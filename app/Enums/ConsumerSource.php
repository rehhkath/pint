<?php

namespace App\Enums;

enum ConsumerSource: int
{
    case SEGMENT = 1;
    case BEHAVIOR = 2;
    
    public static function labels(): array
    {
        return [
            self::SEGMENT->value => 'Segmento',
            self::BEHAVIOR->value => 'Comportamento',
        ];
    }

    public static function label(int $value): string
    {
        return self::labels()[$value] ?? 'Unknown';
    }
}
