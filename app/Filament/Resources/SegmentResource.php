<?php

namespace App\Filament\Resources;

use App\Filament\Resources\SegmentResource\Pages;
use App\Models\Segment;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn;
use App\Filament\Resources\Actions\ViewSegmentAction;
use App\Filament\Resources\SegmentResource\Components\AttributeConditionFieldset;
use App\Filament\Resources\SegmentResource\Components\SegmentConditionFieldset;
use App\Filament\Resources\SegmentResource\Components\BehaviorConditionFieldset;
use App\Filament\Resources\SegmentResource\Components\PreferenceTypeFieldset;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Enums\FiltersLayout;
use Filament\Tables\Columns\ToggleColumn;
use Filament\Forms\Components\{Wizard, DatePicker, Field, Notification, Fieldset, Grid, Placeholder, Radio, Select, Tabs, TextInput, Textarea, FileUpload, ViewField, Repeater, Modal};

class SegmentResource extends Resource
{
    protected static ?string $model = Segment::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static ?string $navigationLabel = 'Segmentos';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Grid::make(1)
                    ->schema([
                        TextInput::make('name')
                            ->label('Nome do segmento')
                            ->required()
                            ->maxLength(255),
                    ]),

                Repeater::make('group')
                    ->label(false)
                    ->schema([
                        Select::make('filter_type')
                            ->label(false)
                            ->options([
                                'behavior' => 'Comportamento',
                                'segment' => 'Segmento',
                                'attribute' => 'Característica',
                                'preference_type' => 'Tipo de preferência',
                            ])
                            ->reactive(),

                        AttributeConditionFieldset::make()
                            ->reactive()
                            ->visible(fn($get) => $get('filter_type') === 'attribute'),

                        SegmentConditionFieldset::make()
                            ->reactive()
                            ->visible(fn($get) => $get('filter_type') === 'segment'),

                        BehaviorConditionFieldset::make()
                            ->reactive()
                            ->visible(fn($get) => $get('filter_type') === 'behavior'),

                        PreferenceTypeFieldset::make()
                            ->reactive()
                            ->visible(fn($get) => $get('filter_type') === 'preference_type'),

                        Repeater::make('or')
                            ->label(false)
                            ->schema([

                                Select::make('filter_type')
                                    ->label(false)
                                    ->options([
                                        'behavior' => 'Comportamento',
                                        'segment' => 'Segmento',
                                        'attribute' => 'Característica',
                                        'preference_type' => 'Tipo de preferência',
                                    ])
                                    ->reactive(),

                                AttributeConditionFieldset::make()
                                    ->reactive()
                                    ->visible(fn($get) => $get('filter_type') === 'attribute'),

                                SegmentConditionFieldset::make()
                                    ->reactive()
                                    ->visible(fn($get) => $get('filter_type') === 'segment'),

                                BehaviorConditionFieldset::make()
                                    ->reactive()
                                    ->visible(fn($get) => $get('filter_type') === 'behavior'),

                                PreferenceTypeFieldset::make()
                                    ->reactive()
                                    ->visible(fn($get) => $get('filter_type') === 'preference_type'),

                            ])->columns(1)
                            ->visible(fn($get) => $get('filter_type') !== null)
                            ->defaultItems(0)
                            ->columnSpanFull()
                            ->cloneable()
                            ->collapsible()
                            ->reactive()
                            ->addActionLabel("ou")
                            ->itemLabel('ou'),                            
                    ])
                    ->defaultItems(1)
                    ->columnSpan(1)
                    ->cloneable()
                    ->collapsible()
                    ->reactive()
                    ->orderColumn('group')
                    ->addActionLabel("e")
                    ->itemLabel( 'e'),

            ])->columns(1);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->label('Nome'),

                    ToggleColumn::make('status')
                    ->label('Status')
                    ->onIcon('heroicon-o-check-circle')
                    ->offIcon('heroicon-o-x-circle')
                    ->onColor('success')
                    ->offColor('danger')
                    ->afterStateUpdated(function ($state, $record) {
                        $record->update([
                            'status' => $state,
                            'updated_at' => now(),
                        ]);
                    }),

                TextColumn::make('created_at')
                    ->label('Criado em')
                    ->date('d/m/Y'),

                TextColumn::make('updated_at')
                    ->label('Última edição')
                    ->date('d/m/Y'),
            ])
            ->filters([
                Filter::make('name')
                    ->label('Nome')
                    ->form([
                        Forms\Components\TextInput::make('name')
                            ->label('Nome')
                            ->placeholder('Digite o nome'),
                    ])
                    ->query(fn($query, $data) => $query->where('name', 'like', '%' . $data['name'] . '%')),

                Filter::make('status')
                    ->label('Status')
                    ->form([
                        Forms\Components\Select::make('value')
                            ->label('Status')
                            ->options([
                                '' => 'Todos',
                                1 => 'Ativo',
                                0 => 'Inativo',
                            ])
                            ->default(1),
                    ])
                    ->query(function ($query, array $data) {
                        if (!filled($data['value'])) {
                            return $query;
                        }

                        return $query->where('status', $data['value']);
                    }),
            ], layout: FiltersLayout::AboveContent)
            ->actions([
                ViewSegmentAction::make()
                    ->label(false)
                    ->icon('heroicon-o-eye')
                    ->tooltip('Visualizar segmento'),

                Tables\Actions\DeleteAction::make()
                    ->label(false)
                    ->color('danger')
                    ->requiresConfirmation()
                    ->icon('heroicon-o-trash')
                    ->tooltip('Excluir segmento'),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListSegments::route('/'),
            'create' => Pages\CreateSegment::route('/create'),
            'edit' => Pages\EditSegment::route('/{record}/edit'),
        ];
    }
}
