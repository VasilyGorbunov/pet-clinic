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

        for ($i = 1; $i <= 3; $i++) {
            $date = now()->addDays($i)->setTime(10, 0);
            $schedule_date = now()->addDays($i)->setTime(0, 0);

            $schedule = Schedule::factory()->for($doctor1, 'owner')->create([
                'date' => $schedule_date
            ]);

            Slot::factory()->for($schedule, 'schedule')->createMany([
                [
                    'start' => Carbon::parse($date),
                    'end' => Carbon::parse($date)->addMinutes(20),
                ],
                [
                    'start' => Carbon::parse($date)->addHours(1),
                    'end' => Carbon::parse($date)->addHours(1)->addMinutes(20),
                ],
                [
                    'start' => Carbon::parse($date)->addHours(2),
                    'end' => Carbon::parse($date)->addHours(2)->addMinutes(20),
                ],
                [
                    'start' => Carbon::parse($date)->addHours(3),
                    'end' => Carbon::parse($date)->addHours(3)->addMinutes(20),
                ],
            ]);
        }

        $doctor2 = User::whereName('Doctor 2')->first();

        for ($i = 1; $i <= 3; $i++) {
            $date = now()->addDays($i)->setTime(12, 0);
            $schedule_date = now()->addDays($i)->setTime(0, 0);

            $schedule = Schedule::factory()->for($doctor2, 'owner')->create([
                'date' => $schedule_date
            ]);

            Slot::factory()->for($schedule, 'schedule')->createMany([
                [
                    'start' => Carbon::parse($date),
                    'end' => Carbon::parse($date)->addMinutes(30),
                ],
                [
                    'start' => Carbon::parse($date)->addHours(1),
                    'end' => Carbon::parse($date)->addHours(1)->addMinutes(30),
                ],
                [
                    'start' => Carbon::parse($date)->addHours(2),
                    'end' => Carbon::parse($date)->addHours(2)->addMinutes(30),
                ],
            ]);
        }

        $doctor3 = User::whereName('Doctor 3')->first();

        for ($i = 1; $i <= 3; $i++) {
            $date = now()->addDays($i)->setTime(18, 0);
            $schedule_date = now()->addDays($i)->setTime(0, 0);

            $schedule = Schedule::factory()->for($doctor3, 'owner')->create([
                'date' => $schedule_date
            ]);

            Slot::factory()->for($schedule, 'schedule')->createMany([
                [
                    'start' => Carbon::parse($date),
                    'end' => Carbon::parse($date)->addMinutes(25),
                ],
                [
                    'start' => Carbon::parse($date)->addHours(1),
                    'end' => Carbon::parse($date)->addHours(1)->addMinutes(30),
                ],
                [
                    'start' => Carbon::parse($date)->addHours(2),
                    'end' => Carbon::parse($date)->addHours(2)->addMinutes(40),
                ],
                [
                    'start' => Carbon::parse($date)->addHours(3),
                    'end' => Carbon::parse($date)->addHours(3)->addMinutes(55),
                ],
            ]);
        }

    }
}
