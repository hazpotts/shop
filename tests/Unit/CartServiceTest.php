<?php

namespace Tests\Unit;

use App\Models\Product;
use App\Models\User;
use App\Services\CartService;
use function Pest\Laravel\actingAs;

beforeEach(function () {
    $this->user = User::factory()->create();
    actingAs($this->user);
    $this->service = app(CartService::class);
});

test('cart starts empty', function () {
    expect($this->service->getCart())->toBeEmpty()
        ->and($this->service->getTotal())->toBe(0.0);
});

test('can add item to cart', function () {
    $product = Product::factory()->create(['price' => 10.00]);
    
    $this->service->addToCart($product, 2);
    
    $cart = $this->service->getCart();
    expect($cart)->toHaveCount(1)
        ->and($cart[0])->toMatchArray([
            'id' => $product->id,
            'name' => $product->name,
            'price' => 10.00,
            'quantity' => 2
        ])
        ->and($this->service->getTotal())->toBe(20.00);
});

test('adding same product increases quantity', function () {
    $product = Product::factory()->create(['price' => 10.00]);
    
    $this->service->addToCart($product, 1);
    $this->service->addToCart($product, 2);
    
    expect($this->service->getCart()[0]['quantity'])->toBe(3);
});

test('can update quantity', function () {
    $product = Product::factory()->create();
    $this->service->addToCart($product, 1);
    
    $this->service->updateQuantity($product->id, 5);
    
    expect($this->service->getCart()[0]['quantity'])->toBe(5);
});

test('updating quantity to zero removes item', function () {
    $product = Product::factory()->create();
    $this->service->addToCart($product, 1);
    
    $this->service->updateQuantity($product->id, 0);
    
    expect($this->service->getCart())->toBeEmpty();
});

test('can remove item from cart', function () {
    $product = Product::factory()->create();
    $this->service->addToCart($product, 1);
    
    $this->service->removeFromCart($product->id);
    
    expect($this->service->getCart())->toBeEmpty();
});

test('can clear cart', function () {
    $products = Product::factory(3)->create();
    foreach ($products as $product) {
        $this->service->addToCart($product, 1);
    }
    
    $this->service->clearCart();
    
    expect($this->service->getCart())->toBeEmpty()
        ->and($this->service->getTotal())->toBe(0.0);
});

test('can calculate cart total', function () {
    $product1 = Product::factory()->create(['price' => 10.00]);
    $product2 = Product::factory()->create(['price' => 20.00]);
    
    $this->service->addToCart($product1, 2);
    $this->service->addToCart($product2, 1);
    
    expect($this->service->getTotal())->toBe(40.00);
});
