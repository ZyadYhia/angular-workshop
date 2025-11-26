<?php

namespace App\Enums;

enum Permission: string
{
    // Exam Management
    case CreateExam = 'create-exam';
    case EditExam = 'edit-exam';
    case DeleteExam = 'delete-exam';
    case ViewExam = 'view-exam';
    case PublishExam = 'publish-exam';
    case ArchiveExam = 'archive-exam';

        // Question Management
    case CreateQuestion = 'create-question';
    case EditQuestion = 'edit-question';
    case DeleteQuestion = 'delete-question';
    case ViewQuestion = 'view-question';
    case ImportQuestions = 'import-questions';
    case ExportQuestions = 'export-questions';

        // Exam Taking
    case TakeExam = 'take-exam';
    case ViewOwnResults = 'view-own-results';
    case RetakeExam = 'retake-exam';

        // Results Management
    case ViewAllResults = 'view-all-results';
    case ViewStudentResults = 'view-student-results';
    case GradeExam = 'grade-exam';
    case ExportResults = 'export-results';

        // User Management
    case ManageUsers = 'manage-users';
    case ViewUsers = 'view-users';
    case AssignRoles = 'assign-roles';

        // System Settings
    case ManageSettings = 'manage-settings';
    case ViewReports = 'view-reports';
    case ManageCategories = 'manage-categories';

    /**
     * Get all permission values as an array.
     *
     * @return array<string>
     */
    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }

    /**
     * Get the display name for the permission.
     */
    public function label(): string
    {
        return match ($this) {
            self::CreateExam => 'Create Exam',
            self::EditExam => 'Edit Exam',
            self::DeleteExam => 'Delete Exam',
            self::ViewExam => 'View Exam',
            self::PublishExam => 'Publish Exam',
            self::ArchiveExam => 'Archive Exam',
            self::CreateQuestion => 'Create Question',
            self::EditQuestion => 'Edit Question',
            self::DeleteQuestion => 'Delete Question',
            self::ViewQuestion => 'View Question',
            self::ImportQuestions => 'Import Questions',
            self::ExportQuestions => 'Export Questions',
            self::TakeExam => 'Take Exam',
            self::ViewOwnResults => 'View Own Results',
            self::RetakeExam => 'Retake Exam',
            self::ViewAllResults => 'View All Results',
            self::ViewStudentResults => 'View Student Results',
            self::GradeExam => 'Grade Exam',
            self::ExportResults => 'Export Results',
            self::ManageUsers => 'Manage Users',
            self::ViewUsers => 'View Users',
            self::AssignRoles => 'Assign Roles',
            self::ManageSettings => 'Manage Settings',
            self::ViewReports => 'View Reports',
            self::ManageCategories => 'Manage Categories',
        };
    }

    /**
     * Get the permission description.
     */
    public function description(): string
    {
        return match ($this) {
            self::CreateExam => 'Create new exams',
            self::EditExam => 'Edit existing exams',
            self::DeleteExam => 'Delete exams',
            self::ViewExam => 'View exam details',
            self::PublishExam => 'Publish exams to students',
            self::ArchiveExam => 'Archive old exams',
            self::CreateQuestion => 'Create new questions',
            self::EditQuestion => 'Edit existing questions',
            self::DeleteQuestion => 'Delete questions',
            self::ViewQuestion => 'View question details',
            self::ImportQuestions => 'Import questions from files',
            self::ExportQuestions => 'Export questions to files',
            self::TakeExam => 'Take available exams',
            self::ViewOwnResults => 'View own exam results',
            self::RetakeExam => 'Retake exams if allowed',
            self::ViewAllResults => 'View all student results',
            self::ViewStudentResults => 'View results of assigned students',
            self::GradeExam => 'Grade exam submissions',
            self::ExportResults => 'Export exam results',
            self::ManageUsers => 'Create, edit, and delete users',
            self::ViewUsers => 'View user list',
            self::AssignRoles => 'Assign roles to users',
            self::ManageSettings => 'Manage system settings',
            self::ViewReports => 'View system reports',
            self::ManageCategories => 'Manage question categories',
        };
    }

    /**
     * Get permissions grouped by category.
     *
     * @return array<string, array<self>>
     */
    public static function grouped(): array
    {
        return [
            'Exam Management' => [
                self::CreateExam,
                self::EditExam,
                self::DeleteExam,
                self::ViewExam,
                self::PublishExam,
                self::ArchiveExam,
            ],
            'Question Management' => [
                self::CreateQuestion,
                self::EditQuestion,
                self::DeleteQuestion,
                self::ViewQuestion,
                self::ImportQuestions,
                self::ExportQuestions,
            ],
            'Exam Taking' => [
                self::TakeExam,
                self::ViewOwnResults,
                self::RetakeExam,
            ],
            'Results Management' => [
                self::ViewAllResults,
                self::ViewStudentResults,
                self::GradeExam,
                self::ExportResults,
            ],
            'User Management' => [
                self::ManageUsers,
                self::ViewUsers,
                self::AssignRoles,
            ],
            'System' => [
                self::ManageSettings,
                self::ViewReports,
                self::ManageCategories,
            ],
        ];
    }
}
