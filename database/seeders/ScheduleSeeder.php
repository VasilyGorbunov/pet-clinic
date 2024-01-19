<?php

namespace Database\Seeders;

use App\Models\Schedule;
use App\Models\Slot;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class ScheduleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $doctor1 = User::whereName('Doctor 1')->first();

        $date = now();

        for ($i = 1; $i <= 3; $i++) {
            $schedule = Schedule::factory()->for($doctor1, 'owner')->create([
                'date' => $date
            ]);

            $end = now()->addHours(1)->addMinutes(10);
            Slot::factory()->for($schedule, 'owner')->create(
                [
                    'start' => Carbon::now()->addHours(2),
                    'end' => Carbon::now()->addHours(3),
                ]
            );

            $date = now()->addDays($i);
        }


    }
}
