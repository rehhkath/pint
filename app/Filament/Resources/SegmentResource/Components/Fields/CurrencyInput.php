<?php

namespace App\Filament\Resources\SegmentResource\Components\Fields;

use Filament\Forms\Components\TextInput;

class CurrencyInput
{
    public static function make(): TextInput
    {
        return TextInput::make('text')
            ->label(false)
            ->prefix('R$')
            ->numeric()
            ->placeholder('Digite um valor')
            ->reactive()
            ->inputMode('decimal');
    }
}
