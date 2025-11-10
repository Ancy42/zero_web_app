<?php

namespace Database\Seeders;

use App\Models\VerifyManage;
use Illuminate\Database\Seeder;

class VerifyManageSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        VerifyManage::truncate();

        $data = [
            [
                'register_otp' => 0,
                'register_otp_type' => 'email',
                'forgot_otp' => 1,
                'forgot_otp_type' => 'email',
                'phone_required' => 0,
                'email_required' => 1,
                'phone_min_length' => 9,
                'phone_max_length' => 16,
                'order_place_account_verify' => 0,
            ],
        ];

        VerifyManage::insert($data);
    }
}
