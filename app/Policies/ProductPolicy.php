<?php

namespace App\Policies;

use App\Models\Product;
use App\Models\User;

class ProductPolicy
{
    public function viewAny(User $user): bool
    {
        return true; // Anyone can view products
    }

    public function view(User $user, Product $product): bool
    {
        return true; // Anyone can view a product
    }

    public function create(User $user): bool
    {
        return $user->is_admin; // Only admins can create products
    }

    public function update(User $user, Product $product): bool
    {
        return $user->is_admin; // Only admins can update products
    }

    public function delete(User $user, Product $product): bool
    {
        return $user->is_admin; // Only admins can delete products
    }
}
