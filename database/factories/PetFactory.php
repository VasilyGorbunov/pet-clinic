<?php

namespace Database\Factories;

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
      'name' => $this->faker->name(),
      'date_of_birth' => Carbon::now(),
      'type' => $this->faker->word(),
      'avatar' => $this->faker->word(),
      'created_at' => Carbon::now(),
      'updated_at' => Carbon::now(),

      'owner_id' => User::factory(),
    ];
  }
}
