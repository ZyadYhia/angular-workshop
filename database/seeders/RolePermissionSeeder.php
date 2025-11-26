<?php

namespace Database\Seeders;

use App\Enums\Permission;
use App\Enums\Role;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission as PermissionModel;
use Spatie\Permission\Models\Role as RoleModel;

class RolePermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Reset cached roles and permissions
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // Create all permissions
        foreach (Permission::cases() as $permission) {
            PermissionModel::firstOrCreate(
                ['name' => $permission->value],
                ['name' => $permission->value]
            );
        }

        // Create roles and assign permissions
        $this->createAdminRole();
        $this->createInstructorRole();
        $this->createStudentRole();
        $this->createModeratorRole();
    }

    /**
     * Create admin role with all permissions.
     */
    protected function createAdminRole(): void
    {
        $admin = RoleModel::firstOrCreate(['name' => Role::Admin->value]);
        $admin->givePermissionTo(Permission::values());
    }

    /**
     * Create instructor role with exam and question management permissions.
     */
    protected function createInstructorRole(): void
    {
        $instructor = RoleModel::firstOrCreate(['name' => Role::Instructor->value]);

        $instructor->givePermissionTo([
            // Exam Management
            Permission::CreateExam->value,
            Permission::EditExam->value,
            Permission::DeleteExam->value,
            Permission::ViewExam->value,
            Permission::PublishExam->value,
            Permission::ArchiveExam->value,

            // Question Management
            Permission::CreateQuestion->value,
            Permission::EditQuestion->value,
            Permission::DeleteQuestion->value,
            Permission::ViewQuestion->value,
            Permission::ImportQuestions->value,
            Permission::ExportQuestions->value,

            // Results Management
            Permission::ViewAllResults->value,
            Permission::ViewStudentResults->value,
            Permission::GradeExam->value,
            Permission::ExportResults->value,

            // System
            Permission::ViewReports->value,
            Permission::ManageCategories->value,
        ]);
    }

    /**
     * Create student role with exam taking permissions.
     */
    protected function createStudentRole(): void
    {
        $student = RoleModel::firstOrCreate(['name' => Role::Student->value]);

        $student->givePermissionTo([
            // Exam Taking
            Permission::TakeExam->value,
            Permission::ViewOwnResults->value,
            Permission::RetakeExam->value,

            // View only
            Permission::ViewExam->value,
        ]);
    }

    /**
     * Create moderator role with user and content management permissions.
     */
    protected function createModeratorRole(): void
    {
        $moderator = RoleModel::firstOrCreate(['name' => Role::Moderator->value]);

        $moderator->givePermissionTo([
            // Exam Management
            Permission::ViewExam->value,
            Permission::PublishExam->value,
            Permission::ArchiveExam->value,

            // Question Management
            Permission::ViewQuestion->value,
            Permission::EditQuestion->value,

            // Results Management
            Permission::ViewAllResults->value,
            Permission::ViewStudentResults->value,

            // User Management
            Permission::ViewUsers->value,

            // System
            Permission::ViewReports->value,
            Permission::ManageCategories->value,
        ]);
    }
}
