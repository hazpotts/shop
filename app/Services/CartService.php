<?php

namespace App\Services;

use App\Models\CartItem;
use App\Models\Product;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class CartService
{
    public function getCart(): Collection
    {
        return CartItem::query()
            ->with('product')
            ->where('user_id', auth()->id())
            ->get()
            ->map(function ($item) {
                return [
                    'id' => $item->product_id,
                    'name' => $item->product->name,
                    'price' => $item->product->price,
                    'quantity' => $item->quantity
                ];
            });
    }

    public function addToCart(Product $product, int $quantity = 1): void
    {
        $cartItem = CartItem::where('user_id', auth()->id())
            ->where('product_id', $product->id)
            ->first();

        if ($cartItem) {
            $cartItem->increment('quantity', $quantity);
        } else {
            CartItem::create([
                'user_id' => auth()->id(),
                'product_id' => $product->id,
                'quantity' => $quantity
            ]);
        }
    }

    public function updateQuantity(int $productId, int $quantity): void
    {
        if ($quantity <= 0) {
            CartItem::query()
                ->where('user_id', auth()->id())
                ->where('product_id', $productId)
                ->delete();
        } else {
            CartItem::where('user_id', auth()->id())
                ->where('product_id', $productId)
                ->update(['quantity' => $quantity]);
        }
    }

    public function removeFromCart(int $productId): void
    {
        CartItem::query()
            ->where('user_id', auth()->id())
            ->where('product_id', $productId)
            ->delete();
    }

    public function clearCart(): void
    {
        CartItem::query()
            ->where('user_id', auth()->id())
            ->delete();
    }

    public function getTotal(): float
    {
        return CartItem::query()
            ->where('user_id', auth()->id())
            ->join('products', 'cart_items.product_id', '=', 'products.id')
            ->selectRaw('SUM(products.price * cart_items.quantity) as total')
            ->value('total') ?? 0.0;
    }
}
