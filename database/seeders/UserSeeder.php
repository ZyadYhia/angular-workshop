<?php

namespace Database\Seeders;

use App\Enums\Role;
use App\Models\User;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create Admin User
        $admin = User::factory()->create([
            'name' => 'Admin User',
            'email' => 'admin@example.com',
        ]);
        $admin->assignRole(Role::Admin->value);

        // Create Instructor Users
        $instructor1 = User::factory()->create([
            'name' => 'John Instructor',
            'email' => 'instructor1@example.com',
        ]);
        $instructor1->assignRole(Role::Instructor->value);

        $instructor2 = User::factory()->create([
            'name' => 'Sarah Teacher',
            'email' => 'instructor2@example.com',
        ]);
        $instructor2->assignRole(Role::Instructor->value);

        $instructor3 = User::factory()->create([
            'name' => 'Michael Professor',
            'email' => 'instructor3@example.com',
        ]);
        $instructor3->assignRole(Role::Instructor->value);

        // Create Moderator Users
        $moderator1 = User::factory()->create([
            'name' => 'Mike Moderator',
            'email' => 'moderator1@example.com',
        ]);
        $moderator1->assignRole(Role::Moderator->value);

        $moderator2 = User::factory()->create([
            'name' => 'Lisa Moderator',
            'email' => 'moderator2@example.com',
        ]);
        $moderator2->assignRole(Role::Moderator->value);

        // Create Named Student Users
        $students = [
            ['name' => 'Alice Johnson', 'email' => 'alice.johnson@example.com'],
            ['name' => 'Bob Smith', 'email' => 'bob.smith@example.com'],
            ['name' => 'Charlie Brown', 'email' => 'charlie.brown@example.com'],
            ['name' => 'Diana Williams', 'email' => 'diana.williams@example.com'],
            ['name' => 'Eve Davis', 'email' => 'eve.davis@example.com'],
            ['name' => 'Frank Miller', 'email' => 'frank.miller@example.com'],
            ['name' => 'Grace Wilson', 'email' => 'grace.wilson@example.com'],
            ['name' => 'Henry Moore', 'email' => 'henry.moore@example.com'],
            ['name' => 'Ivy Taylor', 'email' => 'ivy.taylor@example.com'],
            ['name' => 'Jack Anderson', 'email' => 'jack.anderson@example.com'],
        ];

        foreach ($students as $studentData) {
            $student = User::factory()->create($studentData);
            $student->assignRole(Role::Student->value);
        }

        // Create additional random students
        User::factory(15)->create()->each(function ($user) {
            $user->assignRole(Role::Student->value);
        });
    }
}
