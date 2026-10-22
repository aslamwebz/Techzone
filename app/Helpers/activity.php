<?php

use App\Models\ActivityLog;
use App\Services\ActivityLogger;

if (! function_exists('activity')) {
    /**
     * Log an activity.
     *
     * @return ActivityLogger|ActivityLog
     */
    function activity(?string $description = null)
    {
        $activityLogger = app(ActivityLogger::class);

        if (is_null($description)) {
            return $activityLogger;
        }

        return $activityLogger->log($description);
    }
}
