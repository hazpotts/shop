<?php

namespace Tests\Feature\Api;

use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use function Pest\Laravel\{actingAs, getJson, postJson};

beforeEach(function () {
    $this->user = User::factory()->create();
    $this->token = $this->user->createToken('test-token')->plainTextToken;
});

test('unauthenticated users cannot access orders', function () {
    getJson('/api/orders')->assertUnauthorized();
});

test('can view empty orders list', function () {
    getJson('/api/orders', [
        'Authorization' => 'Bearer ' . $this->token
    ])->assertOk()
        ->assertJsonStructure([
            'current_page',
            'data',
            'first_page_url',
            'from',
            'last_page',
            'last_page_url',
            'links' => [
                '*' => [
                    'url',
                    'label',
                    'active'
                ]
            ],
            'next_page_url',
            'path',
            'per_page',
            'prev_page_url',
            'to',
            'total'
        ])
        ->assertJsonPath('total', 0)
        ->assertJsonPath('data', []);
});

test('can list user orders', function () {
    $orders = Order::factory(3)->create([
        'user_id' => $this->user->id
    ]);

    $response = getJson('/api/orders', [
        'Authorization' => 'Bearer ' . $this->token
    ])->assertOk()
        ->assertJsonStructure([
            'current_page',
            'data' => [
                '*' => [
                    'id',
                    'user_id',
                    'total_amount',
                    'status',
                    'payment_status',
                    'created_at'
                ]
            ],
            'first_page_url',
            'from',
            'last_page',
            'last_page_url',
            'links',
            'next_page_url',
            'path',
            'per_page',
            'prev_page_url',
            'to',
            'total'
        ]);

    expect($response->json('total'))->toBe(3)
        ->and($response->json('data'))->toHaveCount(3)
        ->and($response->json('per_page'))->toBe(10)
        ->and($response->json('current_page'))->toBe(1);
});

test('cannot view other users orders', function () {
    $otherUser = User::factory()->create();
    Order::factory(3)->create([
        'user_id' => $otherUser->id
    ]);

    getJson('/api/orders', [
        'Authorization' => 'Bearer ' . $this->token
    ])->assertOk()
        ->assertJsonPath('total', 0)
        ->assertJsonPath('data', []);
});

test('can place order', function () {
    $product = Product::factory()->create([
        'price' => 10.00,
        'stock' => 5
    ]);

    // Add product to cart
    postJson("/api/cart/products/{$product->id}", [
        'quantity' => 2
    ], [
        'Authorization' => 'Bearer ' . $this->token
    ])->assertOk();

    // Place order
    $response = postJson('/api/orders', [
        'shipping_address' => '123 Test St',
        'billing_address' => '123 Test St',
        'payment_method' => 'credit_card'
    ], [
        'Authorization' => 'Bearer ' . $this->token
    ])->assertCreated();

    expect($response->json())
        ->toHaveKey('id')
        ->toHaveKey('user_id')
        ->toHaveKey('total_amount')
        ->and($response->json('total_amount'))->toBe('20.00')
        ->and($response->json('status'))->toBe('pending')
        ->and($response->json('payment_status'))->toBe('pending');

    // Check product stock was decremented
    $product->refresh();
    expect($product->stock)->toBe(3);
});
