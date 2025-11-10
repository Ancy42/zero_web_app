<?php

namespace App\Http\Controllers\Gateway\AamarPay;

use App\Http\Controllers\Controller;
use App\Models\Payment;
use Illuminate\Support\Str;

class ProcessController extends Controller
{
    /**
     * Process to aamarPay
     *
     * @return string
     */
    public static function process($paymentGateway, Payment $payment, ?array $info = null)
    {
        $config = json_decode($paymentGateway->config);

        $paymentToken = Str::uuid()->toString();

        $payment->update([
            'payment_token' => $paymentToken
        ]);

        $successUrl = $cancelUrl = null;

        if ($info) {
            $customerEmail = $info['email'] ?? 'example@gmail.com';
            $customerPhone = $info['phone'] ?? '01870******';
            $customerName = $info['name'] ?? 'Example Name';
            $description = $info['description'];

            if ($info['type'] == 'subscription') {
                $successUrl = route('subscription.payment.success', ['payment' => $payment, 'token' => $paymentToken]);
                $cancelUrl = route('subscription.payment.cancel', ['payment' => $payment, 'token' => $paymentToken]);
            } else {
                $successUrl = route('payment.success', $payment->id);
                $cancelUrl = route('payment.cancel', $payment->id);
            }
        } else {
            $customerEmail = $payment->orders[0]?->customer?->user?->email ?? 'example@gmail.com';
            $customerPhone = $payment->orders[0]?->customer?->user?->phone ?? '01870******';
            $customerName = $payment->orders[0]?->customer?->user?->name ?? 'Example Name';
            $description = 'Total order '.$payment->orders->count().' total amount '.$payment->amount.'BDT '.' orderIDs '.implode(',', $payment->orders->pluck('id')->toArray());
            $successUrl = route('payment.success', $payment->id);
            $cancelUrl = route('payment.cancel', $payment->id);
        }

        $endPoint = $paymentGateway->mode == 'live' ? 'https://secure.aamarpay.com/index.php' : 'https://sandbox.aamarpay.com/index.php';

        $transitionID = str_pad($payment->id, 6, '0', STR_PAD_LEFT);

        $curl = curl_init();

        curl_setopt_array($curl, [
            CURLOPT_URL => $endPoint,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS => [
                'store_id' => $config->store_id,
                'signature_key' => $config->signature_key,
                'cus_name' => $customerName,
                'cus_email' => $customerEmail,
                'cus_phone' => $customerPhone,
                'amount' => $payment->amount,
                'currency' => 'BDT',
                'tran_id' => $transitionID,
                'desc' => $description,
                'success_url' => $successUrl,
                'fail_url' => $cancelUrl,
                'cancel_url' => $cancelUrl,
                'type' => 'json',
            ],
        ]);

        $response = curl_exec($curl);

        curl_close($curl);

        $response = json_decode($response);

        if (isset($response->payment_url)) {
            return $response->payment_url;
        }

        $error = is_object($response) ? json_encode($response, JSON_PRETTY_PRINT) : (string)$response;

        return json_encode(['error' => $error]);
    }
}
