<?php

namespace App\Enums;

enum ContactType : int
{
    case UNIQUE = 1;
    case MULTIPLE = 2;
    case VALIDITY = 3;

    public static function labels(): array
    {
        return [
            self::UNIQUE->value => 'Único',
            self::MULTIPLE->value => 'Multiplos',
            self::VALIDITY->value => 'Validade',
        ];
    }

    public static function label(int $value): string
    {
        return self::labels()[$value] ?? 'Unknown';
    }
}
