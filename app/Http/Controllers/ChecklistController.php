<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\EventChecklist;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ChecklistController extends Controller
{
    /**
     * Display the checklist for a specific event.
     */
    public function index(Event $event)
    {
        $checklists = $event->eventChecklists()
            ->with('checklist.phase')
            ->orderBy('checklist_id')
            ->get()
            ->groupBy(function ($item) {
                return $item->checklist->phase->name ?? 'unknown';
            });

        return view('checklists.index', compact('event', 'checklists'));
    }

    /**
     * Update a checklist item status.
     */
    public function update(Request $request, EventChecklist $eventChecklist)
    {
        $validated = $request->validate([
            'status' => 'required|in:pending,in_progress,completed,not_applicable',
            'notes' => 'nullable|string',
        ]);

        // ✅ CHECK IF PHASE IS LOCKED
        $event = $eventChecklist->event;
        $phaseName = $eventChecklist->checklist->phase->name ?? null;

        if ($phaseName && !$event->isPhaseUnlocked($phaseName)) {
            return redirect()->back()->with('error', 'This phase is locked. Complete the previous phase first.');
        }

        $eventChecklist->update([
            'status' => $validated['status'],
            'notes' => $validated['notes'] ?? $eventChecklist->notes,
            'completed_by' => $validated['status'] === 'completed' ? Auth::id() : null,
            'completed_at' => $validated['status'] === 'completed' ? now() : null,
        ]);

        // ✅ Auto-update event progress AND status
        $event->syncStatus();

        $previousPhaseComplete = $event->isPhaseCompleted('pre_event');
        $currentPhaseComplete = $event->isPhaseCompleted('during_event');

        if ($previousPhaseComplete && !$currentPhaseComplete) {
            session()->flash('info', 'Pre-Event completed! During-Event phase is now unlocked.');
        }

        // Log activity if event was completed
        if ($event->status === 'completed') {
            \App\Helpers\ActivityLogger::update(
                'Event',
                $event->id,
                'Event "' . $event->event_name . '" automatically marked as completed.'
            );
        }

        return redirect()->back()->with('success', 'Checklist item updated successfully!');
    }

    public function markComplete(EventChecklist $eventChecklist)
    {
        // ✅ CHECK IF PHASE IS LOCKED
        $event = $eventChecklist->event;
        $phaseName = $eventChecklist->checklist->phase->name ?? null;

        if ($phaseName && !$event->isPhaseUnlocked($phaseName)) {
            return redirect()->back()->with('error', 'This phase is locked. Complete the previous phase first.');
        }

        $eventChecklist->update([
            'status' => 'completed',
            'completed_by' => Auth::id(),
            'completed_at' => now(),
        ]);

        // ✅ Auto-update event progress AND status
        $event->syncStatus();

        if ($event->status === 'completed') {
            \App\Helpers\ActivityLogger::update(
                'Event',
                $event->id,
                'Event "' . $event->event_name . '" automatically marked as completed.'
            );
        }

        return redirect()->back()->with('success', 'Checklist item marked as completed!');
    }
    public function bulkComplete(Request $request, Event $event)
    {
        $validated = $request->validate([
            'checklist_ids' => 'required|array',
            'checklist_ids.*' => 'exists:event_checklists,id',
        ]);

        // ✅ Filter out items from locked phases
        $allowedIds = EventChecklist::whereIn('id', $validated['checklist_ids'])
            ->where('event_id', $event->id)
            ->get()
            ->filter(function ($item) use ($event) {
                $phaseName = $item->checklist->phase->name ?? null;
                return $phaseName && $event->isPhaseUnlocked($phaseName);
            })
            ->pluck('id')
            ->toArray();

        if (empty($allowedIds)) {
            return redirect()->back()->with('error', 'No items can be completed. Check if previous phases are done.');
        }

        EventChecklist::whereIn('id', $allowedIds)
            ->update([
                'status' => 'completed',
                'completed_by' => Auth::id(),
                'completed_at' => now(),
            ]);

        $event->syncStatus();

        return redirect()->back()->with('success', 'Selected checklist items completed!');
}
}