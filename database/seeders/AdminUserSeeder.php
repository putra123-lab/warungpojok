<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminUserSeeder extends Seeder
{
    public function run(): void
    {
        User::updateOrCreate(
            ['email' => 'admin@warmindo.test'],
            [
                'name' => 'Admin Warmindo',
                'password' => Hash::make('AdminWarmindo123!'),
                'role' => 'admin',
            ]
        );
    }
}
