<?php

namespace Database\Seeders;

use App\Enums\Roles;
use App\Models\User;
use Illuminate\Database\Seeder;

class SetRootRole extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $user = User::where('email', 'root@readygrocery.com')->first();
        if ($user) {
            $user->assignRole(Roles::ROOT->value);
        }
    }
}
