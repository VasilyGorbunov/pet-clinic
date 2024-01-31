<?php

namespace Database\Factories;

use App\Enums\PetType;
use App\Models\Pet;
use Illuminate\Database\Eloquent\Factories\Factory;

class PetFactory extends Factory
{
    protected $model = Pet::class;

    public function definition(): array
    {
        return [
            'name' => fake()->name(),
            'date_of_birth' => fake()->date(),
            'type' => PetType::Dog,
        ];
    }
}
