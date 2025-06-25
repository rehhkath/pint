<?php

namespace App\Filament\Resources\SegmentResource\Components\Fields;

use Filament\Forms\Components\Select;

class BasicConditionSelect
{
    public static function make(): Select
    {
        return Select::make('condition')
            ->label(false)
            ->options([
                'equals' => 'Igual a',
                'not_equals' => 'Diferente de',
            ])
            ->default('equals')
            ->visible(fn($get) => in_array($get('attribute_id'), ['birthday', 'gener']))
            ->required()
            ->reactive()
            ->searchable();
    }
}
