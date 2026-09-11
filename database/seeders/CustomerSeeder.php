<?php

namespace Database\Seeders;

use App\Models\Address;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Payment;
use App\Models\Product;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class CustomerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $demoCustomers = [
            [
                'name' => 'Sara Ahmed',
                'email' => 'sara.ahmed@example.com',
                'phone' => '03011122334',
                'status' => true,
            ],
            [
                'name' => 'Usman Malik',
                'email' => 'usman.malik@example.com',
                'phone' => '03022233445',
                'status' => true,
            ],
            [
                'name' => 'Fatima Noor',
                'email' => 'fatima.noor@example.com',
                'phone' => '03033344556',
                'status' => false, // Inactive demo customer
            ],
            [
                'name' => 'Zainab Bibi',
                'email' => 'zainab.bibi@example.com',
                'phone' => '03044455667',
                'status' => true,
            ],
        ];

        $product = Product::first();

        foreach ($demoCustomers as $cData) {
            $user = User::firstOrCreate(
                ['email' => $cData['email']],
                [
                    'name' => $cData['name'],
                    'phone' => $cData['phone'],
                    'password' => Hash::make('password'),
                    'role' => 'customer',
                    'status' => $cData['status'],
                    'email_verified_at' => now(),
                ]
            );

            // Ensure address exists for demo customer
            Address::firstOrCreate(
                ['user_id' => $user->id],
                [
                    'first_name' => explode(' ', $user->name)[0],
                    'last_name' => explode(' ', $user->name)[1] ?? 'Customer',
                    'phone' => $user->phone ?? '03000000000',
                    'address_line_1' => 'Street ' . rand(1, 20) . ', Block ' . chr(rand(65, 70)),
                    'address_line_2' => 'Phase ' . rand(1, 5),
                    'city' => 'Lahore',
                    'state' => 'Punjab',
                    'postal_code' => '54000',
                    'country' => 'Pakistan',
                    'is_default' => true,
                ]
            );

            // Create sample order for customer if they have no orders
            if ($product && $user->orders()->count() === 0) {
                $orderNum = 'AH-2026-CUST' . sprintf('%03d', $user->id);
                $unitPrice = (float) ($product->sale_price ?: $product->price);
                $total = $unitPrice * 2;

                $order = Order::create([
                    'user_id' => $user->id,
                    'order_number' => $orderNum,
                    'subtotal' => $total,
                    'discount' => 0.00,
                    'shipping_cost' => 150.00,
                    'total' => $total + 150.00,
                    'payment_method' => 'cod',
                    'payment_status' => 'paid',
                    'order_status' => 'delivered',
                    'customer_notes' => 'Customer seeder sample order.',
                    'created_at' => now()->subDays(rand(1, 10)),
                ]);

                OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $product->id,
                    'product_name' => $product->name,
                    'sku' => $product->sku,
                    'size' => '1-2Y',
                    'color' => 'Red',
                    'quantity' => 2,
                    'unit_price' => $unitPrice,
                    'total' => $total,
                ]);

                Payment::create([
                    'order_id' => $order->id,
                    'transaction_id' => 'TXN-' . strtoupper(Str::random(8)),
                    'method' => 'cod',
                    'amount' => $order->total,
                    'status' => 'paid',
                    'paid_at' => now(),
                ]);
            }
        }
    }
}
