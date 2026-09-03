<?php
namespace App\Support;

use App\Models\ActivityLog;
use Illuminate\Database\Eloquent\Model;

class ActivityLogger
{
    public static function log(?int $userId, string $action, ?Model $subject = null, ?string $description = null, array $properties = []): void
    {
        ActivityLog::create([
            'user_id' => $userId,
            'action' => $action,
            'subject_type' => $subject ? get_class($subject) : null,
            'subject_id' => $subject?->getKey(),
            'ip_address' => request()?->ip(),
            'description' => $description,
            'properties' => $properties ?: null,
        ]);
    }
}