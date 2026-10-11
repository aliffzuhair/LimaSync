<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\User;
use App\Models\Client;
use App\Models\Inventory;
use App\Models\Finance;
use App\Models\ActivityLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        
        // If user has no role, redirect to a default view
        if (!$user->role) {
            return view('dashboard.index', compact('user'));
        }

        $roleName = $user->role->name;

        // Redirect based on role
        switch ($roleName) {
            case 'admin':
                return redirect()->route('dashboard.admin');
            case 'operations':
                return redirect()->route('dashboard.operations');
            case 'sales':
                return redirect()->route('dashboard.sales');
            case 'finance':
                return redirect()->route('dashboard.finance');
            case 'logistics':
                return redirect()->route('dashboard.logistics');
            case 'client_view':
                return redirect()->route('dashboard.client');
            default:
                return view('dashboard.index', compact('user'));
        }
    }

    public function admin()
    {   
    $user = Auth::user();

    // Get recent activities (latest 20)
    $recentActivities = \App\Models\ActivityLog::with('user')
        ->latest()
        ->take(20)
        ->get();

    // Get counts for summary
    $totalUsers = \App\Models\User::count();
    $totalClients = \App\Models\Client::count();
    $totalEvents = \App\Models\Event::count();
    $activeEvents = \App\Models\Event::where('status', 'in_progress')->count();

    // Today's activities
    $todayLogins = \App\Models\ActivityLog::where('action', 'login')
        ->whereDate('created_at', today())
        ->count();
    $todayLogouts = \App\Models\ActivityLog::where('action', 'logout')
        ->whereDate('created_at', today())
        ->count();
    $todayRegistrations = \App\Models\ActivityLog::where('action', 'register')
        ->whereDate('created_at', today())
        ->count();
    $todayCreations = \App\Models\ActivityLog::where('action', 'create')
        ->whereDate('created_at', today())
        ->count();

    return view('dashboard.admin', compact(
        'user',
        'recentActivities',
        'totalUsers',
        'totalClients',
        'totalEvents',
        'activeEvents',
        'todayLogins',
        'todayLogouts',
        'todayRegistrations',
        'todayCreations'
    ));
    }

    public function operations()
    {
        $user = Auth::user();

        // ✅ Count events assigned to this user (via checklist items)
        // OR simply count all events the user has access to
        $myEvents = Event::where('status', '!=', 'cancelled')->count();

        $completedEvents = Event::where('status', 'completed')->count();
        $inProgressEvents = Event::where('status', 'in_progress')->count();

        // Recent events (clickable to checklists)
        $recentEvents = Event::with('client')
            ->latest()
            ->take(5)
            ->get();

        return view('dashboard.operations', compact(
            'user',
            'myEvents',
            'completedEvents',
            'inProgressEvents',
            'recentEvents'
        ));
    }

    public function sales()
    {
        $user = Auth::user();
        return view('dashboard.sales', compact('user'));
    }

    public function finance()
    {
        $user = Auth::user();

        // Get all events for finance to manage
        $events = \App\Models\Event::with(['client', 'finances'])
            ->latest()
            ->take(10)
            ->get();

        // Financial summary
        $totalIncome = \App\Models\Finance::where('transaction_type', 'income')->sum('amount');
        $totalExpense = \App\Models\Finance::where('transaction_type', 'expense')->sum('amount');
        $netProfit = $totalIncome - $totalExpense;

        // Recent transactions
        $recentTransactions = \App\Models\Finance::with(['event', 'enteredBy'])
            ->latest()
            ->take(10)
            ->get();

        return view('dashboard.finance', compact(
            'user',
            'events',
            'totalIncome',
            'totalExpense',
            'netProfit',
            'recentTransactions'
        ));
    }

   public function logistics()
    {
        $user = Auth::user();

        // ✅ Use the same logic as the Inventory page (based on available_quantity)
        $allItems = Inventory::where('is_active', true)->get();

        $totalItems = $allItems->sum('quantity');
        $lowStockCount = $allItems->filter(fn($i) => $i->stock_status === 'Low Stock')->count();
        $outOfStockCount = $allItems->filter(fn($i) => $i->stock_status === 'Out of Stock')->count();
        $sufficientCount = $allItems->filter(fn($i) => $i->stock_status === 'Sufficient')->count();
        $totalCategories = Inventory::distinct('category')->count('category');

        // All events with inventory info
        $events = \App\Models\Event::with(['client', 'eventInventory.inventory'])
            ->latest()
            ->take(10)
            ->get();

        // Recent inventory allocations
        $recentAllocations = \App\Models\EventInventory::with(['event', 'inventory', 'allocatedBy'])
            ->latest()
            ->take(5)
            ->get();

        return view('dashboard.logistics', compact(
            'user',
            'totalItems',
            'sufficientCount',
            'lowStockCount',
            'outOfStockCount',
            'totalCategories',
            'events',
            'recentAllocations'
        ));
    }

    public function client()
    {
        return redirect()->route('client.dashboard');
    }
}