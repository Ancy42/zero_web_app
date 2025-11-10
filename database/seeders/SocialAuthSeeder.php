<?php

namespace Database\Seeders;

use App\Models\SocialAuth;
use Illuminate\Database\Seeder;

class SocialAuthSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        SocialAuth::truncate();

        $isLocal = app()->isLocal();

        $data = [
            [
                'name' => 'Google',
                'client_id' => $isLocal ? '624695883390-4s3vbj7v43es32lfkh1kp1eq9opcnl30.apps.googleusercontent.com' : null,
                'client_secret' => $isLocal ? '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' : null,
                'redirect' => 'postmessage',
                'provider' => 'google',
                'logo' => 'assets/social/google.svg',
                'is_active' => false,
            ],
            [
                'name' => 'Facebook',
                'client_id' => '',
                'client_secret' => '',
                'redirect' => '',
                'provider' => 'facebook',
                'logo' => 'assets/social/facebook.svg',
                'is_active' => false,
            ],
            [
                'name' => 'Apple',
                'client_id' => $isLocal ? 'com.readygrocery.web' : null,
                'client_secret' => '',
                'redirect' => null,
                'provider' => 'apple',
                'logo' => 'assets/social/apple.svg',
                'is_active' => false,
            ],
        ];

        SocialAuth::insert($data);
    }
}
