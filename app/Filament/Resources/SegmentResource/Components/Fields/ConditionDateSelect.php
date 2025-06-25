<?php

namespace App\Filament\Resources\SegmentResource\Components\Fields;

use Filament\Forms\Components\Select;

class ConditionDateSelect
{
    public static function make(): Select
    {
        return Select::make('condition')
            ->label(false)
            ->options([
                'equals' => 'Igual a',
                'not_equals' => 'Diferente de',
                'before' => 'Antes de',
                'before_or_equals' => 'Antes ou igual a',
                'after' => 'Depois de',
                'after_or_equals' => 'Depois ou igual a',
                'days_from_now' => 'Daqui a',
                'between_days' => 'Entre os dias',
            ])
            ->reactive()
            ->default('equals')
            ->searchable();
    }
}
