<?php

namespace App\Filament\Resources\SegmentResource\Components\Fields;

use Filament\Forms\Components\Toggle;

class OperatorToggle
{
    public static function make(): Toggle
    {
        return Toggle::make('operator')
            ->label('Fizeram')
            ->default(true)
            ->reactive()
            ->onColor('success')
            ->offColor('danger');
    }
}
