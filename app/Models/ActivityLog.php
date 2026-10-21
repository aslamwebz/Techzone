<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class ActivityLog extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'log_name',
        'description',
        'subject_type',
        'subject_id',
        'causer_type',
        'causer_id',
        'properties',
        'event',
        'batch_uuid',
        'ip_address',
        'user_agent',
        'method',
        'url',
        'route',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'properties' => 'array',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Get the subject of the activity.
     */
    public function subject(): MorphTo
    {
        return $this->morphTo();
    }

    /**
     * Get the user that triggered the activity.
     */
    public function causer(): MorphTo
    {
        return $this->morphTo();
    }

    /**
     * Scope a query to only include activities by a given causer.
     */
    public function scopeCausedBy($query, $causer)
    {
        return $query->where('causer_type', get_class($causer))
            ->where('causer_id', $causer->getKey());
    }

    /**
     * Scope a query to only include activities for a given subject.
     */
    public function scopeForSubject($query, $subject)
    {
        return $query->where('subject_type', get_class($subject))
            ->where('subject_id', $subject->getKey());
    }

    /**
     * Get the extra properties with the given name.
     */
    public function getExtraProperty(string $propertyName, mixed $defaultValue = null): mixed
    {
        return data_get($this->properties, $propertyName, $defaultValue);
    }

    /**
     * Log a new activity.
     */
    public static function log(
        string $description,
        $subject = null,
        $causer = null,
        array $properties = [],
        ?string $event = null,
        ?string $logName = null
    ): self {
        $log = new static;

        $log->fill([
            'log_name' => $logName ?? config('activitylog.default_log_name', 'default'),
            'description' => $description,
            'event' => $event,
            'properties' => $properties,
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
            'method' => request()->method(),
            'url' => request()->fullUrl(),
            'route' => request()->route() ? request()->route()->getName() : null,
        ]);

        if ($subject) {
            $log->subject()->associate($subject);
        }

        if ($causer) {
            $log->causer()->associate($causer);
        } elseif (auth()->check()) {
            $log->causer()->associate(auth()->user());
        }

        $log->save();

        return $log;
    }
}
