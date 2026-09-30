<?php

namespace App\Helpers;

use App\Models\ActivityLog;
use Illuminate\Support\Facades\Auth;

class ActivityLogger
{
    /**
     * Log an activity.
     */
    public static function log($action, $description, $modelType = null, $modelId = null)
    {
        // Prevent duplicate logs within 5 seconds
        $recentLog = ActivityLog::where('user_id', Auth::id())
            ->where('action', $action)
            ->where('model_type', $modelType)
            ->where('model_id', $modelId)
            ->where('created_at', '>=', now()->subSeconds(5))
            ->exists();

        if ($recentLog) {
            return;
        }

        ActivityLog::create([
            'user_id' => Auth::id(),
            'action' => $action,
            'description' => $description,
            'model_type' => $modelType,
            'model_id' => $modelId,
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
        ]);
    }

    public static function login($userId, $userName)
    {
        ActivityLog::create([
            'user_id' => $userId,
            'action' => 'login',
            'description' => $userName . ' logged in',
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
        ]);
    }

    public static function logout($userId, $userName)
    {
        ActivityLog::create([
            'user_id' => $userId,
            'action' => 'logout',
            'description' => $userName . ' logged out',
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
        ]);
    }

    public static function register($userId, $userName)
    {
        ActivityLog::create([
            'user_id' => $userId,
            'action' => 'register',
            'description' => $userName . ' registered a new account',
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
        ]);
    }

    public static function create($modelName, $modelId, $description = null)
    {
        ActivityLog::create([
            'user_id' => Auth::id(),
            'action' => 'create',
            'description' => $description ?? Auth::user()->full_name . ' created a new ' . $modelName,
            'model_type' => $modelName,
            'model_id' => $modelId,
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
        ]);
    }

    public static function update($modelName, $modelId, $description = null)
    {
        ActivityLog::create([
            'user_id' => Auth::id(),
            'action' => 'update',
            'description' => $description ?? Auth::user()->full_name . ' updated ' . $modelName,
            'model_type' => $modelName,
            'model_id' => $modelId,
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
        ]);
    }

    public static function delete($modelName, $modelId, $description = null)
    {
        ActivityLog::create([
            'user_id' => Auth::id(),
            'action' => 'delete',
            'description' => $description ?? Auth::user()->full_name . ' deleted ' . $modelName,
            'model_type' => $modelName,
            'model_id' => $modelId,
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
        ]);
    }
}