<?php

namespace App\Http\Controllers;

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
        return view('dashboard.operations', compact('user'));
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

        // Inventory stats
        $totalItems = \App\Models\Inventory::sum('quantity');
        $lowStockCount = \App\Models\Inventory::whereRaw('quantity < min_quantity')->where('quantity', '>', 0)->count();
        $outOfStockCount = \App\Models\Inventory::where('quantity', '<=', 0)->count();
        $totalCategories = \App\Models\Inventory::distinct('category')->count('category');

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