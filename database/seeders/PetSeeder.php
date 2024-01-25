<?php

namespace Database\Seeders;

use App\Models\Clinic;
use App\Models\Pet;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class PetSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $clinic1 = Clinic::find(1);
        $clinic2 = Clinic::find(2);

        Pet::factory()->create([
            'name' => 'Cat 1',
            'date_of_birth' => Carbon::now()->subYears(3),
            'avatar' => null,
            'owner_id' => User::whereName('Owner 1')->first()->id,
            'type' => 'cat'
        ])->clinics()->attach($clinic1);


        Pet::factory()->create([
            'name' => 'Dog 1',
            'date_of_birth' => Carbon::now()->subYears(8),
            'avatar' => null,
            'owner_id' => User::whereName('Owner 1')->first()->id,
            'type' => 'dog'
        ])->clinics()->attach($clinic1);

        Pet::factory()->create([
            'name' => 'Cat 2',
            'date_of_birth' => Carbon::now()->subYears(1),
            'avatar' => null,
            'owner_id' => User::whereName('Owner 2')->first()->id,
            'type' => 'cat'
        ])->clinics()->attach($clinic1);

        Pet::factory()->create([
            'name' => 'Cat 3',
            'date_of_birth' => Carbon::now()->subYears(5),
            'avatar' => null,
            'owner_id' => User::whereName('Owner 3')->first()->id,
            'type' => 'cat'
        ])->clinics()->attach($clinic2);

        Pet::factory()->create([
            'name' => 'Dog 3',
            'date_of_birth' => Carbon::now()->subYears(7),
            'avatar' => null,
            'owner_id' => User::whereName('Owner 3')->first()->id,
            'type' => 'dog'
        ])->clinics()->attach($clinic2);
    }
}
