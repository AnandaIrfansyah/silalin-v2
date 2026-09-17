<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $admin = User::firstOrCreate(
            ['email' => 'admin@silalin.test'], // sesuaikan email admin
            [
                'name' => 'Administrator',
                'password' => Hash::make('password'), // ganti password default ini
                'email_verified_at' => now(),
            ]
        );

        $admin->assignRole('Admin');
    }
}
