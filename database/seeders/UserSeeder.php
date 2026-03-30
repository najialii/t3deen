<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $refineries = \App\Models\Refinery::all();

        foreach ($refineries as $refinery) {
            // Create a sales manager for each refinery
            \App\Models\User::factory()->create([
                'role' => 'sales_manager',
                'refinery_id' => $refinery->id,
            ]);

            // Create 2 workers for each refinery
            \App\Models\User::factory()->count(2)->create([
                'role' => 'worker',
                'refinery_id' => $refinery->id,
            ]);
        }
    }
}
