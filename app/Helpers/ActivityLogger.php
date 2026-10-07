<?php

namespace App\Helpers;

use App\Mail\CrudNotification;
use App\Models\ActivityLog;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;

class ActivityLogger
{
    /**
     * Send notification emails for CRUD actions.
     */
    private static function sendEmail($action, $modelType, $modelName, $description)
    {
        if (!in_array($action, ['create', 'update', 'delete'])) {
            return;
        }

        $recipients = [];

        // Determine who to notify based on model type
        switch (strtolower($modelType)) {
            case 'client':
                $recipients = \App\Models\User::whereHas('role', function ($q) {
                    $q->whereIn('name', ['admin', 'sales']);
                })->pluck('email')->toArray();
                break;

            case 'event':
                $recipients = \App\Models\User::whereHas('role', function ($q) {
                    $q->whereIn('name', ['admin', 'operations']);
                })->pluck('email')->toArray();
                break;

            case 'inventory':
                $recipients = \App\Models\User::whereHas('role', function ($q) {
                    $q->whereIn('name', ['admin', 'logistics']);
                })->pluck('email')->toArray();
                break;

            case 'finance':
                $recipients = \App\Models\User::whereHas('role', function ($q) {
                    $q->whereIn('name', ['admin', 'finance']);
                })->pluck('email')->toArray();
                break;

            default:
                // Default: notify all admins
                $recipients = \App\Models\User::whereHas('role', function ($q) {
                    $q->where('name', 'admin');
                })->pluck('email')->toArray();
        }

        if (!empty($recipients)) {
            try {
                Mail::to($recipients)->send(new CrudNotification(
                    $action,
                    $modelType,
                    $modelName,
                    Auth::user()->full_name ?? 'System',
                    $description
                ));
            } catch (\Exception $e) {
                \Log::error('Email notification failed: ' . $e->getMessage());
            }
        }
    }

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

        // Send email notification
        self::sendEmail($action, $modelType, $description, $description);
    }

    public static function create($modelName, $modelId, $description = null)
    {
        $description = $description ?? Auth::user()->full_name . ' created a new ' . $modelName;

        ActivityLog::create([
            'user_id' => Auth::id(),
            'action' => 'create',
            'description' => $description,
            'model_type' => $modelName,
            'model_id' => $modelId,
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
        ]);

        self::sendEmail('create', $modelName, $description, $description);
    }

    public static function update($modelName, $modelId, $description = null)
    {
        $description = $description ?? Auth::user()->full_name . ' updated ' . $modelName;

        ActivityLog::create([
            'user_id' => Auth::id(),
            'action' => 'update',
            'description' => $description,
            'model_type' => $modelName,
            'model_id' => $modelId,
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
        ]);

        self::sendEmail('update', $modelName, $description, $description);
    }

    public static function delete($modelName, $modelId, $description = null)
    {
        $description = $description ?? Auth::user()->full_name . ' deleted ' . $modelName;

        ActivityLog::create([
            'user_id' => Auth::id(),
            'action' => 'delete',
            'description' => $description,
            'model_type' => $modelName,
            'model_id' => $modelId,
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
        ]);

        self::sendEmail('delete', $modelName, $description, $description);
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
}