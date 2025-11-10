<?php

namespace Database\Seeders;

use App\Enums\Roles;
use App\Models\User;
use App\Repositories\DriverRepository;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class RiderSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $user = User::where('email', 'rider@readygrocery.com')->first();

        if (!$user) {
            $user = User::factory()->create([
                'name' => 'Demo Rider',
                'email' => 'rider@readygrocery.com',
                'phone' => '0170000000',
                'password' => Hash::make('secret'),
                'is_active' => true,
            ]);
        }

        $user->assignRole(Roles::DRIVER->value);
        DriverRepository::storeByUser($user);

        $user->update(['is_active' => true]);
    }
}
