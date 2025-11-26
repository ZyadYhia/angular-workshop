<?php

namespace App\Enums;

enum Role: string
{
    case Admin = 'admin';
    case Instructor = 'instructor';
    case Student = 'student';
    case Moderator = 'moderator';

    /**
     * Get all role values as an array.
     *
     * @return array<string>
     */
    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }

    /**
     * Get the display name for the role.
     */
    public function label(): string
    {
        return match ($this) {
            self::Admin => 'Administrator',
            self::Instructor => 'Instructor',
            self::Student => 'Student',
            self::Moderator => 'Moderator',
        };
    }
}
