<?php

namespace App\Http\Controllers\Gateway\Bkash;

use App\Http\Controllers\Controller;
use App\Models\Payment;
use App\Models\PaymentGateway;
use Illuminate\Http\Request;

class ExecutePaymentController extends Controller
{
    /**
     * charge payment and execute
     */
    public function index(Payment $payment, Request $request)
    {
        $paymentID = $request->paymentID;
        $paymentStatus = $request->status;
        $info = $request->info;
        $token = $request->token;

        if ($paymentStatus == 'success') {
            $response = $this->executePayment($paymentID, $token);

            if ($response->statusCode == '0000') {
                if ($info && $info['type'] == 'subscription') {
                    return to_route('subscription.payment.success', ['payment' => $payment->id, 'token' => $info['payment_token']]);
                }

                return to_route('payment.success', ['payment' => $payment->id, 'token' => $token]);
            }

            if ($info && $info['type'] == 'subscription') {
                return to_route('subscription.payment.cancel', ['payment' => $payment, 'token' => $info['payment_token'], 'error' => 'Payment execute failed']);
            }

            return to_route('order.payment.cancel', ['payment' => $payment, 'error' => 'Payment execute failed']);
        }

        if ($info && $info['type'] == 'subscription') {
            return to_route('subscription.payment.cancel', ['payment' => $payment, 'token' => $info['payment_token'], 'error' => 'Payment execute failed']);
        }

        return to_route('order.payment.cancel', ['payment' => $payment, 'error' => 'Payment execute failed']);
    }

    /**
     *  execute payment
     */
    private function executePayment($paymentID, $token)
    {
        $paymentGateway = PaymentGateway::where('name', 'bkash')->first();
        $config = json_decode($paymentGateway->config);

        $paymentID = $paymentID;

        $postToken = [
            'paymentID' => $paymentID,
        ];

        $url = 'https://tokenized.sandbox.bka.sh/v1.2.0-beta/tokenized/checkout/execute';
        if ($paymentGateway->mode == 'live') {
            $url = 'https://tokenized.pay.bka.sh/v1.2.0-beta/tokenized/checkout/execute';
        }

        $url = curl_init($url);
        $postToken = json_encode($postToken);

        $header = [
            'Content-Type:application/json',
            'Authorization:'.$token,
            'X-APP-Key:'.$config->app_key,
        ];
        curl_setopt($url, CURLOPT_HTTPHEADER, $header);
        curl_setopt($url, CURLOPT_CUSTOMREQUEST, 'POST');
        curl_setopt($url, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($url, CURLOPT_POSTFIELDS, $postToken);
        curl_setopt($url, CURLOPT_FOLLOWLOCATION, 1);
        curl_setopt($url, CURLOPT_IPRESOLVE, CURL_IPRESOLVE_V4);
        $responseData = curl_exec($url);

        curl_close($url);

        return json_decode($responseData);
    }
}
