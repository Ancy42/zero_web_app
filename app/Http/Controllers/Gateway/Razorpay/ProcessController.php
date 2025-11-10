<?php

namespace App\Http\Controllers\Gateway\Razorpay;

use App\Http\Controllers\Controller;
use App\Models\Payment;
use Illuminate\Support\Str;
use Razorpay\Api\Api;

class ProcessController extends Controller
{
    /**
     * Process to Razorpay
     *
     * @return string
     */
    public static function process($paymentGateway, Payment $payment, ?array $info = null)
    {
        $config = json_decode($paymentGateway->config);

        $razorpay = new Api($config->key, $config->secret);

        $amount = (float) $payment->amount;
        $currency = 'INR';
        $receipt = 'payment_receipt_'.$payment->id;

        $paymentToken = Str::uuid()->toString();

        $payment->update([
            'payment_token' => $paymentToken
        ]);

        $successUrl = $cancelUrl = null;

        if ($info) {
            $email = $info['email'] ?? 'example@gmail.com';
            $phone = $info['phone'] ?? '01870******';
            $name = $info['name'] ?? 'Example Name';
            $description = $info['description'];

            if ($info['type'] == 'subscription') {
                $successUrl = route('subscription.payment.success', ['payment' => $payment, 'token' => $paymentToken]);
                $cancelUrl = route('subscription.payment.cancel', ['payment' => $payment, 'token' => $paymentToken]);
            } else {
                $successUrl = route('payment.success', $payment->id);
                $cancelUrl = route('payment.cancel', $payment->id);
            }
        } else {
            $name = $payment->orders[0]->customer?->user?->name ?? '';
            $email = $payment->orders[0]->customer?->user?->email ?? '';
            $phone = $payment->orders[0]->customer?->user?->phone ?? '';
            $description = 'Total order '.$payment->orders->count().' total amount '.$payment->amount.'INR';
            $successUrl = route('payment.success', $payment->id);
            $cancelUrl = route('payment.cancel', $payment->id);
        }

        try {

            $paymentLink = $razorpay->invoice->create([
                'type' => 'link',
                'amount' => $amount * 100, // amount in paisa
                'currency' => $currency,
                'description' => $description,
                'customer' => [
                    'name' => $name,
                    'email' => $email,
                    'contact' => $phone,
                ],
                'callback_url' => $successUrl,
                'redirect' => true,
                'callback_method' => 'get',
                'cancel_url' => $cancelUrl,
            ]);

            return $paymentLink['short_url'];

        } catch (\Throwable $th) {
            return json_encode(['error' => $th->getMessage()]);
        }
    }
}
