<?php

namespace App\Policies;

use App\Models\Product;
use App\Models\User;

class ProductPolicy
{
    public function view(User $user, Product $product): bool
    {
        return true; // All authenticated users can view
    }

    public function create(User $user): bool
    {
        return true; // All authenticated users can create
    }

    public function update(User $user, Product $product): bool
    {
        return $user->id === $product->created_by || $user->isAdmin();
    }

    public function delete(User $user, Product $product): bool
    {
        return $user->id === $product->created_by || $user->isAdmin();
    }
}
