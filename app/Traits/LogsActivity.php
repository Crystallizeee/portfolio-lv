<?php

namespace App\Traits;

use App\Models\ActivityLog;
use Illuminate\Support\Facades\Auth;

trait LogsActivity
{
    protected static function bootLogsActivity()
    {
        static::created(function ($model) {
            self::logActivity('create', $model);
        });

        static::updated(function ($model) {
            self::logActivity('update', $model);
        });

        static::deleted(function ($model) {
            self::logActivity('delete', $model);
        });
    }

    protected static function logActivity($action, $model)
    {
        if (!Auth::check()) return;

        $description = ucfirst($action) . ' ' . class_basename($model);
        
        // Add specific details
        if (isset($model->title)) {
            $description .= ": {$model->title}";
        } elseif (isset($model->name)) {
            $description .= ": {$model->name}";
        } elseif (isset($model->company)) {
            $description .= ": {$model->company}";
        }

        ActivityLog::create([
            'user_id' => Auth::id(),
            'action' => $action,
            'description' => $description,
            'model_type' => get_class($model),
            'model_id' => $model->id,
            'properties' => $model->getDirty(), // Log changed attributes
        ]);
    }
}
