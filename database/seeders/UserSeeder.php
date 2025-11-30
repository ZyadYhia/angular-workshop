<?php

namespace Database\Seeders;

use App\Enums\Role;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

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
            ['name' => 'Admin User', 'username' => 'admin', 'email' => 'admin@example.com', 'phone_number' => '+1234567890', 'role' => Role::Admin],
            ['name' => 'John Instructor', 'username' => 'john.instructor', 'email' => 'instructor1@example.com', 'phone_number' => '+1234567891', 'role' => Role::Instructor],
            ['name' => 'Sarah Teacher', 'username' => 'sarah.teacher', 'email' => 'instructor2@example.com', 'phone_number' => '+1234567892', 'role' => Role::Instructor],
            ['name' => 'Michael Professor', 'username' => 'michael.professor', 'email' => 'instructor3@example.com', 'phone_number' => '+1234567893', 'role' => Role::Instructor],
            ['name' => 'Mike Moderator', 'username' => 'mike.moderator', 'email' => 'moderator1@example.com', 'phone_number' => '+1234567894', 'role' => Role::Moderator],
            ['name' => 'Lisa Moderator', 'username' => 'lisa.moderator', 'email' => 'moderator2@example.com', 'phone_number' => '+1234567895', 'role' => Role::Moderator],
            ['name' => 'Alice Johnson', 'username' => 'alice.johnson', 'email' => 'alice.johnson@example.com', 'phone_number' => '+1234567896', 'role' => Role::Student],
            ['name' => 'Bob Smith', 'username' => 'bob.smith', 'email' => 'bob.smith@example.com', 'phone_number' => '+1234567897', 'role' => Role::Student],
            ['name' => 'Charlie Brown', 'username' => 'charlie.brown', 'email' => 'charlie.brown@example.com', 'phone_number' => '+1234567898', 'role' => Role::Student],
            ['name' => 'Diana Williams', 'username' => 'diana.williams', 'email' => 'diana.williams@example.com', 'phone_number' => '+1234567899', 'role' => Role::Student],
            ['name' => 'Eve Davis', 'username' => 'eve.davis', 'email' => 'eve.davis@example.com', 'phone_number' => '+1234567800', 'role' => Role::Student],
            ['name' => 'Frank Miller', 'username' => 'frank.miller', 'email' => 'frank.miller@example.com', 'phone_number' => '+1234567801', 'role' => Role::Student],
            ['name' => 'Grace Wilson', 'username' => 'grace.wilson', 'email' => 'grace.wilson@example.com', 'phone_number' => '+1234567802', 'role' => Role::Student],
            ['name' => 'Henry Moore', 'username' => 'henry.moore', 'email' => 'henry.moore@example.com', 'phone_number' => '+1234567803', 'role' => Role::Student],
            ['name' => 'Ivy Taylor', 'username' => 'ivy.taylor', 'email' => 'ivy.taylor@example.com', 'phone_number' => '+1234567804', 'role' => Role::Student],
            ['name' => 'Jack Anderson', 'username' => 'jack.anderson', 'email' => 'jack.anderson@example.com', 'phone_number' => '+1234567805', 'role' => Role::Student],
        ];

        // Create only non-existing users
        foreach ($usersToCreate as $userData) {
            if (! in_array($userData['email'], $existingEmails)) {
                $user = User::create([
                    'name' => $userData['name'],
                    'username' => $userData['username'],
                    'email' => $userData['email'],
                    'phone_number' => $userData['phone_number'],
                    'email_verified_at' => now(),
                    'password' => Hash::make('password'),
                    'remember_token' => Str::random(10),
                ]);
                $user->assignRole($userData['role']->value);
            }
        }

        // Create additional random students if total student count is less than 25
        $currentStudentCount = User::role(Role::Student->value)->count();
        $studentsToCreate = max(0, 25 - $currentStudentCount);

        if ($studentsToCreate > 0) {
            for ($i = 1; $i <= $studentsToCreate; $i++) {
                $user = User::create([
                    'name' => 'Student ' . ($currentStudentCount + $i),
                    'username' => 'student' . ($currentStudentCount + $i),
                    'email' => 'student' . ($currentStudentCount + $i) . '@example.com',
                    'phone_number' => '+1234' . str_pad($currentStudentCount + $i, 6, '0', STR_PAD_LEFT),
                    'email_verified_at' => now(),
                    'password' => Hash::make('password'),
                    'remember_token' => Str::random(10),
                ]);
                $user->assignRole(Role::Student->value);
            }
        }
    }
}
