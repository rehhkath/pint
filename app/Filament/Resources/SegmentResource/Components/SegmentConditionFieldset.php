<?php

namespace App\Filament\Resources\SegmentResource\Components;

use App\Models\Segment;
use Filament\Forms\Components\Fieldset;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Toggle;

class SegmentConditionFieldset
{
    public static function make(): Fieldset
    {
        return Fieldset::make('segment')
            ->label('Segmento')
            ->visible(fn($get) => $get('type') === 'segment')
            ->schema([
                Toggle::make('operator')
                    ->label('Faz parte')
                    ->default(true)
                    ->inline(true)
                    ->columnSpanFull(),

                Select::make('segment_id')
                    ->label(false)
                    ->options(fn () => Segment::query()->pluck('name', 'id'))
                    ->required()
                    ->searchable(),
            ]);
    }
}
