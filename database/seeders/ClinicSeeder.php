<?php

namespace Database\Seeders;

use App\Models\Clinic;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ClinicSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Clinic::factory()->createMany([
            [
                'name' => 'Pet Clinic New York',
                'address' => '123 Fake str., New York, NY',
                'zip' => '77777',
                'phone' => '123 456 78 90'
            ],
            [
                'name' => 'Pet Clinic Salt Lake City',
                'address' => '434 Fake str., Salt Lake City, SL',
                'zip' => '44444',
                'phone' => '345 333 23 45'
            ],
        ]);
    }
}
