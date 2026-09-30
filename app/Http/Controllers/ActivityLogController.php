<?php

namespace App\Http\Controllers;

use App\Models\ActivityLog;
use Illuminate\Http\Request;

class ActivityLogController extends Controller
{
    public function index(Request $request)
    {
        $query = ActivityLog::with('user')->latest();

        // Filter by action
        if ($request->has('action') && $request->action) {
            $query->where('action', $request->action);
        }

        // Filter by user
        if ($request->has('user_id') && $request->user_id) {
            $query->where('user_id', $request->user_id);
        }

        // Filter by date
        if ($request->has('date') && $request->date) {
            $query->whereDate('created_at', $request->date);
        }

        // ✅ Filter by model type (e.g., Report)
        if ($request->has('model_type') && $request->model_type) {
            $query->where('model_type', $request->model_type);
        }

        $activities = $query->paginate(30);
        $users = \App\Models\User::all();

        return view('activity.index', compact('activities', 'users'));
    }
    public function clear()
    {
        ActivityLog::truncate();
        return redirect()->route('activity.index')
            ->with('success', 'Activity log cleared successfully!');
    }
}