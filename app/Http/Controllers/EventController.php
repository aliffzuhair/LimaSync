<?php

namespace App\Http\Controllers;

use App\Models\Client;
use App\Models\Event;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Helpers\ActivityLogger;

class EventController extends Controller
{
    public function index()
    {
        $events = Event::with(['client', 'createdBy'])->latest()->paginate(10);
        return view('events.index', compact('events'));
    }

    public function create()
    {
        $clients = Client::where('is_active', true)->get();
        return view('events.create', compact('clients'));
    }

    public function store(Request $request)
    {
    $validated = $request->validate([
        'client_id' => 'required|exists:clients,id',
        'event_name' => 'required|string|max:100',
        'event_type' => 'required|string|max:50',
        'description' => 'nullable|string',
        'venue' => 'nullable|string|max:200',
        'start_date' => 'required|date',
        'end_date' => 'required|date|after_or_equal:start_date',
        'start_time' => 'nullable',
        'end_time' => 'nullable',
        'estimated_attendees' => 'nullable|integer|min:0',
        'budget' => 'nullable|numeric|min:0',
        'notes' => 'nullable|string',
    ]);

    $validated['created_by'] = Auth::id();
    $validated['status'] = 'planning';
    $validated['progress_percentage'] = 0;

    $event = Event::create($validated);

    $this->generateChecklistsForEvent($event);

    // Log activity
    ActivityLogger::create('Event', $event->id, Auth::user()->full_name . ' created event: ' . $event->event_name);

    return redirect()->route('events.index')
        ->with('success', 'Event created successfully! Checklists have been generated.');
    }

/**
 * Generate checklists for a newly created event.
 */
    private function generateChecklistsForEvent(Event $event)
    {
    // Get all checklist templates
            $checklistTemplates = \App\Models\Checklist::all();

    // Create event checklist items for each template
            foreach ($checklistTemplates as $template) {
                \App\Models\EventChecklist::create([
                    'event_id' => $event->id,
                    'checklist_id' => $template->id,
                    'status' => 'pending',
            ]);
        }
    }

    public function show(Event $event)
    {
        $event->load(['client', 'createdBy']);
        return view('events.show', compact('event'));
    }

    public function edit(Event $event)
    {
        $clients = Client::where('is_active', true)->get();
        return view('events.edit', compact('event', 'clients'));
    }

    public function update(Request $request, Event $event)
    {
        $validated = $request->validate([
            'client_id' => 'required|exists:clients,id',
            'event_name' => 'required|string|max:100',
            'event_type' => 'required|string|max:50',
            'description' => 'nullable|string',
            'venue' => 'nullable|string|max:200',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'start_time' => 'nullable',
            'end_time' => 'nullable',
            'estimated_attendees' => 'nullable|integer|min:0',
            'budget' => 'nullable|numeric|min:0',
            'notes' => 'nullable|string',
            'status' => 'in:planning,in_progress,completed,cancelled',
        ]);

        $event->update($validated);

        // ✅ Auto-sync status (except if manually cancelled)
        if ($event->status !== 'cancelled') {
            $event->syncStatus();
        }

        ActivityLogger::update('Event', $event->id, Auth::user()->full_name . ' updated event: ' . $event->event_name);

        return redirect()->route('events.index')
            ->with('success', 'Event updated successfully!');
    }

    public function destroy(Event $event)
    {
    $eventName = $event->event_name;
    $eventId = $event->id;
    $event->delete();

    // Log activity
    ActivityLogger::delete('Event', $eventId, Auth::user()->full_name . ' deleted event: ' . $eventName);

    return redirect()->route('events.index')
        ->with('success', 'Event deleted successfully!');
    }

    
}