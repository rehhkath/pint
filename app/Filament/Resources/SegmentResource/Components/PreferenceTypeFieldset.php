<?php

namespace App\Filament\Resources\SegmentResource\Components;

use Filament\Forms\Components\Fieldset;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Placeholder;
use Filament\Forms\Components\Select;

class PreferenceTypeFieldset
{
    public static function make(): Fieldset
    {
        return Fieldset::make('preference_type')
            ->label('Tipo de preferência')
            ->visible(fn($get) => $get('type') === 'preference_type')
            ->schema([
                Grid::make(3)
                    ->schema([
                        Select::make('preference_type_id')
                            ->label(false)
                            ->reactive()
                            ->required()
                            ->searchable()
                            ->options([
                                'preferred_seller' => 'Vendedor de preferência',
                                'preferred_store' => 'Loja de preferência',
                            ]),

                        Select::make('stores')
                            ->label(false)
                            ->reactive()
                            ->visible(fn($get) => $get('preference_type_id') === 'preferred_store')
                            ->options([
                                'own_store' => 'Loja própria',
                                'studio' => 'Estúdio',
                                'franchise' => 'Franquia',
                                'cnpj' => 'CNPJ',
                            ]),

                        Select::make('cnpj')
                            ->label(false)
                            ->visible(fn($get) => $get('stores') === 'cnpj')
                            ->multiple()
                            ->reactive()
                            ->options([
                                '12345678000195' => '12345678000195',
                                '98765432000198' => '98765432000198',
                                '11223344000111' => '11223344000111',
                                '12345678901234' => '12345678901234',
                                '98765432109876' => '98765432109876',
                                '12345678901235' => '12345678901235',
                                '12345678901236' => '12345678901236',
                                '12345678901237' => '12345678901237',
                                '12345678901238' => '12345678901238',
                            ]),

                        Select::make('sellers')
                            ->label(false)
                            ->visible(fn($get) => $get('preference_type_id') === 'preferred_seller')
                            ->multiple()
                            ->reactive()
                            ->options([
                                '123456' => '123456',
                                '654321' => '654321',
                                '789012' => '789012',
                                '345678' => '345678',
                                '901234' => '901234',
                                '567890' => '567890',
                                '234567' => '234567',
                                '890123' => '890123',
                            ]),

                    ]),
            ]);
    }
}
