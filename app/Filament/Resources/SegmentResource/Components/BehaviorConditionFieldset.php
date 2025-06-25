<?php

namespace App\Filament\Resources\SegmentResource\Components;

use Filament\Forms\Components\Fieldset;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use App\Filament\Resources\SegmentResource\Components\Fields\OperatorToggle;
use App\Filament\Resources\SegmentResource\Components\Fields\ConditionSelect;
use App\Filament\Resources\SegmentResource\Components\Fields\ConditionDateSelect;
use App\Filament\Resources\SegmentResource\Components\Fields\CurrencyInput;
use App\Filament\Resources\SegmentResource\Components\Fields\DateChoose;
use App\Filament\Resources\SegmentResource\Components\Fields\BetweenDaysGrid;
use Filament\Forms\Components\Grid;

class BehaviorConditionFieldset
{
    public static function make(): Fieldset
    {
        return Fieldset::make('behavior')
            ->label('Comportamento')
            ->visible(fn($get) => $get('type') === 'behavior')
            ->schema([
                Grid::make(4)
                    ->schema([
                        OperatorToggle::make(),

                        Select::make('event_type')
                            ->label(false)
                            ->options([
                                'purchased' => 'Comprou',
                                'returned' => 'Devolveu',
                                'exchanged' => 'Trocou',
                                'cashback_redeemed' => 'Resgatou cashback',
                            ])
                            ->reactive()
                            ->searchable(),

                        Select::make('product')
                            ->label(false)
                            ->options([
                                'top' => 'Top',
                                'blouse' => 'Blusa',
                                'pants' => 'Calça',
                                'shorts' => 'Shorts',
                                'leggings' => 'Legging',
                            ])
                            ->placeholder('Produto')
                            ->searchable()
                            ->visible(fn($get) => !is_null($get('event_type')))
                            ->reactive(),

                        Select::make('size')
                            ->label(false)
                            ->options([
                                'pp' => 'PP',
                                'p' => 'P',
                                'm' => 'M',
                                'g' => 'G',
                                'gg' => 'GG',
                            ])
                            ->placeholder('Tamanho')
                            ->searchable()
                            ->visible(fn($get) => !is_null($get('event_type')))
                            ->reactive(),
                    ]),

                Grid::make(4)
                    ->schema([
                        Select::make('property')
                            ->label(false)
                            ->options([
                                'purchase_date' => 'Data de compra',
                                'revenue' => 'Receita',
                                'quantity' => 'Quantidade',
                                'first_time' => 'Primeira vez',
                                'last_time' => 'Última vez',
                            ])
                            ->placeholder('Propriedade')
                            ->searchable()
                            ->reactive(),

                        ConditionSelect::make()
                            ->visible(fn($get) => in_array($get('property'), ['revenue', 'quantity'])),

                        CurrencyInput::make()
                            ->visible(fn($get) => in_array($get('property'), ['revenue'])),

                        TextInput::make('text')
                            ->label(false)
                            ->numeric()
                            ->placeholder('Digite um valor')
                            ->visible(fn($get) => in_array($get('property'), ['quantity']))
                            ->reactive(),

                        ConditionDateSelect::make()
                            ->visible(fn($get) => in_array($get('property'), ['purchase_date', 'first_time', 'last_time'])),

                    ]),

                DateChoose::make()
                    ->reactive()
                    ->visible(fn($get) => in_array($get('property'), ['purchase_date', 'first_time', 'last_time']) && $get('condition') !== 'between_days'),

                BetweenDaysGrid::make()
                    ->visible(fn($get) => $get('condition') === 'between_days')

            ])->extraAttributes(['class' => 'items-end !important;'])
        ;
    }
}
