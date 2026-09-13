<?php

namespace App\Services;

use App\Models\Order;
use App\Models\Payment;
use Illuminate\Support\Str;

class PaymentService
{
    /**
     * Get details for all configured advance payment methods.
     */
    public static function getAvailableMethods(): array
    {
        return [
            'jazzcash' => [
                'id' => 'jazzcash',
                'name' => 'JazzCash Mobile Wallet',
                'badge' => '⚡ 0% Fee • Instant Mobile Transfer',
                'icon' => 'smartphone',
                'account_title' => config('services.payment.jazzcash.title', 'Al Hayat Kids'),
                'account_number' => config('services.payment.jazzcash.number', '03249171213'),
                'instructions' => 'Transfer total order amount to our official JazzCash wallet account. Upload screenshot receipt or send via WhatsApp.',
            ],
            'easypaisa' => [
                'id' => 'easypaisa',
                'name' => 'EasyPaisa Mobile Wallet',
                'badge' => '⚡ 0% Fee • Instant Mobile Transfer',
                'icon' => 'wallet',
                'account_title' => config('services.payment.easypaisa.title', 'Al Hayat Kids'),
                'account_number' => config('services.payment.easypaisa.number', '03249171213'),
                'instructions' => 'Transfer total order amount to our official EasyPaisa wallet account. Upload screenshot receipt or send via WhatsApp.',
            ],
            'bank_transfer' => [
                'id' => 'bank_transfer',
                'name' => 'Direct Online Bank Transfer (IBFT)',
                'badge' => '⚡ 0% Fee • All Banking Apps Supported',
                'icon' => 'building-bank',
                'bank_name' => config('services.payment.bank.bank_name', 'Meezan Bank Limited'),
                'account_title' => config('services.payment.bank.title', 'Al Hayat Kids'),
                'account_number' => config('services.payment.bank.account_number', '03249171213'),
                'iban' => config('services.payment.bank.iban', 'PK36MEZN03249171213'),
                'instructions' => 'Transfer via Meezan, HBL, Allied, UBL or any banking app using IBAN/Account details. Upload screenshot receipt below.',
            ],
            'card' => [
                'id' => 'card',
                'name' => 'Credit / Debit Card (Visa / Mastercard)',
                'badge' => 'Instant Real-time Auto-Confirmation',
                'icon' => 'credit-card',
                'instructions' => 'Pay securely using Visa, Mastercard or UnionPay credit/debit card. Your payment will be verified instantly.',
            ],
        ];
    }

    /**
     * Attach & process advance payment for an Order.
     */
    public static function processPayment(Order $order, string $method, array $paymentData = []): Payment
    {
        $existingPayment = Payment::where('order_id', $order->id)->first();

        $referenceNumber = isset($paymentData['reference_number']) ? trim($paymentData['reference_number']) : null;
        $senderName = isset($paymentData['sender_name']) ? trim($paymentData['sender_name']) : null;
        $paymentNotes = isset($paymentData['payment_notes']) ? trim($paymentData['payment_notes']) : null;
        $proofImage = isset($paymentData['payment_proof_image']) ? $paymentData['payment_proof_image'] : null;

        $transactionId = $referenceNumber ? strtoupper($referenceNumber) : ($existingPayment ? $existingPayment->transaction_id : 'TRX-' . strtoupper(Str::random(8)));
        $status = 'pending_verification';
        $paidAt = null;

        $order->update([
            'payment_method' => $method,
            'payment_status' => 'pending_verification',
            'order_status' => 'pending',
        ]);

        $paymentPayload = [
            'order_id' => $order->id,
            'transaction_id' => $transactionId,
            'reference_number' => $referenceNumber ?: ($existingPayment ? $existingPayment->reference_number : $transactionId),
            'sender_name' => $senderName ?: ($existingPayment ? $existingPayment->sender_name : null),
            'payment_notes' => $paymentNotes ?: ($existingPayment ? $existingPayment->payment_notes : null),
            'payment_proof_image' => $proofImage ?: ($existingPayment ? $existingPayment->payment_proof_image : null),
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
                'transaction_id' => 'VERIFIED-' . strtoupper(Str::random(8)),
                'method' => $order->payment_method ?: 'jazzcash',
                'amount' => $order->total,
                'status' => 'paid',
                'paid_at' => now(),
                'payment_notes' => $adminNote ?: 'Verified and approved by store admin.',
            ]);
        } else {
            $payment->update([
                'status' => 'paid',
                'paid_at' => now(),
                'payment_notes' => $payment->payment_notes ? $payment->payment_notes . ' | Admin Approval: ' . $adminNote : 'Verified by admin: ' . $adminNote,
            ]);
        }

        $order->update([
            'payment_status' => 'paid',
            'order_status' => ($order->order_status === 'pending') ? 'processing' : $order->order_status,
        ]);

        return true;
    }
}
