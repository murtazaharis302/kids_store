<?php

namespace Database\Seeders;

use App\Models\Customer;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Payment;
use App\Models\Product;
use App\Models\ProductVariant;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class OrderSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Don't duplicate if orders already exist
        if (Order::count() > 0) {
            return;
        }

        $customer = User::where('role', 'customer')->first() ?? User::first();
        $products = Product::with('variants')->take(5)->get();

        if (!$customer || $products->isEmpty()) {
            return;
        }

        $sampleOrders = [
            [
                'order_number' => 'AH-2026-1001',
                'order_status' => 'delivered',
                'payment_status' => 'paid',
                'payment_method' => 'card',
                'discount' => 100.00,
                'shipping_cost' => 150.00,
                'customer_notes' => 'Please deliver before 5 PM.',
                'created_at' => now()->subDays(5),
            ],
            [
                'order_number' => 'AH-2026-1002',
                'order_status' => 'shipped',
                'payment_status' => 'paid',
                'payment_method' => 'easypaisa',
                'discount' => 0.00,
                'shipping_cost' => 150.00,
                'customer_notes' => 'Call before arrival.',
                'created_at' => now()->subDays(3),
            ],
            [
                'order_number' => 'AH-2026-1003',
                'order_status' => 'processing',
                'payment_status' => 'paid',
                'payment_method' => 'bank_transfer',
                'discount' => 200.00,
                'shipping_cost' => 0.00,
                'customer_notes' => 'Gift wrap please.',
                'created_at' => now()->subDays(2),
            ],
            [
                'order_number' => 'AH-2026-1004',
                'order_status' => 'confirmed',
                'payment_status' => 'pending',
                'payment_method' => 'cod',
                'discount' => 0.00,
                'shipping_cost' => 200.00,
                'customer_notes' => null,
                'created_at' => now()->subDay(),
            ],
            [
                'order_number' => 'AH-2026-1005',
                'order_status' => 'pending',
                'payment_status' => 'pending',
                'payment_method' => 'cod',
                'discount' => 0.00,
                'shipping_cost' => 150.00,
                'customer_notes' => 'Handle with care.',
                'created_at' => now(),
            ],
            [
                'order_number' => 'AH-2026-1006',
                'order_status' => 'cancelled',
                'payment_status' => 'failed',
                'payment_method' => 'card',
                'discount' => 0.00,
                'shipping_cost' => 150.00,
                'customer_notes' => 'Customer requested cancellation.',
                'created_at' => now()->subHours(12),
            ],
        ];

        foreach ($sampleOrders as $oData) {
            $selectedProducts = $products->random(rand(1, min(3, $products->count())));
            $subtotal = 0;
            $itemsData = [];

            foreach ($selectedProducts as $product) {
                $variant = $product->variants->first();
                $quantity = rand(1, 2);
                $unitPrice = (float) ($product->sale_price ?: $product->price);
                $lineTotal = $unitPrice * $quantity;
                $subtotal += $lineTotal;

                $itemsData[] = [
                    'product_id' => $product->id,
                    'variant_id' => $variant ? $variant->id : null,
                    'product_name' => $product->name,
                    'sku' => $variant ? $variant->sku : $product->sku,
                    'size' => $variant && $variant->size ? $variant->size->name : '1-2Y',
                    'color' => $variant && $variant->color ? $variant->color->name : 'Red',
                    'quantity' => $quantity,
                    'unit_price' => $unitPrice,
                    'total' => $lineTotal,
                ];
            }

            $total = ($subtotal - $oData['discount']) + $oData['shipping_cost'];

            $order = Order::create([
                'user_id' => $customer->id,
                'order_number' => $oData['order_number'],
                'subtotal' => $subtotal,
                'discount' => $oData['discount'],
                'shipping_cost' => $oData['shipping_cost'],
                'total' => max(0, $total),
                'payment_method' => $oData['payment_method'],
                'payment_status' => $oData['payment_status'],
                'order_status' => $oData['order_status'],
                'customer_notes' => $oData['customer_notes'],
                'created_at' => $oData['created_at'],
                'updated_at' => $oData['created_at'],
            ]);

            foreach ($itemsData as $item) {
                $order->items()->create($item);
            }

            Payment::create([
                'order_id' => $order->id,
                'transaction_id' => $oData['payment_status'] === 'paid' ? 'TXN-' . strtoupper(Str::random(10)) : null,
                'method' => $oData['payment_method'],
                'amount' => $order->total,
                'status' => $oData['payment_status'],
                'paid_at' => $oData['payment_status'] === 'paid' ? $oData['created_at'] : null,
            ]);
        }
    }
}
