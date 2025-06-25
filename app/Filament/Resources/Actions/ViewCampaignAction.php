<?php

namespace App\Filament\Resources\Actions;

use App\Enums\ContactType;
use App\Enums\ConsumerSource;
use App\Enums\DistributionType;
use App\Models\Segment;
use App\Models\Behavior;
use Filament\Tables\Actions\ViewAction;
use Filament\Infolists\Components\Split;
use Filament\Infolists\Components\Section;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Components\RepeatableEntry;
use Filament\Support\Enums\FontWeight;
use App\Filament\Resources\SegmentResource;

class ViewCampaignAction
{
    public static function make(): ViewAction
    {
        return ViewAction::make('visualizar')
            ->label('Visualizar')
            ->slideOver()
            ->stickyModalHeader()
            ->modalCancelAction(false)
            ->infolist([
                Section::make([
                    TextEntry::make('name')
                        ->label('Nome da campanha')
                        ->weight(FontWeight::Bold),

                    Split::make([
                        TextEntry::make('distribution')
                            ->label('Contato realizado por')
                            ->formatStateUsing(fn($record) => $record->distribution === DistributionType::PREFERRED_SELLER
                                ? 'Vendedor de preferência'
                                : 'Vendedor que fez a última interação'),
                        TextEntry::make('consumer_source')
                            ->label(fn($record) => $record->consumer_source === ConsumerSource::SEGMENT
                                ? 'Segmento'
                                : 'Comportamento')
                            ->formatStateUsing(function ($record) {
                                if ($record->consumer_source === ConsumerSource::SEGMENT) {
                                    $segmentName = Segment::getNameById($record->segment_id);
                                    $url = SegmentResource::getUrl(name: 'edit', parameters: ['record' => $record->segment_id]);
                                    return '<a href="' . $url . '" class="text-primary-600 hover:underline" target="_blank">' . e($segmentName) . '</a>';
                                }

                                return Behavior::getNameById($record->behavior_done_id);
                            })
                            ->html(),
                    ]),

                    TextEntry::make('download_image')
                        ->label("Matarial de apoio")
                        ->default('<a href="' . asset('assets/images/live.png') . '" download class="text-primary-600 hover:underline">'
                            . pathinfo(asset('assets/images/live.png'), PATHINFO_BASENAME) . '</a>')
                        ->html(),
                ]),

                Section::make([
                    RepeatableEntry::make('messages')
                        ->label('Mensagens da campanha')
                        ->schema([
                            TextEntry::make('text')
                                ->label('Mensagem')
                                ->columnSpan(2),
                        ])
                        ->columns(1)
                ]),
            ]);
    }
}
