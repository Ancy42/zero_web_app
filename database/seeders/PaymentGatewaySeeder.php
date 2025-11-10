<?php

namespace Database\Seeders;

use App\Models\PaymentGateway;
use Illuminate\Database\Seeder;

class PaymentGatewaySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        PaymentGateway::truncate();
        $paymentMethods = [
            [
                'title' => 'Stripe',
                'name' => 'stripe',
                'config' => json_encode([
                    'secret_key' => 'sk_test_AC8LYQ8cVN0RNGdhZ7G02zWe00lYKYw7LR',
                    'published_key' => 'pk_test_2Iu9vNpu2ROjYOb9KHDBa3Hb00KSavaClK',
                ]),
                'mode' => 'test',
                'alias' => 'Stripe',
                'is_active' => true,
            ],
            [
                'title' => 'PayPal',
                'name' => 'paypal',
                'config' => json_encode([
                    'client_id' => 'ASw2Ol4zJrd7UOYWz7Vjwv2ZBEZ9AXuF4aCbSXLXImOp8HaCFwGHCggJ1QBuzSoouGJ6vMncd9pMAtV9',
                    'client_secret' => 'EA3d_eVh67xx4_vk1FYAsV75faeFvLVf1B6d2Rg9E4BfjXetw63k883MtSoVLi2v8P3bbW3tOJVFEKdt',
                ]),
                'mode' => 'test',
                'alias' => 'PayPal',
                'is_active' => true,
            ],
            [
                'title' => 'Razorpay',
                'name' => 'razorpay',
                'config' => json_encode([
                    'key' => 'rzp_test_k23Mr4BskGqpBu',
                    'secret' => 'LTrXh7U5xWeZoAHcqdhemFkg',
                ]),
                'mode' => 'test',
                'alias' => 'Razorpay',
                'is_active' => true,
            ],
            [
                'title' => 'Paystack',
                'name' => 'paystack',
                'config' => json_encode([
                    'public_key' => 'pk_test_0c871ddaa80aafd5b64f14390e0745a6c3c274bc',
                    'secret_key' => 'sk_test_03c7e6762cf1772676272d4677e21e60323610aa',
                    'machant_email' => '',
                ]),
                'mode' => 'test',
                'alias' => 'PayStack',
                'is_active' => true,
            ],
            [
                'title' => 'aamarPay',
                'name' => 'aamarpay',
                'config' => json_encode([
                    'store_id' => 'aamarpaytest',
                    'signature_key' => 'dbb74894e82415a2f7ff0ec3a97e4183',
                ]),
                'mode' => 'test',
                'alias' => 'AamarPay',
                'is_active' => true,
            ],
            [
                'title' => 'BKash',
                'name' => 'bKash',
                'config' => json_encode([
                    'username' => 'sandboxTokenizedUser02',
                    'password' => 'sandboxTokenizedUser02@12345',
                    'app_key' => '4f6o0cjiki2rfm34kfdadl1eqq',
                    'app_secret_key' => '2is7hdktrekvrbljjh44ll3d9l1dtjo4pasmjvs5vl5qr3fug4b',
                ]),
                'mode' => 'test',
                'alias' => 'Bkash',
                'is_active' => true,
            ],
            [
                'title' => 'PayTabs',
                'name' => 'paytabs',
                'config' => json_encode([
                    'base_url' => 'https://secure-global.paytabs.com',
                    'profile_id' => '142160',
                    'server_key' => 'S6J9R6JRLB-JJBGTHLGJK-GZWGDGZMJL',
                    'currency' => 'USD',
                ]),
                'mode' => 'test',
                'alias' => 'PayTabs',
                'is_active' => false,
            ],
        ];

        PaymentGateway::insert($paymentMethods);
    }
}
