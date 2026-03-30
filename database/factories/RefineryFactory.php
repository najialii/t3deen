<?php

namespace Database\Factories;

use App\Models\Refinery;
use Illuminate\Database\Eloquent\Factories\Factory;

class RefineryFactory extends Factory
{
    
    protected $model = Refinery::class;

    public function definition(): array
    {
        return [
            'name' => $this->faker->company . ' Refinery',
            'location' => $this->faker->city,
            'phone' => $this->faker->phoneNumber,
            'is_active' => $this->faker->boolean(90),
        ];
    }
}
