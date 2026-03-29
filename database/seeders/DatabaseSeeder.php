<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        User::firstOrCreate(
            ['email' => 'admin@mai.com'],
            [
                'name'     => 'System Admin',
                'role'     => 'system_admin',
                'password' => bcrypt('11235813'),
            ]
        );
    }
}
