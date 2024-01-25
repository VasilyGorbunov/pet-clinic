<?php

namespace Database\Seeders;

use App\Models\Clinic;
use App\Models\Schedule;
use App\Models\Slot;
use App\Models\User;
use Illuminate\Database\Seeder;

class ScheduleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {

        for ($i = 0; $i <= 6; $i++) {
            $schedule = Schedule::factory()->create([
                'owner_id' => User::whereName('Doctor 1')->first()->id,
                'clinic_id' => Clinic::find(1)->id,
                'day_of_week' => $i
            ]);

            for ($hour = 0; $hour <= 3; $hour++) {
                Slot::factory()->create([
                    'schedule_id' => $schedule->id,
                    'status' => 'available',
                    'start' => now()->setTime(10 + $hour, 0),
                    'end' => now()->setTime(10 + $hour, 20),
                ]);
            }

            $schedule = Schedule::factory()->create([
                'owner_id' => User::whereName('Doctor 1')->first()->id,
                'clinic_id' => Clinic::find(2)->id,
                'day_of_week' => $i
            ]);

            for ($hour = 0; $hour <= 3; $hour++) {
                Slot::factory()->create([
                    'schedule_id' => $schedule->id,
                    'status' => 'available',
                    'start' => now()->setTime(8 + $hour, 0),
                    'end' => now()->setTime(8 + $hour, 20),
                ]);
            }

        }


    }
}
