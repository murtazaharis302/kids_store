<?php

namespace App\Services;

use App\Models\Cart;
use App\Models\CartItem;
use App\Models\Product;
use App\Models\ProductVariant;

class CartService
{
    public static function getCart()
    {
        if (auth()->check()) {
            $userCart = Cart::firstOrCreate(['user_id' => auth()->id()]);

            // Check if a guest cart exists for the current session
            $sessionId = session()->getId();
            $guestCart = Cart::where('session_id', $sessionId)
                ->whereNull('user_id')
                ->where('id', '!=', $userCart->id)
                ->first();

            if ($guestCart) {
                static::mergeCarts($guestCart, $userCart);
            }

            return $userCart;
        }

        $sessionId = session()->getId();
        return Cart::firstOrCreate(['session_id' => $sessionId, 'user_id' => null]);
    }

    public static function mergeCarts(Cart $guestCart, Cart $userCart)
    {
        $guestItems = $guestCart->items()->with(['product', 'variant'])->get();

        foreach ($guestItems as $gItem) {
            // Check if product and variant (if present) are active
            if (!$gItem->product || !$gItem->product->status) {
                continue;
            }
            if ($gItem->variant_id && (!$gItem->variant || !$gItem->variant->status)) {
                continue;
            }

            // Available stock
            $stock = $gItem->variant ? $gItem->variant->stock_quantity : 0;
            if ($stock <= 0) {
                continue;
            }

            // Find matching item in user cart
            $existingItem = $userCart->items()
                ->where('product_id', $gItem->product_id)
                ->where('variant_id', $gItem->variant_id)
                ->first();

            $price = static::getEffectivePrice($gItem->variant, $gItem->product);

            if ($existingItem) {
                $combinedQty = min($existingItem->quantity + $gItem->quantity, $stock);
                $existingItem->update([
                    'quantity' => $combinedQty,
                    'price' => $price,
                ]);
            } else {
                $qty = min($gItem->quantity, $stock);
                $userCart->items()->create([
                    'product_id' => $gItem->product_id,
                    'variant_id' => $gItem->variant_id,
                    'quantity' => $qty,
                    'price' => $price,
                ]);
            }
        }

        // Delete guest cart to ensure merge is idempotent and guest cart is cleaned up
        $guestCart->items()->delete();
        $guestCart->delete();
    }

    public static function getEffectivePrice(?ProductVariant $variant, Product $product)
    {
        if ($variant) {
            $regPrice = (float) ($variant->price ?: $product->price);
            $salePrice = !is_null($variant->sale_price) ? (float) $variant->sale_price : (!is_null($product->sale_price) ? (float) $product->sale_price : null);
            if (!is_null($salePrice) && $salePrice < $regPrice) {
                return $salePrice;
            }
            return $regPrice;
        }

        $regPrice = (float) $product->price;
        $salePrice = !is_null($product->sale_price) ? (float) $product->sale_price : null;
        if (!is_null($salePrice) && $salePrice < $regPrice) {
            return $salePrice;
        }
        return $regPrice;
    }

    /**
     * Get reserved quantity for a specific product & variant across active carts
     * within the last 10 minutes (excluding a specific cart ID if provided).
     */
    public static function getReservedStock(?int $productId, ?int $variantId, ?int $excludeCartId = null): int
    {
        $cutoff = now()->subMinutes(10);

        $query = CartItem::whereHas('cart', function ($q) use ($excludeCartId) {
            if ($excludeCartId) {
                $q->where('id', '!=', $excludeCartId);
            }
        })
        ->where('updated_at', '>=', $cutoff);

        if ($variantId) {
            $query->where('variant_id', $variantId);
        } else {
            $query->where('product_id', $productId)->whereNull('variant_id');
        }

        return (int) $query->sum('quantity');
    }

    /**
     * Get available stock considering 10-minute temporary reservations by other active carts.
     */
    public static function getAvailableStock(?ProductVariant $variant, Product $product, ?int $excludeCartId = null): int
    {
        $physicalStock = $variant ? (int) $variant->stock_quantity : 50;
        $productId = $product->id;
        $variantId = $variant ? $variant->id : null;

        $reserved = static::getReservedStock($productId, $variantId, $excludeCartId);

        return max(0, $physicalStock - $reserved);
    }

