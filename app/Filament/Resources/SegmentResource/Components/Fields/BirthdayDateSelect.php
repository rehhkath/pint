<?php

namespace App\Filament\Resources\SegmentResource\Components\Fields;

use Filament\Forms\Components\Select;

class BirthdayDateSelect
{
    public static function make(): Select
    {
        return Select::make('value')
            ->label(false)
            ->options([
                'today' => 'Hoje',
                'tomorrow' => 'Amanhã',
                'days_ago_1' => '1 dia atrás',
                'days_ago_3' => '3 dias atrás',
                'days_ago_7' => '7 dias atrás',
                'days_from_now_3' => 'Daqui a 3 dias',
                'days_from_now_7' => 'Daqui a 7 dias',
                'this_week' => 'Esta semana',
                'last_month' => 'Mês passado',
                'this_month' => 'Este mês',
                'next_month' => 'Mês que vem',
            ])
            ->reactive()
            ->searchable();
    }
}
