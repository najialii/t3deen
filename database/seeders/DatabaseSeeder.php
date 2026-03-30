<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
   
    public function run(): void
    {

    User::factory()->create([
        'name' => 'Admin User',
        'email' => 'admin@mail.com',
        'password' => bcrypt('password'),
        'role' => 'system_admin',
    ]);
        $this->call([
            UserSeeder::class,
            RefinerySeeder::class,
            MachineSeeder::class,
            WorkerSeeder::class,
        ]);
    }
}