    /**
     * Get remaining seconds on current user's 10-minute cart reservation window.
     */
    public static function getCartReservationRemainingSeconds(): int
    {
        $cart = static::getCart();
        $latestItem = $cart->items()->orderBy('updated_at', 'desc')->first();
        if (!$latestItem) {
            return 0;
        }

        $elapsedSeconds = now()->diffInSeconds($latestItem->updated_at);
        $totalReservationSeconds = 10 * 60; // 600 seconds
        return max(0, $totalReservationSeconds - $elapsedSeconds);
    }

    public static function addToCart(int $productId, ?int $variantId, int $quantity)
    {
        // 1. Freshly load Product and Variant from database
        $product = Product::find($productId);
        if (!$product || !$product->status) {
            return ['success' => false, 'message' => 'Product is currently unavailable.'];
        }

        $variant = null;
        if ($variantId) {
            $variant = ProductVariant::where('id', $variantId)
                ->where('product_id', $productId)
                ->where('status', true)
                ->first();
            if (!$variant) {
                return ['success' => false, 'message' => 'Selected variant option is invalid or unavailable.'];
            }
        } elseif ($product->variants()->where('status', true)->exists()) {
            return ['success' => false, 'message' => 'Please select a valid variant combination.'];
        }

        $cart = static::getCart();
        $availableStock = static::getAvailableStock($variant, $product, $cart->id);
        if ($availableStock <= 0) {
            return ['success' => false, 'message' => 'Selected option is currently out of stock or reserved by another customer.'];
        }

        $existingItem = $cart->items()
            ->where('product_id', $productId)
            ->where('variant_id', $variantId)
            ->first();

        $existingQty = $existingItem ? $existingItem->quantity : 0;
        $newTotalQty = $existingQty + $quantity;

        if ($newTotalQty > $availableStock) {
            return [
                'success' => false,
                'message' => "Only {$availableStock} items are currently available.",
            ];
        }

        $effectivePrice = static::getEffectivePrice($variant, $product);

        if ($existingItem) {
            $existingItem->update([
                'quantity' => $newTotalQty,
                'price' => $effectivePrice,
                'updated_at' => now(),
            ]);
        } else {
            $cart->items()->create([
                'product_id' => $productId,
                'variant_id' => $variantId,
                'quantity' => $quantity,
                'price' => $effectivePrice,
                'updated_at' => now(),
            ]);
        }
        $cart->touch();

        return [
            'success' => true,
            'message' => 'Added to cart. Items reserved for 10 minutes.',
            'count' => static::getCartCount(),
        ];
    }

    public static function updateQuantity(int $itemId, int $quantity)
    {
        $cart = static::getCart();
        $item = $cart->items()->with(['product', 'variant'])->find($itemId);

        if (!$item) {
            return ['success' => false, 'message' => 'Cart item not found.'];
        }

        if ($quantity < 1) {
            return ['success' => false, 'message' => 'Quantity must be at least 1.'];
        }

        $availableStock = static::getAvailableStock($item->variant, $item->product, $cart->id);

        if ($availableStock <= 0) {
            $item->delete();
            return ['success' => false, 'message' => 'Item is out of stock and was removed from cart.'];
        }

        // Clamp quantity if stock changed
        $targetQty = min($quantity, $availableStock);
        $price = static::getEffectivePrice($item->variant, $item->product);

        $item->update([
            'quantity' => $targetQty,
            'price' => $price,
            'updated_at' => now(),
        ]);
        $cart->touch();

        $message = ($targetQty < $quantity) 
            ? "Quantity adjusted to available stock ({$targetQty})." 
            : 'Cart updated. Reservation extended for 10 minutes.';

        return [
            'success' => true,
            'message' => $message,
            'count' => static::getCartCount(),
        ];
    }

    public static function removeItem(int $itemId)
    {
        $cart = static::getCart();
        $item = $cart->items()->find($itemId);

        if (!$item) {
            return ['success' => false, 'message' => 'Cart item not found.'];
        }

        $item->delete();
        $cart->touch();

        return [
            'success' => true,
            'message' => 'Item removed from cart.',
            'count' => static::getCartCount(),
        ];
    }

    public static function getCartCount()
    {
        $cart = static::getCart();
        return (int) $cart->items()->sum('quantity');
    }

    public static function getSubtotal()
    {
        $cart = static::getCart();
        $items = $cart->items()->with(['product', 'variant'])->get();

        $subtotal = 0.0;
        foreach ($items as $item) {
            if ($item->product && $item->product->status && ($item->variant_id === null || ($item->variant && $item->variant->status))) {
                $subtotal += (float) $item->price * $item->quantity;
            }
        }

        return $subtotal;
    }
}
