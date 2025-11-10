<?php

namespace App\Http\Controllers\Gateway\Stripe;

use App\Http\Controllers\Controller;
use App\Models\Payment;
use Illuminate\Support\Str;
use Stripe\Checkout\Session;
use Stripe\Stripe;

class ProcessController extends Controller
{
    /**
     * Process to stripe
     *
     * @return string
     */
    public static function process($paymentGateway, Payment $payment, ?array $info = null)
    {
        $config = json_decode($paymentGateway->config);

        Stripe::setApiKey($config->secret_key);

        $paymentToken = Str::uuid()->toString();

        $payment->update([
            'payment_token' => $paymentToken
        ]);

        $successUrl = $cancelUrl = null;

        if ($info) {
            if ($info['type'] == 'subscription') {
                $successUrl = route('subscription.payment.success', ['payment' => $payment, 'token' => $paymentToken]);
                $cancelUrl = route('subscription.payment.cancel', ['payment' => $payment, 'token' => $paymentToken]);
            } else {
                $successUrl = route('payment.success', $payment->id);
                $cancelUrl = route('payment.cancel', $payment->id);
            }
        } else {
            $successUrl = route('payment.success', $payment->id);
            $cancelUrl = route('payment.cancel', $payment->id);
        }

        $session = Session::create([
            'payment_method_types' => ['card'],
            'line_items' => [
                [
                    'price_data' => [
                        'currency' => 'usd',
                        'product_data' => [
                            'name' => 'PaymentID #'.str_pad($payment->id, 6, '0', STR_PAD_LEFT),
                            'metadata' => [
                                'order_ids' => $payment->orders?->pluck('id')->implode(',') ?? '',
                                'amount' => $payment->amount,
                                'total_orders' => $payment->orders?->count() ?? 1,
                            ],
                        ],
                        'unit_amount' => $payment->amount * 100, // Amount in cents
                    ],
                    'quantity' => 1,
                ],
            ],
            'mode' => 'payment',
            'success_url' => $successUrl,
            'cancel_url' => $cancelUrl,
        ]);

        return $session->url;
    }
}
