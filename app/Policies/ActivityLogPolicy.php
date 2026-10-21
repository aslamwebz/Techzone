<?php

namespace App\Policies;

use App\Models\ActivityLog;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class ActivityLogPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->can('view activity logs');
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, ActivityLog $activityLog): bool
    {
        return $user->can('view activity logs');
    }

    /**
     * Determine whether the user can view the dashboard.
     */
    public function viewDashboard(User $user): bool
    {
        return $user->can('view activity logs');
    }

    /**
     * Determine whether the user can view user activity.
     */
    public function viewUserActivity(User $user): bool
    {
        return $user->can('view activity logs');
    }
}
