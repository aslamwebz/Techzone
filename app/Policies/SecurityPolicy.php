<?php

namespace App\Policies;

use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class SecurityPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any security settings.
     *
     * @return mixed
     */
    public function viewAny(User $user)
    {
        return $user->isAdmin();
    }

    /**
     * Determine whether the user can enable two-factor authentication.
     *
     * @return mixed
     */
    public function enableTwoFactorAuth(User $user)
    {
        return $user->isAdmin() && config('security.2fa.enabled', true);
    }

    /**
     * Determine whether the user can manage user roles and permissions.
     *
     * @return mixed
     */
    public function manageRoles(User $user)
    {
        return $user->isAdmin();
    }

    /**
     * Determine whether the user can view audit logs.
     *
     * @return mixed
     */
    public function viewAuditLogs(User $user)
    {
        return $user->isAdmin();
    }

    /**
     * Determine whether the user can manage IP restrictions.
     *
     * @return mixed
     */
    public function manageIpRestrictions(User $user)
    {
        return $user->isAdmin();
    }

    /**
     * Determine whether the user can manage security policies.
     *
     * @return mixed
     */
    public function managePolicies(User $user)
    {
        return $user->isAdmin();
    }

    /**
     * Determine whether the user can perform security scans.
     *
     * @return mixed
     */
    public function performSecurityScans(User $user)
    {
        return $user->isAdmin();
    }

    /**
     * Determine whether the user can view security reports.
     *
     * @return mixed
     */
    public function viewSecurityReports(User $user)
    {
        return $user->isAdmin();
    }

    /**
     * Determine whether the user can manage API tokens.
     *
     * @return mixed
     */
    public function manageApiTokens(User $user)
    {
        return $user->isAdmin();
    }
}
