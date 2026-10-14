<?php

namespace App\Http\Controllers;

use App\Models\Client;
use App\Models\User;
use App\Models\Role;
use App\Mail\WelcomeCredentials;
use App\Helpers\ActivityLogger;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;

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

        // ✅ Create the client
        $client = Client::create($validated);

        // ✅ Check if a user with this email already exists
        $existingUser = User::where('email', $client->email)->first();

        if (!$existingUser) {
            // ✅ Get the Client View role
            $clientRole = Role::where('name', 'client_view')->first();

            if ($clientRole) {
                // Generate a unique username from company name
                $baseUsername = strtolower(preg_replace('/[^a-z0-9]/i', '', $client->company_name));
                $username = $baseUsername;
                $counter = 1;

                // Ensure unique username
                while (User::where('username', $username)->exists()) {
                    $username = $baseUsername . $counter;
                    $counter++;
                }

                // Default password
                $defaultPassword = 'password123';

                // ✅ Create the client-view user account
                $clientUser = User::create([
                    'role_id' => $clientRole->id,
                    'client_id' => $client->id,
                    'username' => $username,
                    'name' => $client->contact_person,
                    'full_name' => $client->contact_person,
                    'email' => $client->email,
                    'password' => Hash::make($defaultPassword),
                    'department' => 'Client',
                    'phone' => $client->phone,
                    'is_active' => true,
                ]);

                // ✅ Send welcome email with credentials
                try {
                    Mail::to($clientUser->email)->send(new WelcomeCredentials($clientUser, $defaultPassword));
                } catch (\Exception $e) {
                    \Log::error('Client welcome email failed: ' . $e->getMessage());
                }

                // ✅ Log activity
                ActivityLogger::create(
                    'Client',
                    $client->id,
                    Auth::user()->full_name . ' added client: ' . $client->company_name . ' and created a client account for ' . $clientUser->email
                );

                return redirect()->route('clients.index')
                    ->with('success', 'Client added successfully! A client-view account has been created and login credentials have been emailed to ' . $client->email);
            }
        }

        // ✅ Log activity if account wasn't created
        ActivityLogger::create(
            'Client',
            $client->id,
            Auth::user()->full_name . ' added client: ' . $client->company_name
        );

        return redirect()->route('clients.index')
            ->with('success', 'Client added successfully! (A user account with this email already exists.)');
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