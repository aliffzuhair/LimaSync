<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\Finance;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Helpers\ActivityLogger;

class FinanceController extends Controller
{
    /**
     * Display company-wide financial dashboard.
     */
    public function index()
    {
        $totalIncome = Finance::income()->sum('amount');
        $totalExpense = Finance::expense()->sum('amount');
        $netProfit = $totalIncome - $totalExpense;

        // Recent transactions
        $transactions = Finance::with(['event', 'enteredBy'])
            ->latest()
            ->paginate(15);

        // Monthly summary (last 6 months)
        $monthlyIncome = Finance::income()
            ->where('transaction_date', '>=', now()->subMonths(6))
            ->selectRaw('MONTH(transaction_date) as month, YEAR(transaction_date) as year, SUM(amount) as total')
            ->groupBy('year', 'month')
            ->orderBy('year', 'desc')
            ->orderBy('month', 'desc')
            ->get();

        $monthlyExpense = Finance::expense()
            ->where('transaction_date', '>=', now()->subMonths(6))
            ->selectRaw('MONTH(transaction_date) as month, YEAR(transaction_date) as year, SUM(amount) as total')
            ->groupBy('year', 'month')
            ->orderBy('year', 'desc')
            ->orderBy('month', 'desc')
            ->get();

        return view('finance.index', compact(
            'totalIncome',
            'totalExpense',
            'netProfit',
            'transactions',
            'monthlyIncome',
            'monthlyExpense'
        ));
    }

    /**
     * Show finances for a specific event.
     */
    public function eventIndex(Event $event)
    {
        $finances = $event->finances()
            ->with('enteredBy', 'approvedBy')
            ->latest()
            ->get();

        $totalIncome = $event->finances()->income()->sum('amount');
        $totalExpense = $event->finances()->expense()->sum('amount');
        $netProfit = $totalIncome - $totalExpense;

        return view('finance.event', compact(
            'event',
            'finances',
            'totalIncome',
            'totalExpense',
            'netProfit'
        ));
    }

    /**
     * Show form to create a new transaction for an event.
     */
    public function create(Event $event)
    {
        return view('finance.create', compact('event'));
    }

    /**
     * Store a new transaction.
     */
    public function store(Request $request, Event $event)
    {
        $validated = $request->validate([
            'transaction_type' => 'required|in:income,expense',
            'category' => 'required|string|max:50',
            'description' => 'required|string|max:255',
            'amount' => 'required|numeric|min:0.01',
            'transaction_date' => 'required|date',
            'reference_no' => 'nullable|string|max:50',
            'notes' => 'nullable|string',
        ]);

        $validated['event_id'] = $event->id;
        $validated['entered_by'] = Auth::id();
        $validated['is_approved'] = false;

        $finance = Finance::create($validated);

        // ✅ Log activity
        ActivityLogger::create(
            'Finance',
            $finance->id,
            Auth::user()->full_name . ' added ' . $finance->transaction_type . ' of RM ' . number_format((float) $finance->amount, 2) . ' for event "' . $event->event_name . '"'
        );

        return redirect()->route('finance.event', $event)
            ->with('success', 'Transaction added successfully!');
    }

    /**
     * Show form to edit a transaction.
     */
    public function edit(Finance $finance)
    {
        $event = $finance->event;
        return view('finance.edit', compact('finance', 'event'));
    }

    /**
     * Update a transaction.
     */
    public function update(Request $request, Finance $finance)
    {
        $validated = $request->validate([
            'transaction_type' => 'required|in:income,expense',
            'category' => 'required|string|max:50',
            'description' => 'required|string|max:255',
            'amount' => 'required|numeric|min:0.01',
            'transaction_date' => 'required|date',
            'reference_no' => 'nullable|string|max:50',
            'notes' => 'nullable|string',
        ]);

        $finance->update($validated);

        // ✅ Log activity
        ActivityLogger::update(
            'Finance',
            $finance->id,
            Auth::user()->full_name . ' updated ' . $finance->transaction_type . ' of RM ' . number_format((float) $finance->amount, 2) . ' for event "' . ($finance->event->event_name ?? 'N/A') . '"'
        );

        return redirect()->route('finance.event', $finance->event)
            ->with('success', 'Transaction updated successfully!');
    }

    /**
     * Delete a transaction.
     */
    public function destroy(Finance $finance)
    {
        $event = $finance->event;
        $amount = $finance->amount;
        $type = $finance->transaction_type;
        $eventName = $event->event_name ?? 'N/A';
        $financeId = $finance->id;

        $finance->delete();

        // ✅ Log activity
        ActivityLogger::delete(
            'Finance',
            $financeId,
            Auth::user()->full_name . ' deleted ' . $type . ' of RM ' . number_format((float) $amount, 2) . ' for event "' . $eventName . '"'
        );

        return redirect()->route('finance.event', $event)
            ->with('success', 'Transaction deleted successfully!');
    }

    /**
     * Approve a transaction (admin only).
     */
    public function approve(Finance $finance)
    {
        $finance->update([
            'is_approved' => true,
            'approved_by' => Auth::id(),
            'approved_at' => now(),
        ]);

        // ✅ Log activity
        ActivityLogger::update(
            'Finance',
            $finance->id,
            Auth::user()->full_name . ' approved ' . $finance->transaction_type . ' of RM ' . number_format((float) $finance->amount, 2) . ' for event "' . ($finance->event->event_name ?? 'N/A') . '"'
        );

        return redirect()->back()
            ->with('success', 'Transaction approved successfully!');
    }

public function reject(Finance $finance)
    {
        $finance->update([
            'is_approved' => false,
            'approved_by' => null,
            'approved_at' => null,
        ]);

        // ✅ Log activity
        ActivityLogger::update(
            'Finance',
            $finance->id,
            Auth::user()->full_name . ' revoked approval for ' . $finance->transaction_type . ' of RM ' . number_format((float) $finance->amount, 2) . ' for event "' . ($finance->event->event_name ?? 'N/A') . '"'
        );

        return redirect()->back()
            ->with('success', 'Transaction approval revoked.');
    }
}