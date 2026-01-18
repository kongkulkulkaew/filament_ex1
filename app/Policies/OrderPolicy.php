<?php

namespace App\Policies;

use App\Models\Order;
use App\Models\User;

class OrderPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return true; // All authenticated users can view orders
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Order $order): bool
    {
        return true; // All authenticated users can view orders
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return true; // All authenticated users can create orders
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Order $order): bool
    {
        return true; // All authenticated users can update orders
    }

    /**
     * Determine whether the user can delete the model.
     * Only admins can delete orders
     */
    public function delete(User $user, Order $order): bool
    {
        // Check if user is admin
        // You can customize this based on your user model structure
        // For example: return $user->is_admin === true;
        // Or use a role system: return $user->hasRole('admin');
        return $user->email === 'admin@example.com' || $user->is_admin ?? false;
    }

    /**
     * Determine whether the user can delete any models.
     * Only admins can bulk delete orders
     */
    public function deleteAny(User $user): bool
    {
        return $user->email === 'admin@example.com' || $user->is_admin ?? false;
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Order $order): bool
    {
        return false; // Not implemented
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Order $order): bool
    {
        return false; // Not implemented
    }
}
