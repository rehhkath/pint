<?php

namespace App\Filament\Resources\SegmentResource\Components\Fields;

use Filament\Forms\Components\Grid;
use Filament\Forms\Components\DatePicker;

class BetweenDaysGrid
{
    public static function make(): Grid
    {
        return Grid::make(4)
            ->schema([
                DatePicker::make('first_date')
                    ->label('Data inicial')
                    ->placeholder('Data inicial')
                    ->default(fn() => now()->subDays(value: 30)),

                DatePicker::make('last_date')
                    ->label('Data final')
                    ->placeholder('Data final')
                    ->default(fn() => now()),
            ])
            ->reactive();
    }
}
