<?php

namespace Tests\Feature\Api;

use App\Models\Product;
use App\Models\User;
use function Pest\Laravel\{actingAs, getJson, postJson};

beforeEach(function () {
    $this->user = User::factory()->create();
    $this->token = $this->user->createToken('test-token')->plainTextToken;
});

test('unauthenticated users cannot access cart', function () {
    getJson('/api/cart')->assertUnauthorized();
});

test('can view empty cart', function () {
    getJson('/api/cart', [
        'Authorization' => 'Bearer ' . $this->token
    ])->assertOk()
        ->assertJson([]);
});

test('can add product to cart', function () {
    $product = Product::factory()->create(['price' => 10.00]);
    
    postJson("/api/cart/products/{$product->id}", [
        'quantity' => 2
    ], [
        'Authorization' => 'Bearer ' . $this->token
    ])->assertOk();
    
    $response = getJson('/api/cart', [
        'Authorization' => 'Bearer ' . $this->token
    ])->assertOk();

    expect($response->json())->toHaveCount(1)
        ->and($response->json('0'))->toMatchArray([
            'id' => $product->id,
            'name' => $product->name,
            'price' => 10.00,
            'quantity' => 2
        ]);
});
