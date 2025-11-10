<?php

namespace Database\Seeders;

use App\Models\SocialLink;
use Illuminate\Database\Seeder;

class SocialLinkSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        SocialLink::truncate();

        $data = [
            [
                'link' => null,
                'logo' => '/assets/social/facebook.png',
                'name' => 'Facebook',
            ],
            [
                'link' => 'https://www.linkedin.com/company/razinsoft',
                'logo' => '/assets/social/linkedin.png',
                'name' => 'LinkedIn',
            ],
            [
                'link' => null,
                'logo' => '/assets/social/instagram.png',
                'name' => 'Instagram',
            ],
            [
                'link' => 'https://www.youtube.com/@razinsoft',
                'logo' => '/assets/social/youtube.png',
                'name' => 'YouTube',
            ],
            [
                'link' => null,
                'logo' => '/assets/social/whatsapp.png',
                'name' => 'WhatsApp',
            ],
            [
                'link' => null,
                'logo' => '/assets/social/twitter.png',
                'name' => 'Twitter',
            ],
            [
                'link' => null,
                'logo' => '/assets/social/telegram.png',
                'name' => 'Telegram',
            ],
            [
                'link' => null,
                'logo' => '/assets/social/google-plus.png',
                'name' => 'Google Plus',
            ],
        ];

        SocialLink::insert($data);
    }
}
