<?php

namespace App\Filament\Resources\SegmentResource\Components\Fields;

use Filament\Forms\Components\Select;

class TextConditionSelect
{
    public static function make(): Select
    {
        return Select::make('condition')
            ->label(false)
            ->options([
                'equals' => 'Igual a',
                'not_equals' => 'Diferente de',
                'contains' => 'Contém',
                'not_contains' => 'Não contém',
            ])
            ->default('equals')
            ->reactive()
            ->visible(fn($get) => $get('attribute_id') === 'name');
    }
}
