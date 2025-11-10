<?php

namespace App\Http\Controllers\Gateway\PayTabs;

use App\Http\Controllers\Controller;
use App\Models\Payment;
use App\Models\PaymentGateway;
use Google\Rpc\Context\AttributeContext\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;

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

        if ($info && $info['type'] == 'subscription') {
            $name = $info['name'] ?? 'Not Available';
            $email = $info['email'] ?? 'Not Available';
            $phone = $info['phone'] ?? '0000000000';
            $callbackUrl = route('paytabs.payment.callback', ['payment' => $payment, 'info' => $info]);
        } else {
            $name = $payment->orders[0]->customer?->user?->name ?? 'Not Available';
            $email = $payment->orders[0]->customer?->user?->email ?? 'Not Available';
            $phone = $payment->orders[0]->customer?->user?->phone ?? '0000000000';
            $callbackUrl = route('paytabs.payment.callback', $payment->id);
        }

        $params = [
            'profile_id' => $config->profile_id,
            'tran_type' => 'sale',
            'tran_class' => 'ecom',
            'cart_id' => str_pad($payment->id, 6, '0', STR_PAD_LEFT),
            'cart_currency' => $config->currency ?? 'USD',
            'cart_amount' => $payment->amount,
            'hide_shipping' => true,
            'cart_description' => 'items',
            'paypage_lang' => 'en',
            'callback' => $callbackUrl,
            'return' => $callbackUrl,
            'customer_ref' => 'test', // convert to string
            'customer_details' => [
                'name' => $name,
                'email' => $email,
                'phone' => $phone,
                'street1' => 'Not Available',
                'city' => 'Not Available',
                'state' => 'Not Available',
                'country' => 'Not Available',
                'zip' => '00000',
            ],
            'valu_down_payment' => '0',
            'tokenise' => 1,
        ];

        $baseUrl = $config->base_url ?? 'https://secure-global.paytabs.com';

        try {

            $response = Http::withHeaders([
                'Authorization' => $config->server_key,
                'Content-Type' => 'application/json',
            ])->post($baseUrl.'/payment/request', $params);

            $payment->update(['payment_token' => $response['tran_ref']]);

            return $response['redirect_url'];

        } catch (\Throwable $th) {
            return json_encode(['error' => $th->getMessage()]);
        }
    }

    public function callback(Request $request, Payment $payment)
    {
        $info = $request->info;
        $paymentGateway = PaymentGateway::where('name', 'paytabs')->first();

        $config = json_decode($paymentGateway->config);

        $baseUrl = $config->base_url ?? 'https://secure-global.paytabs.com';

        $response = Http::withHeaders([
            'Authorization' => $config->server_key,
            'Content-Type' => 'application/json',
        ])->post($baseUrl.'/payment/query', [
            'profile_id' => $config->profile_id,
            'tran_ref' => $payment->payment_token,
        ])->json();

        if (isset($response['payment_result']['response_status']) && $response['payment_result']['response_status'] == 'A') {
            if ($info && $info['type'] == 'subscription') {
                return to_route('subscription.payment.success', ['payment' => $payment, 'token' => $payment->payment_token]);
            }

            return to_route('payment.success', ['payment' => $payment]);
        } else {
            $errorMessage = isset($response['payment_result']['response_message']) ? $response['payment_result']['response_message'] : 'Payment failed';

            if ($info && $info['type'] == 'subscription') {
                return to_route('subscription.payment.cancel', ['payment' => $payment, 'token' => $payment->payment_token, 'error' => $errorMessage]);
            }

            return to_route('order.payment.cancel', ['payment' => $payment, 'error' => $errorMessage]);
        }
    }
}
