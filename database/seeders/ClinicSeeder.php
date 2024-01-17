<?php

namespace Database\Seeders;

use App\Models\Clinic;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ClinicSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('clinics')->insert([
            'name' => 'Default Clinic'
        ]);

        Clinic::first()->users()->attach(1);
    }
}
