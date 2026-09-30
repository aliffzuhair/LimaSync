<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\Sustainability;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SustainabilityController extends Controller
{
    /**
     * Display sustainability data for a specific event.
     */
    public function index(Event $event)
    {
        $sustainability = $event->sustainability()
            ->with('recordedBy', 'verifiedBy')
            ->latest()
            ->get();

        $summary = [
            'carbon' => $event->total_carbon,
            'electricity' => $event->total_electricity,
            'water' => $event->total_water,
            'waste' => $event->total_waste,
        ];

        return view('sustainability.index', compact('event', 'sustainability', 'summary'));
    }

    /**
     * Show form to create sustainability data.
     */
    public function create(Event $event)
    {
        return view('sustainability.create', compact('event'));
    }

    /**
     * Store sustainability data.
     */
    public function store(Request $request, Event $event)
    {
        $validated = $request->validate([
            'metric_type' => 'required|in:carbon,electricity,water,waste',
            'metric_name' => 'required|string|max:100',
            'value' => 'required|numeric|min:0',
            'unit' => 'required|string|max:20',
            'measurement_date' => 'required|date',
            'source' => 'nullable|string|max:100',
            'notes' => 'nullable|string',
        ]);

        $validated['event_id'] = $event->id;
        $validated['recorded_by'] = Auth::id();
        $validated['is_verified'] = false;

        Sustainability::create($validated);

        return redirect()->route('sustainability.index', $event)
            ->with('success', 'Sustainability data added successfully!');
    }

    /**
     * Show form to edit sustainability data.
     */
    public function edit(Sustainability $sustainability)
    {
        $event = $sustainability->event;
        return view('sustainability.edit', compact('sustainability', 'event'));
    }

    /**
     * Update sustainability data.
     */
    public function update(Request $request, Sustainability $sustainability)
    {
        $validated = $request->validate([
            'metric_type' => 'required|in:carbon,electricity,water,waste',
            'metric_name' => 'required|string|max:100',
            'value' => 'required|numeric|min:0',
            'unit' => 'required|string|max:20',
            'measurement_date' => 'required|date',
            'source' => 'nullable|string|max:100',
            'notes' => 'nullable|string',
        ]);

        $sustainability->update($validated);

        return redirect()->route('sustainability.index', $sustainability->event)
            ->with('success', 'Sustainability data updated successfully!');
    }

    /**
     * Delete sustainability data.
     */
    public function destroy(Sustainability $sustainability)
    {
        $event = $sustainability->event;
        $sustainability->delete();

        return redirect()->route('sustainability.index', $event)
            ->with('success', 'Sustainability data deleted successfully!');
    }

    /**
     * Verify sustainability data (admin only).
     */
    public function verify(Sustainability $sustainability)
    {
        $sustainability->update([
            'is_verified' => true,
            'verified_by' => Auth::id(),
            'verified_at' => now(),
        ]);

        return redirect()->back()
            ->with('success', 'Sustainability data verified successfully!');
    }

    /**
     * Unverify sustainability data (admin only).
     */
    public function unverify(Sustainability $sustainability)
    {
        $sustainability->update([
            'is_verified' => false,
            'verified_by' => null,
            'verified_at' => null,
        ]);

        return redirect()->back()
            ->with('success', 'Verification removed.');
    }
}