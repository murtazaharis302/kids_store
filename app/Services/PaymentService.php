<?php

namespace App\Services;

use App\Models\Order;
use App\Models\Payment;
use Illuminate\Support\Str;
use Stripe\Stripe;
use Stripe\Checkout\Session as StripeSession;

class PaymentService
{
    /**
     * Get details for all configured payment methods.
     */
    public static function getAvailableMethods(): array
    {
        return [
            'jazzcash' => [
                'id' => 'jazzcash',
                'name' => 'JazzCash Mobile Wallet',
                'badge' => '0% Fee • Instant Mobile Transfer',
                'icon' => 'smartphone',
                'account_title' => config('services.payment.jazzcash.title', 'AH Kids Store'),
                'account_number' => config('services.payment.jazzcash.number', '03001234567'),
                'instructions' => 'Send total order amount to our JazzCash wallet account. Enter your 12-digit TRX ID below after sending.',
            ],
            'easypaisa' => [
                'id' => 'easypaisa',
                'name' => 'EasyPaisa Mobile Wallet',
                'badge' => '0% Fee • Instant Mobile Transfer',
                'icon' => 'wallet',
                'account_title' => config('services.payment.easypaisa.title', 'AH Kids Store'),
                'account_number' => config('services.payment.easypaisa.number', '03007654321'),
                'instructions' => 'Send total order amount to our EasyPaisa wallet account. Enter your 12-digit TRX ID below after sending.',
            ],
            'bank_transfer' => [
                'id' => 'bank_transfer',
                'name' => 'Direct Online Bank Transfer (IBFT)',
                'badge' => '0% Fee • All Banking Apps Supported',
                'icon' => 'building-bank',
                'bank_name' => config('services.payment.bank.bank_name', 'Meezan Bank Limited'),
                'account_title' => config('services.payment.bank.title', 'AH Kids Store'),
                'account_number' => config('services.payment.bank.account_number', '01020304050607'),
                'iban' => config('services.payment.bank.iban', 'PK36MEZN0001020304050607'),
                'instructions' => 'Transfer via Meezan, HBL, Allied, UBL or any banking app using IBAN/Account details. Enter Transaction Reference / TRX ID below.',
            ],
            'card' => [
                'id' => 'card',
                'name' => 'Credit / Debit Card (Stripe Real-Time)',
                'badge' => 'Instant Real-time Auto-Confirmation',
                'icon' => 'credit-card',
                'instructions' => 'Pay securely using Visa, Mastercard or UnionPay credit/debit card. Your payment will be verified instantly in real-time.',
            ],
            'cod' => [
                'id' => 'cod',
                'name' => 'Cash on Delivery (COD)',
                'badge' => 'Pay upon Parcel Delivery',
                'icon' => 'truck-delivery',
                'instructions' => 'Pay in cash when your parcel arrives at your doorstep.',
            ],
        ];
    }

    /**
     * Attach & process payment for an Order.
     */
    public static function processPayment(Order $order, string $method, array $paymentData = []): Payment
    {
        $existingPayment = Payment::where('order_id', $order->id)->first();

        $referenceNumber = isset($paymentData['reference_number']) ? trim($paymentData['reference_number']) : null;
        $senderName = isset($paymentData['sender_name']) ? trim($paymentData['sender_name']) : null;
        $paymentNotes = isset($paymentData['payment_notes']) ? trim($paymentData['payment_notes']) : null;

        $transactionId = null;
        $status = 'pending';
        $paidAt = null;

        if (in_array($method, ['jazzcash', 'easypaisa', 'bank_transfer'])) {
            $transactionId = $referenceNumber ? strtoupper($referenceNumber) : 'TRX-' . strtoupper(Str::random(8));
            $status = 'pending_verification';
            $order->update([
                'payment_method' => $method,
                'payment_status' => 'pending_verification',
                'order_status' => 'pending',
            ]);
        } elseif ($method === 'card') {
            $stripeSecret = config('services.payment.stripe.secret');

            if ($stripeSecret) {
                // Production Stripe Session
                Stripe::setApiKey($stripeSecret);
                $session = StripeSession::create([
                    'payment_method_types' => ['card'],
                    'line_items' => [[
                        'price_data' => [
                            'currency' => strtolower(config('services.payment.stripe.currency', 'pkr')),
                            'product_data' => [
                                'name' => 'Order #' . $order->order_number,
                            ],
                            'unit_amount' => (int) round($order->total * 100),
                        ],
                        'quantity' => 1,
                    ]],
                    'mode' => 'payment',
                    'success_url' => route('orders.show', $order->id) . '?payment=success&session_id={CHECKOUT_SESSION_ID}',
                    'cancel_url' => route('orders.show', $order->id) . '?payment=cancel',
                ]);

                $transactionId = $session->id;
                $status = 'pending';
            } else {
                // Real-time simulated Card Payment in Sandbox mode
                $transactionId = 'STRIPE-PAY-' . strtoupper(Str::random(10));
                $status = 'paid';
                $paidAt = now();

                $order->update([
                    'payment_method' => 'card',
                    'payment_status' => 'paid',
                    'order_status' => 'processing',
                ]);
            }
        } else { // COD
            $transactionId = 'COD-' . strtoupper(Str::random(6));
            $status = 'pending';

            $order->update([
                'payment_method' => 'cod',
                'payment_status' => 'pending',
                'order_status' => 'pending',
            ]);
        }

        $paymentPayload = [
            'order_id' => $order->id,
            'transaction_id' => $transactionId,
            'reference_number' => $referenceNumber ?: $transactionId,
            'sender_name' => $senderName,
            'payment_notes' => $paymentNotes,
            'method' => $method,
            'amount' => $order->total,
            'status' => $status,
            'paid_at' => $paidAt,
        ];

        if ($existingPayment) {
            $existingPayment->update($paymentPayload);
            return $existingPayment;
        }

        return Payment::create($paymentPayload);
    }

    /**
     * Mark an order payment as fully verified and received (Admin function).
     */
    public static function markAsReceived(Order $order, ?string $adminNote = null): bool
    {
        $payment = $order->payment;

        if (!$payment) {
            $payment = Payment::create([
                'order_id' => $order->id,
                'transaction_id' => 'MANUAL-' . strtoupper(Str::random(8)),
                'method' => $order->payment_method ?: 'manual',
                'amount' => $order->total,
                'status' => 'paid',
                'paid_at' => now(),
                'payment_notes' => $adminNote ?: 'Verified and approved by store admin.',
            ]);
        } else {
            $payment->update([
                'status' => 'paid',
                'paid_at' => now(),
                'payment_notes' => $payment->payment_notes ? $payment->payment_notes . ' | Admin Note: ' . $adminNote : 'Verified by admin: ' . $adminNote,
            ]);
        }

        $order->update([
            'payment_status' => 'paid',
            'order_status' => ($order->order_status === 'pending') ? 'processing' : $order->order_status,
        ]);

        return true;
    }
}
