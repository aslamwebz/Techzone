<?php

namespace App\Services;

use App\Models\ActivityLog;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class ActivityLogger
{
    /**
     * The model that is the subject of the activity.
     *
     * @var Model|null
     */
    protected $subject = null;

    /**
     * The user that triggered the activity.
     *
     * @var Model|null
     */
    protected $causer = null;

    /**
     * The log name.
     *
     * @var string
     */
    protected $logName = 'default';

    /**
     * The event name.
     *
     * @var string|null
     */
    protected $event = null;

    /**
     * The log properties.
     *
     * @var array
     */
    protected $properties = [];

    /**
     * Set the subject of the activity.
     *
     * @return $this
     */
    public function on(?Model $subject = null): self
    {
        $this->subject = $subject;

        return $this;
    }

    /**
     * Set the causer of the activity.
     *
     * @return $this
     */
    public function by(?Model $causer = null): self
    {
        $this->causer = $causer;

        return $this;
    }

    /**
     * Set the log name.
     *
     * @return $this
     */
    public function useLog(string $logName): self
    {
        $this->logName = $logName;

        return $this;
    }

    /**
     * Set the event name.
     *
     * @return $this
     */
    public function event(string $event): self
    {
        $this->event = $event;

        return $this;
    }

    /**
     * Set the properties.
     *
     * @return $this
     */
    public function withProperties(array $properties): self
    {
        $this->properties = array_merge($this->properties, $properties);

        return $this;
    }

    /**
     * Set a single property.
     *
     * @param  mixed  $value
     * @return $this
     */
    public function withProperty(string $key, $value): self
    {
        $this->properties[$key] = $value;

        return $this;
    }

    /**
     * Log the activity.
     *
     * @return ActivityLog
     */
    public function log(string $description)
    {
        // If no causer is set, use the authenticated user
        if (! $this->causer && Auth::check()) {
            $this->causer = Auth::user();
        }

        // Add request information to properties
        $this->withProperties(array_merge($this->properties, [
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
            'method' => request()->method(),
            'url' => request()->fullUrl(),
            'route' => request()->route() ? request()->route()->getName() : null,
        ]));

        return ActivityLog::log(
            $description,
            $this->subject,
            $this->causer,
            $this->properties,
            $this->event,
            $this->logName
        );
    }

    /**
     * Create a new instance of the activity logger.
     *
     * @return static
     */
    public static function new(): self
    {
        return new static;
    }
}
