<?php

namespace App\Policies;

use App\Models\Category;
use App\Models\User;

class CategoryPolicy
{
    public function viewAny(User $user): bool
    {
        return true; // Anyone can view categories
    }

    public function view(User $user, Category $category): bool
    {
        return true; // Anyone can view a category
    }

    public function create(User $user): bool
    {
        return $user->is_admin; // Only admins can create categories
    }

    public function update(User $user, Category $category): bool
    {
        return $user->is_admin; // Only admins can update categories
    }

    public function delete(User $user, Category $category): bool
    {
        return $user->is_admin; // Only admins can delete categories
    }
}
