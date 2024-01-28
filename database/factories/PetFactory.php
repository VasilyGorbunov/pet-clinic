<?php

namespace Database\Factories;

use App\Enums\PetType;
use App\Models\Pet;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Carbon;

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
