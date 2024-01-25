<?php

namespace Database\Seeders;

use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $adminRole = Role::whereName('admin')->first();

        User::factory()->for($adminRole)->create([
            'name' => 'Admin',
            'email' => 'admin@app.com',
            'phone' => '555 555 12 34'
        ]);

        $doctorRole = Role::whereName('doctor')->first();

        User::factory()->for($doctorRole)->create([
            'name' => 'Doctor 1',
            'email' => 'doctor1@app.com',
            'phone' => '777 777 77 77'
        ]);

        User::factory()->for($doctorRole)->create([
            'name' => 'Doctor 2',
            'email' => 'doctor2@app.com',
            'phone' => '777 777 77 76'
        ]);

        User::factory()->for($doctorRole)->create([
            'name' => 'Doctor 3',
            'email' => 'doctor3@app.com',
            'phone' => '777 777 77 78'
        ]);

        $staffRole = Role::whereName('staff')->first();

        User::factory()->for($staffRole)->create([
            'name' => 'Staff 1',
            'email' => 'staff1@app.com',
            'phone' => '111 111 11 11'
        ]);

        User::factory()->for($staffRole)->create([
            'name' => 'Staff 2',
            'email' => 'staff2@app.com',
            'phone' => '111 111 11 12'
        ]);

        $ownerRole = Role::whereName('owner')->first();

        User::factory()->for($ownerRole)->create([
            'name' => 'Owner 1',
            'email' => 'owner1@app.com',
            'phone' => '333 333 33 31'
        ]);

        User::factory()->for($ownerRole)->create([
            'name' => 'Owner 2',
            'email' => 'owner2@app.com',
            'phone' => '333 333 33 32'
        ]);

        User::factory()->for($ownerRole)->create([
            'name' => 'Owner 3',
            'email' => 'owner3@app.com',
            'phone' => '333 333 33 33'
        ]);

        User::factory()->for($ownerRole)->create([
            'name' => 'Owner 4',
            'email' => 'owner4@app.com',
            'phone' => '333 333 33 34'
        ]);

        User::factory()->for($ownerRole)->create([
            'name' => 'Owner 5',
            'email' => 'owner5@app.com',
            'phone' => '333 333 33 35'
        ]);
    }
}
