<?php

namespace Database\Seeders;

use App\Models\Company;
use App\Models\User;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create an Admin user
        $admin = User::factory()->admin()->create();
        $admin->assignRole('Admin');

        // Create regular users
        $users = User::factory(10)->create();
        foreach ($users as $user) {
            $user->assignRole('User');

            // Attach the user to 1-2 random companies
            $user->companies()->attach(
                Company::inRandomOrder()->take(rand(1, 2))->pluck('id')->toArray()
            );
        }
    }
}
