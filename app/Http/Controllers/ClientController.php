<?php

namespace App\Http\Controllers;

use App\Models\Client;
use Illuminate\Http\Request;
use App\Helpers\ActivityLogger;
use Illuminate\Support\Facades\Auth;

class ClientController extends Controller
{
    public function index(Request $request)
    {
        $query = Client::query();

        // ✅ Search by name, contact person, email, or phone
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('company_name', 'like', "%{$search}%")
                  ->orWhere('contact_person', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('phone', 'like', "%{$search}%");
            });
        }

        // ✅ Filter by Industry
        if ($request->filled('industry')) {
            $query->where('industry', $request->industry);
        }

        // ✅ Filter by Status
        if ($request->filled('status')) {
            $query->where('is_active', $request->status === 'active');
        }

        $clients = $query->latest()->paginate(10)->appends($request->query());

        // Get unique industries for filter dropdown
        $industries = Client::distinct()->pluck('industry')->filter()->sort();

        // Summary counts
        $totalClients = Client::count();
        $activeClients = Client::where('is_active', true)->count();
        $inactiveClients = Client::where('is_active', false)->count();

        return view('clients.index', compact(
            'clients',
            'industries',
            'totalClients',
            'activeClients',
            'inactiveClients'
        ));
    }
    public function create()
    {
        return view('clients.create');
    }

    public function store(Request $request)
    {
    $validated = $request->validate([
        'company_name' => 'required|string|max:100',
        'contact_person' => 'required|string|max:100',
        'email' => 'required|email|max:100',
        'phone' => 'required|string|max:20',
        'address' => 'nullable|string',
        'industry' => 'nullable|string|max:50',
        'notes' => 'nullable|string',
    ]);

    $validated['is_active'] = true;

    $client = Client::create($validated);

    // Log activity
    ActivityLogger::create('Client', $client->id, Auth::user()->full_name . ' added client: ' . $client->company_name);

    return redirect()->route('clients.index')
        ->with('success', 'Client added successfully!');
    }

    public function show(Client $client)
    {
        $client->load('events');
        return view('clients.show', compact('client'));
    }

    public function edit(Client $client)
    {
        return view('clients.edit', compact('client'));
    }

    public function update(Request $request, Client $client)
    {
    $validated = $request->validate([
        'company_name' => 'required|string|max:100',
        'contact_person' => 'required|string|max:100',
        'email' => 'required|email|max:100',
        'phone' => 'required|string|max:20',
        'address' => 'nullable|string',
        'industry' => 'nullable|string|max:50',
        'notes' => 'nullable|string',
        'is_active' => 'sometimes|boolean',
    ]);

    $client->update($validated);

    // Log activity
    ActivityLogger::update('Client', $client->id, Auth::user()->full_name . ' updated client: ' . $client->company_name);

    return redirect()->route('clients.index')
        ->with('success', 'Client updated successfully!');
    }

    public function destroy(Client $client)
    {
    if ($client->events()->count() > 0) {
        return redirect()->route('clients.index')
            ->with('error', 'Cannot delete client because they have associated events.');
    }

    $clientName = $client->company_name;
    $client->delete();

    // Log activity
    ActivityLogger::delete('Client', $client->id, Auth::user()->full_name . ' deleted client: ' . $clientName);

    return redirect()->route('clients.index')
        ->with('success', 'Client deleted successfully!');
    }
}