<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    public function run(): void
    {
        User::firstOrCreate(
            ['email' => 'admin@furniter.com'],
            [
                'name'     => 'Admin',
                'email'    => 'admin@furniter.com',
                'role'     => 'admin',
                'password' => Hash::make('admin123'),
            ]
        );
    }
}
