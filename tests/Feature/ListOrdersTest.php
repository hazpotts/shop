<?php

namespace Tests\Feature;

use App\Livewire\ListOrders;
use App\Models\Order;
use App\Models\User;
use function Pest\Laravel\{actingAs, get};
use function Pest\Livewire\livewire;

beforeEach(function () {
    $this->user = User::factory()->create();
    actingAs($this->user);
});

test('can view orders page', function () {
    get('/dashboard')->assertOk();
});

test('can list orders', function () {
    $orders = Order::factory(3)->create([
        'user_id' => $this->user->id
    ]);

    livewire(ListOrders::class)
        ->assertSee($orders[0]->id)
        ->assertSee($orders[0]->total_amount)
        ->assertSee($orders[0]->status)
        ->assertSee($orders[0]->payment_status);
});

test('only shows user orders', function () {
    $otherUser = User::factory()->create();
    $otherOrders = Order::factory(3)->create([
        'user_id' => $otherUser->id
    ]);
    $userOrders = Order::factory(2)->create([
        'user_id' => $this->user->id
    ]);

    livewire(ListOrders::class)
        ->assertSee($userOrders[0]->id)
        ->assertDontSee($otherOrders[0]->id);
});
