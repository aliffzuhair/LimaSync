<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Event extends Model
{
    use HasFactory;

    protected $fillable = [
        'client_id',
        'event_name',
        'event_type',
        'description',
        'venue',
        'start_date',
        'end_date',
        'start_time',
        'end_time',
        'estimated_attendees',
        'actual_attendees',
        'status',
        'progress_percentage',
        'budget',
        'notes',
        'created_by',
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
        'budget' => 'decimal:2',
    ];

    /**
     * Get the client that owns the event.
     */
    public function client()
    {
        return $this->belongsTo(Client::class);
    }

    /**
     * Get the user who created the event.
     */
    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * Get the financial records for the event.
     */
    public function finances()
    {
        return $this->hasMany(Finance::class);
    }

    /**
     * Get the inventory allocations for the event.
     */
    public function eventInventory()
    {
        return $this->hasMany(EventInventory::class);
    }

    /**
     * Get the sustainability records for the event.
     */
    public function sustainability()
    {
        return $this->hasMany(Sustainability::class);
    }

    /**
     * Get the checklist items for the event.
     */
    public function eventChecklists()
    {
        return $this->hasMany(EventChecklist::class);
    }

    /**
     * Calculate the progress percentage for the event.
     */
    public function calculateProgress()
    {
        $total = $this->eventChecklists()->where('status', '!=', 'not_applicable')->count();
        
        if ($total === 0) {
            return 0;
        }
        
        $completed = $this->eventChecklists()->where('status', 'completed')->count();
        
        return round(($completed / $total) * 100);
    }

    /**
     * Update the event progress percentage.
     */
    public function updateProgress()
    {
        $this->progress_percentage = $this->calculateProgress();
        $this->save();
        
        // Auto-update status if 100% complete
        if ($this->progress_percentage === 100 && $this->status !== 'completed') {
            $this->status = 'completed';
            $this->save();
        }
        
        return $this->progress_percentage;
    }

    /**
     * Get the completion status summary.
     */
    public function getChecklistSummary()
    {
        $total = $this->eventChecklists()->count();
        $completed = $this->eventChecklists()->where('status', 'completed')->count();
        $inProgress = $this->eventChecklists()->where('status', 'in_progress')->count();
        $pending = $this->eventChecklists()->where('status', 'pending')->count();
        $notApplicable = $this->eventChecklists()->where('status', 'not_applicable')->count();

        return [
            'total' => $total,
            'completed' => $completed,
            'in_progress' => $inProgress,
            'pending' => $pending,
            'not_applicable' => $notApplicable,
            'percentage' => $this->calculateProgress(),
        ];
    }

    /**
     * Check if the event is fully completed.
     */
    public function isComplete()
    {
        return $this->calculateProgress() === 100 && $this->status === 'completed';
    }

    /**
     * Scope a query to only include active events.
     */
    public function scopeActive($query)
    {
        return $query->whereIn('status', ['planning', 'in_progress']);
    }

    /**
     * Scope a query to only include completed events.
     */
    public function scopeCompleted($query)
    {
        return $query->where('status', 'completed');
    }

    /**
     * Scope a query to only include events for a specific client.
     */
    public function scopeForClient($query, $clientId)
    {
        return $query->where('client_id', $clientId);
    }

    /**
     * Get total income for the event.
     */
    public function getTotalIncomeAttribute()
    {
        return $this->finances()->where('transaction_type', 'income')->sum('amount');
    }

    /**
     * Get total expenses for the event.
     */
    public function getTotalExpenseAttribute()
    {
        return $this->finances()->where('transaction_type', 'expense')->sum('amount');
    }

    /**
     * Get net profit for the event.
     */
    public function getNetProfitAttribute()
    {
        return $this->total_income - $this->total_expense;
    }

    /**
     * Get budget utilization percentage.
     */
    public function getBudgetUtilizationAttribute()
    {
        if (!$this->budget || $this->budget == 0) {
            return 0;
        }
        return round(($this->total_expense / $this->budget) * 100, 2);
    }

    /**
     * Get total carbon footprint for the event.
     */
    public function getTotalCarbonAttribute()
    {
        return $this->sustainability()->where('metric_type', 'carbon')->sum('value');
    }

    /**
     * Get total electricity usage for the event.
     */
    public function getTotalElectricityAttribute()
    {
        return $this->sustainability()->where('metric_type', 'electricity')->sum('value');
    }

    /**
     * Get total water usage for the event.
     */
    public function getTotalWaterAttribute()
    {
        return $this->sustainability()->where('metric_type', 'water')->sum('value');
    }

    /**
     * Get total waste for the event.
     */
    public function getTotalWasteAttribute()
    {
        return $this->sustainability()->where('metric_type', 'waste')->sum('value');
    }

    /**
     * Check if sustainability data is verified.
     */
    public function getSustainabilityVerifiedAttribute()
    {
        return $this->sustainability()->where('is_verified', true)->count() > 0;
    }

    /**
     * Get sustainability summary.
     */
    public function getSustainabilitySummaryAttribute()
    {
        return [
            'carbon' => $this->total_carbon,
            'electricity' => $this->total_electricity,
            'water' => $this->total_water,
            'waste' => $this->total_waste,
        ];
    }

    /**
     * Get all reports for the event.
     */
    public function reports()
    {
        return $this->hasMany(Report::class);
    }

    /**
     * Check if the event is ready for report generation.
     */
    public function isReadyForReport()
    {
        return $this->progress_percentage === 100 || $this->eventChecklists()->where('status', '!=', 'completed')->where('status', '!=', 'not_applicable')->count() === 0;
    }

    /**
     * Get the latest report of a specific type.
     */
    public function getLatestReport($type = 'final')
    {
        return $this->reports()->where('report_type', $type)->latest()->first();
    }

    public function syncStatus()
    {
    // Don't override cancelled events
    if ($this->status === 'cancelled') {
        return $this->status;
    }

    $progress = $this->calculateProgress();
    
    $newStatus = match(true) {
        $progress >= 100 => 'completed',
        $progress > 0    => 'in_progress',
        default          => 'planning',
    };

    // Update both fields
    $this->progress_percentage = $progress;
    $this->status = $newStatus;
    $this->save();   // <-- Must save!

    return $newStatus;
    }

        /**
     * Get the live status based on current checklist progress.
     */
    public function getLiveStatusAttribute()
    {
        // Manually cancelled stays cancelled
        if ($this->status === 'cancelled') {
            return 'cancelled';
        }

        $progress = $this->calculateProgress();

        if ($progress >= 100) {
            return 'completed';
        }
        if ($progress > 0) {
            return 'in_progress';
        }
        return 'planning';
    }

    /**
     * Get the live progress percentage.
     */
    public function getLiveProgressAttribute()
    {
        return $this->calculateProgress();
    }

    /**
     * Get live status badge color.
     */
    public function getLiveStatusBadgeAttribute()
    {
        return match($this->live_status) {
            'completed' => 'success',
            'in_progress' => 'warning',
            'cancelled' => 'danger',
            default => 'secondary',
        };
    }

    /**
     * Get live status icon.
     */
    public function getLiveStatusIconAttribute()
    {
        return match($this->live_status) {
            'completed' => 'check-circle',
            'in_progress' => 'spinner',
            'cancelled' => 'ban',
            default => 'clock',
        };
    }

        /**
     * Check if a specific phase is fully completed for this event.
     */
    public function isPhaseCompleted($phaseName)
    {
        $phaseItems = $this->eventChecklists()
            ->whereHas('checklist.phase', function ($q) use ($phaseName) {
                $q->where('name', $phaseName);
            })
            ->where('status', '!=', 'not_applicable')
            ->get();

        if ($phaseItems->isEmpty()) {
            return false;
        }

        // Every applicable item must be 'completed'
        return $phaseItems->every(function ($item) {
            return $item->status === 'completed';
        });
    }

    /**
     * Get the phase completion counts.
     */
    public function getPhaseProgress($phaseName)
    {
        $items = $this->eventChecklists()
            ->whereHas('checklist.phase', function ($q) use ($phaseName) {
                $q->where('name', $phaseName);
            })
            ->get();

        $total = $items->where('status', '!=', 'not_applicable')->count();
        $completed = $items->where('status', 'completed')->count();

        return [
            'total' => $total,
            'completed' => $completed,
            'percentage' => $total > 0 ? round(($completed / $total) * 100) : 0,
        ];
    }

    /**
     * Check if a phase is unlocked (previous phase is complete).
     */
    public function isPhaseUnlocked($phaseName)
    {
        // Pre-event is always unlocked
        if ($phaseName === 'pre_event') {
            return true;
        }

        // During-event requires pre-event to be complete
        if ($phaseName === 'during_event') {
            return $this->isPhaseCompleted('pre_event');
        }

        // Post-event requires during-event to be complete
        if ($phaseName === 'post_event') {
            return $this->isPhaseCompleted('during_event');
        }

        return false;
    }
}