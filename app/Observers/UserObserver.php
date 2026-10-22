<?php

namespace App\Observers;

use App\Models\User;
use Illuminate\Support\Str;

class UserObserver
{
    /**
     * Handle the User "creating" event.
     */
    public function creating(User $user): void
    {
        // Generate a unique username if not provided
        if (empty($user->username)) {
            $baseUsername = Str::slug($user->name);
            $username = $baseUsername;
            $count = 1;

            while (User::where('username', $username)->exists()) {
                $username = $baseUsername.$count++;
            }

            $user->username = $username;
        }

        // Set default status if not set
        if (empty($user->status)) {
            $user->status = 'active';
        }
    }

    /**
     * Handle the User "created" event.
     */
    public function created(User $user): void
    {
        // Set default role if not set
        if (empty($user->role)) {
            $user->role = 'customer';
            $user->save();
        }

        // Send welcome email
        // Mail::to($user)->queue(new WelcomeEmail($user));
    }

    /**
     * Handle the User "updating" event.
     */
    public function updating(User $user): void
    {
        // If email is being updated, reset email verification
        if ($user->isDirty('email')) {
            $user->email_verified_at = null;
            // $user->sendEmailVerificationNotification();
        }

        // If status is being updated to banned, revoke all tokens
        if ($user->isDirty('status') && $user->status === 'banned') {
            $user->tokens()->delete();
        }
    }

    /**
     * Handle the User "updated" event.
     */
    public function updated(User $user): void
    {
        // Clear any cached permissions
        $user->forgetCachedPermissions();
    }

    /**
     * Handle the User "deleting" event.
     */
    public function deleting(User $user): void
    {
        // Prevent deletion of the last admin
        if ($user->hasRole('Super Admin') && User::role('Super Admin')->count() <= 1) {
            throw new \RuntimeException('Cannot delete the last admin user.');
        }

        // Soft delete related data or transfer ownership
        // Example: $user->posts()->update(['user_id' => config('system.user_id')]);
    }

    /**
     * Handle the User "deleted" event.
     */
    public function deleted(User $user): void
    {
        // Revoke all tokens
        $user->tokens()->delete();

        // Clear any cached permissions
        $user->forgetCachedPermissions();
    }

    /**
     * Handle the User "restored" event.
     */
    public function restored(User $user): void
    {
        // Restore related data if needed
    }

    /**
     * Handle the User "force deleted" event.
     */
    public function forceDeleted(User $user): void
    {
        // Permanently delete related data if needed
    }
}
