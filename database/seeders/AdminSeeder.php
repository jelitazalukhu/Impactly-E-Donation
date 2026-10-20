<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    public function run(): void
    {
               if (User::where('role', 'admin')->exists()) {
            return;
        }

        User::create([
            'name'      => 'Administrator',
            'email'     => env('ADMIN_EMAIL', 'admin@edonation.test'),
            'password'  => Hash::make(env('ADMIN_PASSWORD', 'Admin#12345')),
            'role'      => 'admin',
            'is_active' => true,
        ]);
    }
}
