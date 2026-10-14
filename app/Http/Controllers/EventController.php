<?php

namespace App\Http\Controllers;

use App\Models\Client;
use App\Models\Event;
use App\Models\User;
use App\Mail\EventCreatedNotification;
use App\Helpers\ActivityLogger;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;

class EventController extends Controller
{
    public function index(Request $request)
    {
        $query = Event::with(['client', 'createdBy']);

        // ✅ Search by event name, client name, venue, or event type
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('event_name', 'like', "%{$search}%")
                ->orWhere('event_type', 'like', "%{$search}%")
                ->orWhere('venue', 'like', "%{$search}%")
                ->orWhereHas('client', function ($c) use ($search) {
                    $c->where('company_name', 'like', "%{$search}%");
                });
            });
        }

        // ✅ Filter by Status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $events = $query->latest()->paginate(10)->appends($request->query());

        // Summary counts
        $totalEvents = Event::count();
        $planningEvents = Event::where('status', 'planning')->count();
        $inProgressEvents = Event::where('status', 'in_progress')->count();
        $completedEvents = Event::where('status', 'completed')->count();

        return view('events.index', compact(
            'events',
            'totalEvents',
            'planningEvents',
            'inProgressEvents',
            'completedEvents'
        ));
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

        // Auto-generate checklists
        $this->generateChecklistsForEvent($event);

        // ✅ Load the client
        $client = Client::find($event->client_id);

        // ✅ Send notification to the client
        if ($client && $client->email) {
            try {
                Mail::to($client->email)->send(new EventCreatedNotification($event, $client));
            } catch (\Exception $e) {
                \Log::error('Event notification email failed: ' . $e->getMessage());
            }
        }

        // ✅ Also notify the client-view user account linked to this client
        $clientUser = User::where('client_id', $client->id)
            ->whereHas('role', function ($q) {
                $q->where('name', 'client_view');
            })->first();

        if ($clientUser && $clientUser->email !== $client->email) {
            try {
                Mail::to($clientUser->email)->send(new EventCreatedNotification($event, $client));
            } catch (\Exception $e) {
                \Log::error('Client user email failed: ' . $e->getMessage());
            }
        }

        // ✅ Log activity
        ActivityLogger::create(
            'Event',
            $event->id,
            Auth::user()->full_name . ' created event: ' . $event->event_name . ' for client ' . ($client->company_name ?? 'N/A')
        );

        return redirect()->route('events.index')
            ->with('success', 'Event created successfully! Checklists have been generated and the client has been notified.');
    }

/**
 * Generate checklists for a newly created event.
 */
    private function generateChecklistsForEvent(Event $event)
    {
        $checklistTemplates = \App\Models\Checklist::all();

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