<?php

namespace App\Filament\Resources\SegmentResource\Components;

use App\Filament\Resources\SegmentResource\Components\Fields\ConditionSelect;
use App\Filament\Resources\SegmentResource\Components\Fields\DateChoose;
use App\Filament\Resources\SegmentResource\Components\Fields\BasicConditionSelect;
use App\Filament\Resources\SegmentResource\Components\Fields\GenderSelect;
use App\Filament\Resources\SegmentResource\Components\Fields\TextConditionSelect;
use App\Filament\Resources\SegmentResource\Components\Fields\CurrencyInput;
use App\Filament\Resources\SegmentResource\Components\Fields\BirthdayDateSelect;
use App\Filament\Resources\SegmentResource\Components\Fields\ConditionDateSelect;
use App\Filament\Resources\SegmentResource\Components\Fields\BetweenDaysGrid;


use Filament\Forms\Components\{Fieldset, Select, Grid};

class AttributeConditionFieldset
{
    public static function make(): Fieldset
    {
        return Fieldset::make('attribute')
            ->label('Característica')
            ->visible(fn($get) => $get('type') === 'attribute')
            ->schema([

                Grid::make(4)
                    ->schema([
                        Select::make('attribute_id')
                            ->label(false)
                            ->options([
                                'name' => 'Nome',
                                'birthdate' => 'Data de nascimento',
                                'birthday' => 'Aniversário',
                                'gener' => 'Gênero',
                                'recipe' => 'Receita',
                                'ticket_medio' => 'Ticket médio',
                            ])
                            ->required()
                            ->searchable()
                            ->reactive(),

                        TextConditionSelect::make()
                            ->visible(fn($get) => $get('attribute_id') === 'name')
                            ->reactive(),

                        //falta o select de nomes

                        ConditionDateSelect::make()
                            ->visible(fn($get) => $get('attribute_id') === 'birthdate')
                            ->reactive(),

                        BasicConditionSelect::make()
                            ->visible(fn($get) => in_array($get('attribute_id'), ['birthday', 'gener']))
                            ->reactive(),

                        BirthdayDateSelect::make()
                            ->visible(fn($get) => in_array($get('attribute_id'), ['birthday']))
                            ->reactive(),

                        GenderSelect::make()
                            ->visible(fn($get) => $get('attribute_id') === 'gener')
                            ->reactive(),

                        ConditionSelect::make()
                            ->visible(fn($get) => in_array($get('attribute_id'), ['recipe', 'ticket_medio']))
                            ->reactive(),

                        CurrencyInput::make()
                            ->visible(fn($get) => in_array($get('attribute_id'), ['recipe', 'ticket_medio']))
                            ->reactive(),
                    ]),

                DateChoose::make()
                    ->visible(fn($get) => in_array($get('attribute_id'), ['birthdate']) && $get('condition') !== 'between_days'),

                BetweenDaysGrid::make()
                    ->visible(fn($get) => $get('condition') === 'between_days')

            ]);
    }
}
