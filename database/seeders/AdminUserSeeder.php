<?php

namespace Database\Seeders;

use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::firstOrCreate(
            [
                'email' => "admin@buckhill.co.uk",
            ],
            [
                'first_name' => "Admin",
                'last_name' => "Buckhill",
                'is_admin' => true,
                'email_verified_at' => Carbon::now(),
                'password' => Hash::make('admin'),
                'avatar' => null,
                'address' => "Address St. 5",
                'phone_number' => "09112920292",
                'is_marketing' => false,
                'last_login_at' => null,
            ]
        );
    }
}
