<?php

namespace Database\Factories;

use App\Models\Schedule;
use App\Models\Slot;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Carbon;

class SlotFactory extends Factory
{
    protected $model = Slot::class;

    public function definition(): array
    {
        return [
          'start' => Carbon::now(),
          'end' => Carbon::now(),
          'status' => $this->faker->word(),
          'created_at' => Carbon::now(),
          'updated_at' => Carbon::now(),

          'schedule_id' => Schedule::factory(),
        ];
    }
}
