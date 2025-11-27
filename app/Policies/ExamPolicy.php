<?php

namespace App\Policies;

use App\Enums\Permission;
use App\Models\Exam;
use App\Models\User;

class ExamPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(?User $user): bool
    {
        return true;
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(?User $user, Exam $exam): bool
    {
        if (! $user) {
            return true;
        }

        return $user->can(Permission::ViewExam->value);
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->can(Permission::CreateExam->value);
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Exam $exam): bool
    {
        return $user->can(Permission::EditExam->value);
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Exam $exam): bool
    {
        return $user->can(Permission::DeleteExam->value);
    }

    /**
     * Determine whether the user can publish the exam.
     */
    public function publish(User $user, Exam $exam): bool
    {
        return $user->can(Permission::PublishExam->value);
    }

    /**
     * Determine whether the user can archive the exam.
     */
    public function archive(User $user, Exam $exam): bool
    {
        return $user->can(Permission::ArchiveExam->value);
    }

    /**
     * Determine whether the user can take the exam.
     */
    public function take(User $user, Exam $exam): bool
    {
        return $user->can(Permission::TakeExam->value) && $exam->active;
    }

    /**
     * Determine whether the user can view questions.
     */
    public function viewQuestions(User $user, Exam $exam): bool
    {
        return $user->can(Permission::TakeExam->value) ||
            $user->can(Permission::ViewQuestion->value);
    }
}
