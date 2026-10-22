<?php

namespace App\Policies;

use App\Models\User;
use Illuminate\Auth\Access\Response;

class AdminPolicy
{
    /**
     * Determine if the user can view the admin dashboard.
     */
    public function viewAdminDashboard(User $user): Response
    {
        return $user->isAdmin()
            ? Response::allow()
            : Response::deny('You do not have permission to access the admin dashboard.');
    }
}
