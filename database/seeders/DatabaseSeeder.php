<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Product;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Create admin user
        User::factory()->create([
            'name' => 'Admin User',
            'email' => 'admin@example.com',
            'password' => Hash::make('password'),
            'is_admin' => true,
        ]);

        // Create regular user
        User::factory()->create([
            'name' => 'Test User',
            'email' => 'user@example.com',
            'password' => Hash::make('password'),
            'is_admin' => false,
        ]);

        // Create categories
        $categories = Category::factory()
            ->count(5)
            ->active()
            ->create();

        // Create products for each category
        foreach ($categories as $category) {
            // Create 5-10 active products in stock for each category
            Product::factory()
                ->count(fake()->numberBetween(5, 10))
                ->active()
                ->inStock()
                ->for($category)
                ->create();

            // Create 1-3 out of stock products
            Product::factory()
                ->count(fake()->numberBetween(1, 3))
                ->active()
                ->outOfStock()
                ->for($category)
                ->create();

            // Create 2-4 low stock products
            Product::factory()
                ->count(fake()->numberBetween(2, 4))
                ->active()
                ->lowStock()
                ->for($category)
                ->create();
        }
    }
}
