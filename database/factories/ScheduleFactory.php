<?php

namespace Database\Factories;

use App\Models\Clinic;
use App\Models\Schedule;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Carbon;

class ScheduleFactory extends Factory
{
  protected $model = Schedule::class;

  public function definition(): array
  {
    return [
      'created_at' => Carbon::now(),
      'updated_at' => Carbon::now(),
      'day_of_week' => $this->faker->word(),

      'owner_id' => User::factory(),
      'clinic_id' => Clinic::factory(),
    ];
  }
}
