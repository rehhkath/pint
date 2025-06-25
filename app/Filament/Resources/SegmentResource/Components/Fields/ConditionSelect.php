<?php

namespace App\Filament\Resources\SegmentResource\Components\Fields;

use Filament\Forms\Components\Select;

class ConditionSelect
{
    public static function make(): Select
    {
        return Select::make('condition')
            ->label(false)
            ->options([
                'equals' => 'Igual a',
                'not_equals' => 'Diferente de',
                'greater_than' => 'Maior que',
                'greater_or_equals' => 'Maior ou igual a',
                'less_than' => 'Menor que',
                'less_or_equals' => 'Menor ou igual a',
            ])
            ->default('equals')
            ->searchable()
            ->reactive()
            ->reactive();
    }
}
