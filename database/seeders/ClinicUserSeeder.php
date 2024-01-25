<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ClinicUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('clinic_user')->insert([
            'clinic_id' => 1,
            'user_id' => User::whereName('Doctor 1')->first()->id
        ]);

        DB::table('clinic_user')->insert([
            'clinic_id' => 2,
            'user_id' => User::whereName('Doctor 1')->first()->id
        ]);

        DB::table('clinic_user')->insert([
            'clinic_id' => 1,
            'user_id' => User::whereName('Doctor 2')->first()->id
        ]);

        DB::table('clinic_user')->insert([
            'clinic_id' => 2,
            'user_id' => User::whereName('Doctor 3')->first()->id
        ]);

    }
}
