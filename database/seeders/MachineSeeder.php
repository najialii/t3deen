<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class MachineSeeder extends Seeder
{
   
    public function run(): void
    {
        $refineries = \App\Models\Refinery::all();
        foreach ($refineries as $refinery) {
            \App\Models\Machine::factory()->count(3)->create([
                'refinery_id' => $refinery->id,
            ]);
        }
    }
}
