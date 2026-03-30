<?php

namespace Database\Factories;

use App\Models\Worker;
use App\Models\Refinery;
use Illuminate\Database\Eloquent\Factories\Factory;

class WorkerFactory extends Factory
{
    protected $model = Worker::class;

    public function definition(): array
    {
        return [
            'name' => $this->faker->name,
            'phone' => $this->faker->phoneNumber,
            'national_id' => $this->faker->unique()->numerify('##########'),
            'refinery_id' => Refinery::factory(),
            'is_active' => $this->faker->boolean(90),
        ];
    }
}
