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
        // Get existing user emails
        $existingEmails = User::pluck('email')->toArray();

        // Define users to create
        $usersToCreate = [
            ['name' => 'Admin User', 'email' => 'admin@example.com', 'role' => Role::Admin],
            ['name' => 'John Instructor', 'email' => 'instructor1@example.com', 'role' => Role::Instructor],
            ['name' => 'Sarah Teacher', 'email' => 'instructor2@example.com', 'role' => Role::Instructor],
            ['name' => 'Michael Professor', 'email' => 'instructor3@example.com', 'role' => Role::Instructor],
            ['name' => 'Mike Moderator', 'email' => 'moderator1@example.com', 'role' => Role::Moderator],
            ['name' => 'Lisa Moderator', 'email' => 'moderator2@example.com', 'role' => Role::Moderator],
            ['name' => 'Alice Johnson', 'email' => 'alice.johnson@example.com', 'role' => Role::Student],
            ['name' => 'Bob Smith', 'email' => 'bob.smith@example.com', 'role' => Role::Student],
            ['name' => 'Charlie Brown', 'email' => 'charlie.brown@example.com', 'role' => Role::Student],
            ['name' => 'Diana Williams', 'email' => 'diana.williams@example.com', 'role' => Role::Student],
            ['name' => 'Eve Davis', 'email' => 'eve.davis@example.com', 'role' => Role::Student],
            ['name' => 'Frank Miller', 'email' => 'frank.miller@example.com', 'role' => Role::Student],
            ['name' => 'Grace Wilson', 'email' => 'grace.wilson@example.com', 'role' => Role::Student],
            ['name' => 'Henry Moore', 'email' => 'henry.moore@example.com', 'role' => Role::Student],
            ['name' => 'Ivy Taylor', 'email' => 'ivy.taylor@example.com', 'role' => Role::Student],
            ['name' => 'Jack Anderson', 'email' => 'jack.anderson@example.com', 'role' => Role::Student],
        ];

        // Create only non-existing users
        foreach ($usersToCreate as $userData) {
            if (! in_array($userData['email'], $existingEmails)) {
                $user = User::factory()->create([
                    'name' => $userData['name'],
                    'email' => $userData['email'],
                ]);
                $user->assignRole($userData['role']->value);
            }
        }

        // Create additional random students if total student count is less than 25
        $currentStudentCount = User::role(Role::Student->value)->count();
        $studentsToCreate = max(0, 25 - $currentStudentCount);

        if ($studentsToCreate > 0) {
            User::factory($studentsToCreate)->create()->each(function ($user) {
                $user->assignRole(Role::Student->value);
            });
        }
    }
}
