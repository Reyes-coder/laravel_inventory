<?php

namespace App\Policies;

use App\Models\Producto;
use App\Models\User;

class ProductoPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return true;
    }

    /**
     * Determine whether the user can view the model.
     * - Admin can view all products
     * - User can view only their own products
     */
    public function view(User $user, Producto $producto): bool
    {
        return $user->isAdmin() || $user->id === $producto->user_id;
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return true;
    }

    /**
     * Determine whether the user can update the model.
     * - Admin can update any product
     * - User can update only their own products
     */
    public function update(User $user, Producto $producto): bool
    {
        return $user->isAdmin() || $user->id === $producto->user_id;
    }

    /**
     * Determine whether the user can delete the model.
     * - Admin can delete any product
     * - User can delete only their own products
     */
    public function delete(User $user, Producto $producto): bool
    {
        return $user->isAdmin() || $user->id === $producto->user_id;
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Producto $producto): bool
    {
        return $user->isAdmin();
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Producto $producto): bool
    {
        return $user->isAdmin();
    }
}
