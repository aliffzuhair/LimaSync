<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\Sustainability;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ClientDashboardController extends Controller
{
    /**
     * Display the client dashboard with sustainability charts and event progress.
     */
    public function index()
{
    $user = Auth::user();
    $clientId = $user->client_id;
    $isStaffView = in_array($user->role->name ?? '', ['admin', 'operations', 'sales', 'finance', 'logistics']);

    $eventsQuery = Event::with(['sustainability', 'client', 'eventChecklists']);

    if (!$isStaffView && $clientId) {
        $eventsQuery->where('client_id', $clientId);
    }

    // Events with verified sustainability
    $events = (clone $eventsQuery)
        ->whereHas('sustainability', function ($query) {
            $query->where('is_verified', true);
        })
        ->latest()
        ->take(10)
        ->get();

    // All events for progress
    $allEvents = (clone $eventsQuery)
        ->latest()
        ->take(10)
        ->get();

    // Average progress
    $totalEvents = $allEvents->count();
    $averageProgress = $totalEvents > 0 ? round($allEvents->avg('progress_percentage')) : 0;

    // Progress breakdown
    $completedCount = $allEvents->where('progress_percentage', 100)->count();
    $nearCompleteCount = $allEvents->whereBetween('progress_percentage', [75, 99])->count();
    $inProgressCount = $allEvents->whereBetween('progress_percentage', [25, 74])->count();
    $startingCount = $allEvents->whereBetween('progress_percentage', [1, 24])->count();
    $notStartedCount = $allEvents->where('progress_percentage', 0)->count();

    // Sustainability data
    $sustainabilityQuery = Sustainability::where('is_verified', true);
    if (!$isStaffView && $clientId) {
        $sustainabilityQuery->whereHas('event', function ($q) use ($clientId) {
            $q->where('client_id', $clientId);
        });
    }

    $sustainabilityData = [
        'carbon' => (clone $sustainabilityQuery)->where('metric_type', 'carbon')->sum('value'),
        'electricity' => (clone $sustainabilityQuery)->where('metric_type', 'electricity')->sum('value'),
        'water' => (clone $sustainabilityQuery)->where('metric_type', 'water')->sum('value'),
        'waste' => (clone $sustainabilityQuery)->where('metric_type', 'waste')->sum('value'),
    ];

    $chartData = $this->getChartData($clientId, $isStaffView);

    // ✅ Pass ALL variables to the view
    return view('dashboard.client', compact(
        'user',
        'events',
        'allEvents',
        'sustainabilityData',
        'chartData',
        'averageProgress',
        'totalEvents',
        'completedCount',
        'nearCompleteCount',
        'inProgressCount',
        'startingCount',
        'notStartedCount'
    ));
}

    /**
     * Get chart data for client dashboard.
     */
    private function getChartData($clientId = null, $isStaffView = true)
    {
        $query = Event::with(['sustainability' => function ($query) {
            $query->where('is_verified', true);
        }])
        ->whereHas('sustainability', function ($query) {
            $query->where('is_verified', true);
        });

        // Filter by client if this is a client view
        if (!$isStaffView && $clientId) {
            $query->where('client_id', $clientId);
        }

        $events = $query->latest()->take(6)->get();

        $labels = [];
        $carbon = [];
        $electricity = [];
        $water = [];
        $waste = [];

        foreach ($events as $event) {
            $labels[] = $event->event_name;
            $carbon[] = $event->sustainability->where('metric_type', 'carbon')->sum('value');
            $electricity[] = $event->sustainability->where('metric_type', 'electricity')->sum('value');
            $water[] = $event->sustainability->where('metric_type', 'water')->sum('value');
            $waste[] = $event->sustainability->where('metric_type', 'waste')->sum('value');
        }

        return [
            'labels' => $labels,
            'carbon' => $carbon,
            'electricity' => $electricity,
            'water' => $water,
            'waste' => $waste,
        ];
    }

    /**
     * Get progress data for events.
     */
    private function getProgressData($events)
    {
        $labels = [];
        $progress = [];

        foreach ($events as $event) {
            $labels[] = $event->event_name;
            $progress[] = $event->progress_percentage;
        }

        return [
            'labels' => $labels,
            'progress' => $progress,
        ];
    }
    public function showEvent(Event $event)
    {
        $user = Auth::user();

        // ✅ SECURITY: Client can only view their own events
        if ($user->client_id && $event->client_id !== $user->client_id) {
            abort(403, 'You do not have access to this event.');
        }

        // Load relationships
        $event->load([
            'client',
            'eventChecklists.checklist.phase',
            'eventChecklists.completedBy',
            'sustainability',
            'reports',
        ]);

        // ✅ Group checklists by phase for display
        $checklistsByPhase = $event->eventChecklists
            ->sortBy(function ($item) {
                return $item->checklist->phase->sort_order ?? 0;
            })
            ->groupBy(function ($item) {
                return $item->checklist->phase->name ?? 'unknown';
            });

        // Checklist summary
        $checklistSummary = $event->getChecklistSummary();

        // Phase progress
        $phaseProgress = [
            'pre_event' => $event->getPhaseProgress('pre_event'),
            'during_event' => $event->getPhaseProgress('during_event'),
            'post_event' => $event->getPhaseProgress('post_event'),
        ];

        // Sustainability summary (verified only)
        $sustainabilitySummary = [
            'carbon' => $event->sustainability()->where('metric_type', 'carbon')->where('is_verified', true)->sum('value'),
            'electricity' => $event->sustainability()->where('metric_type', 'electricity')->where('is_verified', true)->sum('value'),
            'water' => $event->sustainability()->where('metric_type', 'water')->where('is_verified', true)->sum('value'),
            'waste' => $event->sustainability()->where('metric_type', 'waste')->where('is_verified', true)->sum('value'),
        ];

        // Available reports
        $reports = $event->reports()->with('generatedBy')->latest()->get();

        return view('dashboard.client-event-show', compact(
            'user',
            'event',
            'checklistsByPhase',
            'checklistSummary',
            'phaseProgress',
            'sustainabilitySummary',
            'reports'
        ));
    }
}