<?php

namespace App\Http\Controllers\Gateway\PayStack;

use App\Http\Controllers\Controller;
use App\Models\Payment;
use Illuminate\Support\Str;
use Yabacon\Paystack;

class ProcessController extends Controller
{
    /**
     * Process to Paystack
     *
     * @return string
     */
    public static function process($paymentGateway, Payment $payment, ?array $info = null)
    {
        $config = json_decode($paymentGateway->config);
        $paystack = new Paystack($config->secret_key);

        $paymentToken = Str::uuid()->toString();

        $payment->update([
            'payment_token' => $paymentToken
        ]);

        $successUrl = $cancelUrl = null;
        $value = null;

        if ($info) {
            if ($info['type'] == 'subscription') {
                $successUrl = route('subscription.payment.success', ['payment' => $payment, 'token' => $paymentToken]);
                $cancelUrl = route('subscription.payment.cancel', ['payment' => $payment, 'token' => $paymentToken]);
                $value = $info['description'];
            } else {
                $successUrl = route('payment.success', $payment->id);
                $cancelUrl = route('payment.cancel', $payment->id);
            }
        } else {
            $successUrl = route('payment.success', $payment->id);
            $cancelUrl = route('payment.cancel', $payment->id);
            $value = 'Total Orders ('.$payment->orders->count().')';
        }

        try {
            // Initialize transaction with success and cancel URLs
            $transaction = $paystack->transaction->initialize([
                'amount' => $payment->amount * 100,  // Amount in kobo (e.g., 10000 for ₦100.00)
                'email' => 'customer@example.com',
                'callback_url' => $successUrl,
                'cancel_url' => $cancelUrl,
                'metadata' => [
                    'custom_fields' => [
                        [
                            'display_name' => 'Customer Name',
                            'variable_name' => 'customer_name',
                            'value' => $value,
                        ],
                    ],
                ],
                // 'currency' => 'USD'
            ]);

            // Redirect user to payment page
            return $transaction->data->authorization_url;
        } catch (\Throwable $th) {
            return $th->getMessage();
        }
    }
}
