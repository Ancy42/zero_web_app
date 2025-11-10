<?php

namespace App\Http\Controllers\Gateway\PayPal;

use App\Http\Controllers\Controller;
use App\Models\Payment;
use App\Models\PaymentGateway;
use App\Models\PaypalPayment;
use App\Repositories\PaypalPaymentRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use PayPalCheckoutSdk\Core\PayPalHttpClient;
use PayPalCheckoutSdk\Core\ProductionEnvironment;
use PayPalCheckoutSdk\Core\SandboxEnvironment;
use PayPalCheckoutSdk\Orders\OrdersCaptureRequest;
use PayPalCheckoutSdk\Orders\OrdersCreateRequest;
use PayPalHttp\HttpException;

class ProcessController extends Controller
{
    /**
     * Process to PayPal
     *
     * @return string
     */
    public static function process($paymentGateway, Payment $payment, ?array $info = null)
    {
        $config = json_decode($paymentGateway->config);

        $environment = $paymentGateway->mode === 'live'
            ? new ProductionEnvironment($config->client_id, $config->client_secret)
            : new SandboxEnvironment($config->client_id, $config->client_secret);

        $client = new PayPalHttpClient($environment);

        $paymentToken = Str::uuid()->toString();

        $payment->update([
            'payment_token' => $paymentToken
        ]);

        $successUrl = $cancelUrl = null;

        $paypalPayment = PaypalPaymentRepository::create([
            'payment_id' => $payment->id,
        ]);

        $successUrl = route('paypal.payment.success', ['paypalPayment' => $paypalPayment, 'info' => $info]);

        if ($info) {
            if ($info['type'] == 'subscription') {
                $cancelUrl = route('subscription.payment.cancel', ['payment' => $payment, 'token' => $paymentToken]);
            } else {
                $cancelUrl = route('payment.cancel', $payment->id);
            }
        } else {
            $cancelUrl = route('payment.cancel', $payment->id);
        }

        $request = new OrdersCreateRequest;
        $request->prefer('return=representation');
        $request->body = [
            'intent' => 'CAPTURE',
            'purchase_units' => [[
                'amount' => [
                    'currency_code' => 'USD',
                    'value' => $payment->amount,
                ],
            ]],
            'application_context' => [
                'return_url' => $successUrl,
                'cancel_url' => $cancelUrl,
            ],
        ];

        try {
            $response = $client->execute($request);
            $paypalPayment->update([
                'order_id' => $response->result->id
            ]);

            return $response->result->links[1]->href; // Redirect to PayPal for payment approval
        } catch (\Throwable $th) {
            return $th->getMessage();
        }
    }

    public function success(Request $request, PaypalPayment $paypalPayment)
    {
        if ($paypalPayment->order_id) {
            $payment = $paypalPayment->payment;
            $info = $request->info;
            $paymentGateway = PaymentGateway::where('name', 'paypal')->first();

            $config = json_decode($paymentGateway->config);

            $environment = $paymentGateway->mode === 'live'
                ? new ProductionEnvironment($config->client_id, $config->client_secret)
                : new SandboxEnvironment($config->client_id, $config->client_secret);

            $client = new PayPalHttpClient($environment);
            $request = new OrdersCaptureRequest($paypalPayment->order_id);

            $request->prefer('return=representation');

            try {
                $response = $client->execute($request);
            } catch (HttpException $ex) {
                return to_route('order.payment.cancel', ['payment' => $payment, 'error' => $ex->getMessage()]);
            }

            if ($response->result->status == 'COMPLETED' && $response->result->purchase_units[0]->payments->captures[0]->status == 'COMPLETED') {
                if ($info && $info['type'] == 'subscription') {
                    return to_route('subscription.payment.success', ['payment' => $payment, 'token' => $payment->payment_token]);
                }

                return to_route('payment.success', ['payment' => $payment]);
            } else {
                if ($info && $info['type'] == 'subscription') {
                    return to_route('subscription.payment.cancel', ['payment' => $payment, 'token' => $payment->payment_token, 'error' => 'Payment failed']);
                }

                return to_route('payment.cancel', ['payment' => $payment, 'error' => 'Payment failed']);
            }
        }

        return to_route('order.payment.success', $paypalPayment->payment->id);
    }
}
