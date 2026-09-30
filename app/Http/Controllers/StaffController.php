<?php

namespace App\Http\Controllers;

use App\Helpers\ActivityLogger;
use App\Models\Role;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;

class StaffController extends Controller
{
    /**
     * Display a listing of all staff.
     */
    public function index(Request $request)
    {
        $query = User::with('role')->latest();

        // Filter by role
        if ($request->filled('role_id')) {
            $query->where('role_id', $request->role_id);
        }

        // Filter by status
        if ($request->filled('status')) {
            $query->where('is_active', $request->status === 'active');
        }

        // Search by name or email
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('full_name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('username', 'like', "%{$search}%")
                  ->orWhere('department', 'like', "%{$search}%");
            });
        }

        $staff = $query->paginate(15);
        $roles = Role::all();

        return view('staff.index', compact('staff', 'roles'));
    }

    /**
     * Show the form for creating a new staff member.
     */
    public function create()
    {
        $roles = Role::all();
        $clients = \App\Models\Client::where('is_active', true)->orderBy('company_name')->get();
        return view('staff.create', compact('roles', 'clients'));
    }

    /**
     * Store a newly created staff member.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'full_name' => 'required|string|max:100',
            'username' => 'required|string|max:50|unique:users,username',
            'email' => 'required|email|max:100|unique:users,email',
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
            'role_id' => 'required|exists:roles,id',
            'client_id' => 'nullable|exists:clients,id',  // <-- ADD
            'department' => 'nullable|string|max:50',
            'phone' => 'nullable|string|max:20',
            'is_active' => 'sometimes|boolean',
        ]);

        $validated['password'] = Hash::make($validated['password']);
        $validated['name'] = $validated['full_name'];
        $validated['is_active'] = $request->has('is_active') ? true : false;

        $staff = User::create($validated);

        ActivityLogger::create('Staff', $staff->id, Auth::user()->full_name . ' added staff: ' . $staff->full_name);

        return redirect()->route('staff.index')
            ->with('success', 'Staff member added successfully!');
    }

    /**
     * Display the specified staff member.
     */
    public function show(User $staff)
    {
        $staff->load('role');
        return view('staff.show', compact('staff'));
    }

    /**
     * Show the form for editing a staff member.
     */
    public function edit(User $staff)
    {
        $roles = Role::all();
        $clients = \App\Models\Client::where('is_active', true)->orderBy('company_name')->get();
        return view('staff.edit', compact('staff', 'roles', 'clients'));
    }

    /**
     * Update the specified staff member.
     */
    public function update(Request $request, User $staff)
    {
        $validated = $request->validate([
            'full_name' => 'required|string|max:100',
            'username' => 'required|string|max:50|unique:users,username,' . $staff->id,
            'email' => 'required|email|max:100|unique:users,email,' . $staff->id,
            'password' => ['nullable', 'confirmed', Rules\Password::defaults()],
            'role_id' => 'required|exists:roles,id',
            'client_id' => 'nullable|exists:clients,id',  // <-- ADD
            'department' => 'nullable|string|max:50',
            'phone' => 'nullable|string|max:20',
            'is_active' => 'sometimes|boolean',
        ]);

        if (!empty($validated['password'])) {
            $validated['password'] = Hash::make($validated['password']);
        } else {
            unset($validated['password']);
        }

        $validated['name'] = $validated['full_name'];
        $validated['is_active'] = $request->has('is_active') ? true : false;

        $staff->update($validated);

        ActivityLogger::update('Staff', $staff->id, Auth::user()->full_name . ' updated staff: ' . $staff->full_name);

        return redirect()->route('staff.index')
            ->with('success', 'Staff member updated successfully!');
    }

    /**
     * Toggle staff active status.
     */
    public function toggleStatus(User $staff)
    {
        // Prevent admin from deactivating themselves
        if ($staff->id === Auth::id()) {
            return redirect()->back()
                ->with('error', 'You cannot deactivate your own account.');
        }

        $staff->update(['is_active' => !$staff->is_active]);
        $status = $staff->is_active ? 'activated' : 'deactivated';

        // Log activity
        ActivityLogger::update('Staff', $staff->id, Auth::user()->full_name . " {$status} staff: " . $staff->full_name);

        return redirect()->back()
            ->with('success', "Staff member {$status} successfully!");
    }

    /**
     * Reset staff password.
     */
    public function resetPassword(Request $request, User $staff)
    {
        $validated = $request->validate([
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        $staff->update([
            'password' => Hash::make($validated['password']),
        ]);

        // Log activity
        ActivityLogger::update('Staff', $staff->id, Auth::user()->full_name . ' reset password for: ' . $staff->full_name);

        return redirect()->back()
            ->with('success', 'Password reset successfully!');
    }

    /**
     * Remove the specified staff member.
     */
    public function destroy(User $staff)
    {
        // Prevent admin from deleting themselves
        if ($staff->id === Auth::id()) {
            return redirect()->back()
                ->with('error', 'You cannot delete your own account.');
        }

        $staffName = $staff->full_name;
        $staffId = $staff->id;

        $staff->delete();

        // Log activity
        ActivityLogger::delete('Staff', $staffId, Auth::user()->full_name . ' deleted staff: ' . $staffName);

        return redirect()->route('staff.index')
            ->with('success', 'Staff member deleted successfully!');
    }
}