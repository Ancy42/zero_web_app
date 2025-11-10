<?php

namespace App\Http\Controllers\Gateway\Bkash;

use App\Http\Controllers\Controller;
use App\Models\Payment;
use App\Models\PaymentGateway;
use Illuminate\Support\Str;

class ProcessController extends Controller
{
    /**
     * Process to bKash
     *
     * @return string
     */
    public static function process($paymentGateway, Payment $payment, ?array $info = null)
    {
        $config = json_decode($paymentGateway->config);

        $response = json_decode(self::tokenGrant($paymentGateway));

        if ($response->statusCode != '0000') {
            return json_encode(['error' => $response->statusMessage]);
        }

        if ($info && $info['type'] == 'subscription') {
            $paymentToken = Str::uuid()->toString();

            $payment->update([
                'payment_token' => $paymentToken
            ]);

            $info['payment_token'] = $paymentToken;
            $callbackUrl = route('bkash.payment.execute', ['payment' => $payment->id, 'info' => $info, 'token' => $response->id_token]);
        } else {
            $callbackUrl = route('bkash.payment.execute', ['payment' => $payment->id, 'token' => $response->id_token]);
        }

        $url = 'https://tokenized.sandbox.bka.sh/v1.2.0-beta/tokenized/checkout/create';
        if ($paymentGateway->mode == 'live') {
            $url = 'https://tokenized.pay.bka.sh/v1.2.0-beta/tokenized/checkout/create';
        }

        $requestBody = [
            'mode' => '0011',
            'amount' => $payment->amount,
            'currency' => 'BDT',
            'intent' => 'sale',
            'payerReference' => '01',
            'merchantInvoiceNumber' => 'invoice-'.str_pad($payment->id, 6, '0', STR_PAD_LEFT),
            'callbackURL' => $callbackUrl,
        ];

        $header = [
            'Content-Type:application/json',
            'Accept:application/json',
            'Authorization:'.$response->id_token,
            'X-APP-Key:'.$config->app_key,
        ];

        $resultData = self::executeCurl($url, $requestBody, $header);

        $resultData = json_decode($resultData);

        if (isset($resultData->bkashURL)) {
            return $resultData->bkashURL;
        }

        return json_encode(['error' => $resultData->statusMessage]);
    }

    /**
     * bKash token grant
     *
     * @return string
     */
    private static function tokenGrant(PaymentGateway $paymentGateway)
    {
        $config = json_decode($paymentGateway->config);

        $url = 'https://tokenized.sandbox.bka.sh/v1.2.0-beta/tokenized/checkout/token/grant';

        if ($paymentGateway->mode == 'live') {
            $url = 'https://tokenized.pay.bka.sh/v1.2.0-beta/tokenized/checkout/token/grant';
        }

        $authorizeData = [
            'app_key' => $config->app_key,
            'app_secret' => $config->app_secret_key,
        ];

        $header = [
            'Content-Type:application/json',
            'Accept:application/json',
            'username:'.$config->username,
            'password:'.$config->password,
        ];

        return self::executeCurl($url, $authorizeData, $header);
    }

    /**
     * bKash payment execute eCurl
     *
     * @return string
     */
    private static function executeCurl($url, $data, $header)
    {
        $curl = curl_init($url);
        curl_setopt($curl, CURLOPT_HTTPHEADER, $header);
        curl_setopt($curl, CURLOPT_CUSTOMREQUEST, 'POST');
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_POSTFIELDS, json_encode($data));
        curl_setopt($curl, CURLOPT_FOLLOWLOCATION, 1);
        curl_setopt($curl, CURLOPT_IPRESOLVE, CURL_IPRESOLVE_V4);

        $result = curl_exec($curl);

        curl_close($curl);

        return $result;
    }
}
