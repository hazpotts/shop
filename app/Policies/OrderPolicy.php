<?php

namespace App\Policies;

use App\Models\Order;
use App\Models\User;

class OrderPolicy
{
    public function viewAny(User $user): bool
    {
        return true; // Users can view their own orders, admins can view all
    }

    public function view(User $user, Order $order): bool
    {
        return $user->id === $order->user_id || $user->is_admin;
    }

    public function create(User $user): bool
    {
        return true; // Any authenticated user can create orders
    }

    public function update(User $user, Order $order): bool
    {
        return $user->is_admin; // Only admins can update orders
    }

    public function delete(User $user, Order $order): bool
    {
        return $user->is_admin; // Only admins can delete orders
    }
}
