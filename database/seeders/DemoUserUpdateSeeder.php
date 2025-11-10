<?php

namespace Database\Seeders;

use App\Models\User;
use App\Repositories\CustomerRepository;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DemoUserUpdateSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $email = 'user@readygrocery.com';

        $user = User::where('email', $email)->first();
        if ($user) {
            $user->update([
                'name' => 'Demo Customer',
                'email' => 'user@readygrocery.com',
                'phone' => '01000000405',
                'password' => Hash::make('secret'),
                'is_active' => true,
            ]);

            if (! $user->hasRole('customer')) {
                $user->assignRole('customer');
            }

            if (! $user->customer) {
                CustomerRepository::storeByRequest($user);
            }
        }
    }
}
