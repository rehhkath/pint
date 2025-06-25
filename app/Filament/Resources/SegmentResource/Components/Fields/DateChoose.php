<?php

namespace App\Filament\Resources\SegmentResource\Components\Fields;

use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\DatePicker;

class DateChoose
{
    public static function make(): Grid
    {
        return Grid::make(4)
            ->schema([
                Toggle::make('data_type')
                    ->label('Data fixa')
                    ->default(false)
                    ->reactive(),

                TextInput::make('value')
                    ->label(false)
                    ->numeric()
                    ->placeholder('0')
                    ->reactive()
                    ->visible(fn($get) => $get('data_type') === false),

                Select::make('time_after')
                    ->label(false)
                    ->options([
                        'days' => 'Dias atrás',
                        'weeks' => 'Semanas atrás',
                        'months' => 'Meses atrás',
                        'years' => 'Anos atrás',
                    ])
                    ->default('days')
                    ->reactive()
                    ->native(false)
                    ->visible(fn($get) => $get('data_type') === false),

                DatePicker::make('date')
                    ->label(false)
                    ->prefixIcon('heroicon-o-calendar')
                    ->required()
                    ->reactive()
                    ->visible(fn($get) => $get('data_type') === true)
            ])
            ->columnSpan(2);
    }
}
