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

        // Load existing permissions or create new ones
        $this->createOrLoadPermissions();

        // Create roles and assign permissions
        $this->createAdminRole();
        $this->createInstructorRole();
        $this->createStudentRole();
        $this->createModeratorRole();
    }

    /**
     * Create or load all permissions into cache.
     */
    protected function createOrLoadPermissions(): void
    {
        $existingPermissions = PermissionModel::where('guard_name', 'api')
            ->pluck('id', 'name')
            ->toArray();

        $permissionsToCreate = [];

        foreach (Permission::cases() as $permission) {
            if (! isset($existingPermissions[$permission->value])) {
                $permissionsToCreate[] = [
                    'name' => $permission->value,
                    'guard_name' => 'api',
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
            }
        }

        // Batch insert new permissions
        if (! empty($permissionsToCreate)) {
            PermissionModel::insert($permissionsToCreate);
        }
    }

    /**
     * Create admin role with all permissions.
     */
    protected function createAdminRole(): void
    {
        $admin = RoleModel::firstOrCreate(['name' => Role::Admin->value, 'guard_name' => 'api']);
        $admin->givePermissionTo(Permission::values());
    }

    /**
     * Create instructor role with exam and question management permissions.
     */
    protected function createInstructorRole(): void
    {
        $instructor = RoleModel::firstOrCreate(['name' => Role::Instructor->value, 'guard_name' => 'api']);

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
        $student = RoleModel::firstOrCreate(['name' => Role::Student->value, 'guard_name' => 'api']);

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
        $moderator = RoleModel::firstOrCreate(['name' => Role::Moderator->value, 'guard_name' => 'api']);

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
