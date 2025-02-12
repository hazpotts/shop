<?php

namespace Database\Factories;

use App\Models\Order;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class OrderFactory extends Factory
{
    protected $model = Order::class;

    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'status' => $this->faker->randomElement([
                Order::STATUS_PENDING,
                Order::STATUS_PROCESSING,
                Order::STATUS_COMPLETED
            ]),
            'total_amount' => $this->faker->randomFloat(2, 10, 1000),
            'shipping_address' => $this->faker->address(),
            'billing_address' => $this->faker->address(),
            'payment_status' => $this->faker->randomElement([
                Order::PAYMENT_STATUS_PENDING,
                Order::PAYMENT_STATUS_PAID,
                Order::PAYMENT_STATUS_FAILED
            ]),
            'payment_method' => $this->faker->randomElement(['credit_card', 'paypal']),
            'notes' => $this->faker->optional()->sentence()
        ];
    }
}
