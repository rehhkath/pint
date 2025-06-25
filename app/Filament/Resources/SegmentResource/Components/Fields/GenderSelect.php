<?php

namespace App\Filament\Resources\SegmentResource\Components\Fields;

use Filament\Forms\Components\Select;

class GenderSelect
{
    public static function make(): Select
    {
        return Select::make('value')
            ->label(false)
            ->options([
                'female' => 'Feminino',
                'male' => 'Masculino',
            ])
            ->reactive()
            ->required()
            ->searchable();
    }
}
