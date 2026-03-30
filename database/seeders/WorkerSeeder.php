<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class WorkerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $refineries = \App\Models\Refinery::all();
        foreach ($refineries as $refinery) {
            \App\Models\Worker::factory()->count(5)->create([
                'refinery_id' => $refinery->id,
            ]);
        }
    }
}
