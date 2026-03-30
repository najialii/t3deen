<?php

namespace Database\Factories;

use App\Models\Machine;
use App\Models\Refinery;
use Illuminate\Database\Eloquent\Factories\Factory;

class MachineFactory extends Factory
{
    protected $model = Machine::class;

    public function definition(): array
    {
        return [
            'name' => $this->faker->word . ' Machine',
            'unit' => $this->faker->randomElement(Machine::$units),
            'price_per_unit' => $this->faker->randomFloat(4, 10, 1000),
            // 'refinery_id' betterrrrr be set in seeder
            'is_active' => $this->faker->boolean(90),
        ];
    }
}
