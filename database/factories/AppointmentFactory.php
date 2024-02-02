<?php

namespace Database\Factories;

use App\Enums\AppointmentStatus;
use App\Models\Appointment;
use App\Models\Clinic;
use App\Models\Pet;
use App\Models\Slot;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Carbon;

class AppointmentFactory extends Factory
{
  protected $model = Appointment::class;

  public function definition(): array
  {
    return [
      'description' => $this->faker->text(),
      'status' => AppointmentStatus::Created,
      'created_at' => Carbon::now(),
      'updated_at' => Carbon::now(),
      'date' => Carbon::now(),

      'pet_id' => Pet::factory(),
      'slot_id' => Slot::factory(),
      'clinic_id' => Clinic::factory(),
      'doctor_id' => User::factory(),
    ];
  }
}
