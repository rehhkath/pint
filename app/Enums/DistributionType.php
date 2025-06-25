<?php

namespace App\Enums;

enum DistributionType: int
{
    case PREFERRED_SELLER = 1;
    case LAST_INTERACTION = 2;

    public static function labels(): array
    {
        return [
            self::PREFERRED_SELLER->value => 'Vendedor de preferência',
            self::LAST_INTERACTION->value => 'Vendedor que realizou a última interação',
        ];
    }


    public static function label(int $value): string
    {
        return self::labels()[$value] ?? 'Unknown';
    }
}
