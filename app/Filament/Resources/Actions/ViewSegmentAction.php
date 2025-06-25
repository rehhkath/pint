<?php

namespace App\Filament\Resources\Actions;

use App\Models\Segment;
use Filament\Tables\Actions\ViewAction;
use Filament\Infolists\Components\Section;
use Filament\Infolists\Components\TextEntry;
use App\Filament\Resources\CampaignResource;

class ViewSegmentAction
{
    public static function make(): ViewAction
    {
        return ViewAction::make('visualizar')
            ->label('Visualizar')
            ->slideOver()
            ->stickyModalHeader()
            ->modalCancelAction(false)
            ->infolist(function ($record) {
                $data = Segment::getUsages($record);

                return [
                    // Section::make('Segmentos que usam este segmento')
                    //     ->schema(
                    //         collect($data['usedBySegments'])->map(
                    //             fn($segment) =>
                    //             TextEntry::make('segment_' . $segment['id'])
                    //                 ->label($segment['name'])
                    //         )->toArray()
                    //     )
                    //     ->columns(1),

                        Section::make('Campanhas que usam este segmento')
                        ->schema(
                            collect($data['usedByCampaigns'])->map(
                                fn($campaign) =>
                                TextEntry::make('campaign_' . $campaign['name'])
                                    ->label(false)
                                    ->default(
                                        '<a class="text-primary-600 hover:underline" href="' .
                                        CampaignResource::getUrl(name: 'edit', parameters: ['record' => $campaign['id']]) .
                                        '" target="_blank">' . $campaign['name'] . '</a>'
                                    )
                                    ->html(),

                            )->toArray()
                        )
                        ->columns(1)
                ];
            });
    }
}
