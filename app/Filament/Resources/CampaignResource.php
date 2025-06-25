<?php

namespace App\Filament\Resources;

use Illuminate\Support\Facades\Log;
use App\Enums\ContactType;
use App\Enums\ConsumerSource;
use App\Enums\DistributionType;
use App\Filament\Resources\CampaignResource\Pages;
use App\Models\Campaign;
use App\Models\Segment;
use App\Models\Behavior;
use Filament\Forms\Components\{Wizard, DatePicker, Notification, Fieldset, Grid, Placeholder, Radio, Select, Tabs, TextInput, Toggle, Textarea, FileUpload, ViewField, Repeater, Modal};
use Filament\Forms\Components\Tabs\Tab;
use Filament\Forms\Form;
use Filament\Forms\Get;
use Filament\Resources\Resource;
use Carbon\Carbon;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\Filter;
use Illuminate\Database\Eloquent\Builder;
use Filament\Tables\Columns\ToggleColumn;
use Filament\Tables\Enums\FiltersLayout;
use App\Filament\Resources\Actions\ViewCampaignAction;
use App\Filament\Resources\Actions\ViewOldMessages;

use Filament\Forms\Components\Actions;



class CampaignResource extends Resource
{
    protected static ?string $model = Campaign::class;
    protected static ?string $navigationIcon = 'heroicon-o-megaphone';
    protected static ?string $navigationLabel = 'Campanhas';
    protected static ?string $title = 'Campanhas';

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->with(['messages']);
    }
    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Wizard::make()
                    ->skippable()
                    ->steps([
                        Wizard\Step::make('Distribuição e Consumidores')
                            ->schema([
                                Fieldset::make('Distribuição da campanha')
                                    ->schema([
                                        Radio::make('distribution')
                                            ->label(false)
                                            ->options(DistributionType::labels())
                                            ->default(DistributionType::PREFERRED_SELLER->value)
                                            ->required(),
                                    ]),

                                Fieldset::make('Fonte de consumidores')
                                    ->schema([
                                        Radio::make('consumer_source')
                                            ->label(false)
                                            ->options(ConsumerSource::labels())
                                            ->default(1)
                                            ->reactive()
                                            ->required(),
                                        Grid::make(1)
                                            ->schema([
                                                Select::make('segment_id')
                                                    ->label('Selecione um segmento')
                                                    ->options(
                                                        Segment::getAllOpt()->pluck('name', 'id')
                                                    )
                                                    ->visible(fn(Get $get) => $get('consumer_source') != ConsumerSource::BEHAVIOR->value)
                                                    ->required(),
                                            ]),

                                        Grid::make(2)
                                            ->schema([
                                                Fieldset::make('Consumidores que realizarem o comportamento:')
                                                    ->schema([
                                                        Select::make('behavior_done_id')
                                                            ->label(false)
                                                            ->options(
                                                                Behavior::getAllOpt()->pluck('name', 'id')
                                                            )
                                                            ->required(),
                                                    ])
                                                    ->visible(fn(Get $get) => $get('consumer_source') != ConsumerSource::SEGMENT->value)
                                                    ->columnSpan(1),

                                                Fieldset::make('Não enviar se realizaram o comportamento')
                                                    ->schema([
                                                        Select::make('behavior_not_done_id')
                                                            ->label(false)
                                                            ->options(
                                                                Behavior::getAllOpt()->pluck('name', 'id')
                                                            )
                                                    ])
                                                    ->columnSpan(1)
                                                    ->visible(fn(Get $get) => $get('consumer_source') != ConsumerSource::SEGMENT->value),
                                            ]),
                                    ]),
                            ]),

                        Wizard\Step::make('Mensagens')
                            ->schema([
                                Actions::make([
                                    ViewOldMessages::make(),
                                ]),

                                Fieldset::make('Mensagens da campanha')
                                    ->schema([
                                        Repeater::make('messages')
                                            ->relationship('messages')
                                            ->label('Mensagens da campanha')
                                            ->schema([
                                                Textarea::make('text')
                                                    ->label('Mensagem')
                                                    ->maxLength(560)
                                                    ->helperText(fn($state) => "Caracteres: " . strlen($state) . " / 560")
                                                    ->rows(7)
                                                    ->reactive()
                                                    ->columnSpanFull()
                                                    ->required(),

                                                Fieldset::make('Adicionar Variáveis')
                                                    ->schema([
                                                        ViewField::make('variable_button')
                                                            ->view(str('filament.buttons.variables'))
                                                            ->columnSpanFull(),
                                                    ]),
                                            ])
                                            ->defaultItems(2)
                                            ->minItems(2)
                                            ->maxItems(5)
                                            ->collapsible()
                                            ->reorderable(false)
                                            ->columnSpanFull()
                                            ->addActionLabel(label: "Nova Mensagem"),
                                    ]),
                                Fieldset::make('Anexos')
                                    ->schema([
                                        FileUpload::make('attachment')
                                            ->label('Material de apoio')
                                            ->disk('s3')
                                            ->acceptedFileTypes(['image/*'])
                                            ->maxSize(1024 * 5)
                                            ->directory('campaigns/attachments')
                                            ->maxFiles(1)
                                            ->columnSpanFull(),
                                    ]),
                            ]),

                        Wizard\Step::make('Duração')
                            ->schema([
                                Toggle::make('start_when_activate')
                                    ->label('Ao ativar campanha')
                                    ->default(true)
                                    ->reactive(),

                                DatePicker::make('start_date')
                                    ->label('Data de início')
                                    ->default(now())
                                    ->columns(1)
                                    ->dehydrated()
                                    ->required()
                                    ->reactive()
                                    ->hidden(fn(Get $get) => $get('start_when_activate')),

                                Toggle::make('repick')
                                    ->label('Indeterminado?')
                                    ->default(true)
                                    ->reactive(),

                                DatePicker::make('end_date')
                                    ->label('Data de fim')
                                    ->hidden(fn(Get $get) => $get('repick'))
                                    ->required()
                                    ->default(
                                        fn(Get $get) =>
                                        $get('start_date')
                                            ? Carbon::parse($get('start_date'))->addMonths(1)
                                            : now()->addMonths(1)
                                    ),
                            ]),

                        Wizard\Step::make('Disponibilidade')
                            ->schema([
                                Fieldset::make('Intervalo de Contato')
                                    ->schema([
                                        Radio::make('global_interval_enabled')
                                            ->label(false)
                                            ->options([
                                                'global' => 'Intervalo Global',
                                            ])
                                            ->reactive()
                                            ->disabled(fn(Get $get) => $get('consumer_source') == ConsumerSource::BEHAVIOR->value),
                                        Grid::make(1)
                                            ->schema([
                                                TextInput::make('global_interval')
                                                    ->label('Intervalo Global (dias)')
                                                    ->numeric()
                                                    ->minValue(1)
                                                    ->maxValue(365)
                                                    ->default(30)
                                                    ->visible(fn(Get $get) => $get('global_interval_enabled') === 'global'),

                                                Placeholder::make('availability.globalInfo')
                                                    ->label(false)
                                                    ->content(
                                                        fn(Get $get) =>
                                                        "Esta campanha poderá impactar os clientes {$get('global_interval')} dias após qualquer impacto de outra campanha."
                                                    )
                                                    ->visible(fn(Get $get) => $get('global_interval_enabled') === 'global'),
                                            ]),
                                    ]),

                                Fieldset::make('Contato com Clientes')
                                    ->schema([
                                        Radio::make('contact_type')
                                            ->label('Tipo de Contato')
                                            ->options(fn(Get $get) => $get('consumer_source') == ConsumerSource::BEHAVIOR->value
                                                ? [
                                                    ContactType::VALIDITY->value => 'Com Validade',
                                                ]
                                                : [
                                                    ContactType::UNIQUE->value => 'Único',
                                                    ContactType::MULTIPLE->value => 'Múltiplo',
                                                ])
                                            ->reactive()
                                            ->required(),

                                        TextInput::make('available_until')
                                            ->label('Os consumidores ficarão disponíveis para contato pelo tempo que você definir. (Dias)')
                                            ->numeric()
                                            ->minValue(1)
                                            ->maxValue(365)
                                            ->default(1)
                                            ->visible(fn(Get $get) => (int) $get('contact_type') === ContactType::VALIDITY->value),

                                        TextInput::make('next_impact')
                                            ->label(fn(Get $get) => "Esta campanha poderá impactar os clientes, novamente,  {$get('next_impact')} dias após um contato do time de vendas.")
                                            ->numeric()
                                            ->minValue(1)
                                            ->maxValue(365)
                                            ->default(30)
                                            ->visible(fn(Get $get) => (int) $get('contact_type') === ContactType::MULTIPLE->value),
                                    ]),
                            ]),
                        Wizard\Step::make('Revisão')
                            ->schema([
                                Grid::make(1)
                                    ->schema([
                                        Toggle::make('status')
                                            ->label('Ativar campanha')
                                            ->default(false)
                                            ->reactive(),

                                        TextInput::make('name')
                                            ->label('Nome da campanha')
                                            ->required(),

                                        Grid::make(1)
                                            ->schema([
                                                Toggle::make('use_default_name')
                                                    ->label('Manter o nome no app do Agenda')
                                                    ->default(true)
                                                    ->reactive(),

                                                TextInput::make('distribution_name')
                                                    ->label('Nome da campanha para o App Agenda do vendedor')
                                                    ->visible(fn(Get $get) => !$get('use_default_name'))
                                                    ->required(fn(Get $get) => !$get('use_default_name')),
                                            ]),

                                        Placeholder::make('distribution_review')
                                            ->label('Distribuição da campanha')
                                            ->content(fn(Get $get) => $get('distribution') === DistributionType::PREFERRED_SELLER->value
                                                ? 'Vendedor(a) de preferência'
                                                : 'Vendedor(a) que realizou a última interação'),

                                        Placeholder::make('consumer_source_review')
                                            ->label('Fonte de consumidores')
                                            ->content(fn(Get $get) => $get('consumer_source') === ConsumerSource::SEGMENT->value
                                                ? 'Segmento'
                                                : 'Comportamento'),

                                        Fieldset::make('Revisão das Mensagens')
                                            ->schema([
                                                Repeater::make('messages')
                                                    ->label(false)
                                                    ->relationship('messages')
                                                    ->schema([
                                                        Placeholder::make('text')
                                                            ->label('Mensagem')
                                                            ->content(fn($get) => $get('text')),
                                                    ])
                                                    ->columns(1)
                                                    ->disabled()
                                                    ->deletable(false)
                                                    ->reorderable(false)
                                                    ->addable(false)
                                                    ->columnSpanFull(),
                                            ]),

                                        Fieldset::make('Duração')
                                            ->schema([
                                                Grid::make(2)
                                                    ->schema([
                                                        Placeholder::make('start_date_review')
                                                            ->label('Data de início')
                                                            ->content(
                                                                fn(Get $get) =>
                                                                $get('start_when_activate') || !$get('start_date')
                                                                    ? 'Ao ativar campanha'
                                                                    : Carbon::parse($get('start_date'))->format('d/m/Y')
                                                            ),

                                                        Placeholder::make('end_date_review')
                                                            ->label('Data de fim')
                                                            ->content(
                                                                fn(Get $get) =>
                                                                !$get('repick') 
                                                                    ? Carbon::parse($get('end_date'))->format('d/m/Y')
                                                                    : 'Indeterminada'
                                                            ),
                                                    ]),
                                            ]),

                                        Placeholder::make('global_interval_review')
                                            ->label('Intervalo Global')
                                            ->content(
                                                fn(Get $get) => $get('availability.globalIntervalType') === 'global'
                                                    ? "Esta campanha poderá impactar os clientes {$get('global_interval')} dias após qualquer impacto de outra campanha."
                                                    : null
                                            )
                                            ->visible(fn(Get $get) => $get('availability.globalIntervalType') === 'global' && $get('global_interval')),

                                        Placeholder::make('contact_type_review')
                                            ->label('Tipo de Contato')
                                            ->content(
                                                fn(Get $get) => match ((int) $get('contact_type')) {
                                                    ContactType::UNIQUE->value => 'Único',
                                                    ContactType::MULTIPLE->value => 'Múltiplo',
                                                    ContactType::VALIDITY->value => 'Com Validade',
                                                    default => '',
                                                }
                                            ),

                                        Placeholder::make('multiple_contact_review')
                                            ->label(false)
                                            ->content(
                                                fn(Get $get) => (int) $get('contact_type') === ContactType::MULTIPLE->value
                                                    ? "Esta campanha poderá impactar os clientes, novamente, {$get('next_impact')} dias após um contato do time de vendas."
                                                    : null
                                            )
                                            ->visible(fn(Get $get) => (int) $get('contact_type') === ContactType::MULTIPLE->value && $get('next_impact')),

                                        Placeholder::make('validity_contact_review')
                                            ->label(false)
                                            ->content(
                                                fn(Get $get) => (int) $get('contact_type') === ContactType::VALIDITY->value
                                                    ? "Os consumidores ficarão disponíveis para contato por {$get('available_until')} dias."
                                                    : null
                                            )
                                            ->visible(fn(Get $get) => (int) $get('contact_type') === ContactType::VALIDITY->value && $get('available_until')),
                                    ]),
                            ]),
                    ]),
            ])
            ->columns(1);
    }

    public static function table(Tables\Table $table): Tables\Table
    {
        return $table
            ->reorderable('sort_order')
            ->defaultSort('sort_order')
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
                        if ($state) {
                            $updateData['sort_order'] = Campaign::max('sort_order') + 1;
                            $updateData = ['start_date' => now()];

                            if (!$record->repick) {
                                $updateData['end_date'] = now()->addMonths(1);
                            }
                            $record->update($updateData);
                        } else {
                            $record->update([
                                'sort_order' => null,
                            ]);
                        }
                    }),

                TextColumn::make('created_at')
                    ->label('Data de criação')
                    ->date('d/m/Y'),

                TextColumn::make('start_date')
                    ->label('Data de início')
                    ->date('d/m/Y'),

                TextColumn::make('end_date')
                    ->label('Data de fim')
                    ->formatStateUsing(
                        fn($state) =>
                        !empty($state) && $state !== null
                            ? Carbon::parse($state)->format('d/m/Y')
                            : 'Indeterminado'
                    ),

                TextColumn::make('updated_at')
                    ->label('Última edição')
                    ->date('d/m/Y'),
            ])
            ->filters([
                Filter::make('name')
                    ->label('Nome da campanha')
                    ->form([
                        TextInput::make('value')
                            ->label('Nome'),
                    ])
                    ->query(
                        fn($query, array $data) =>
                        $query->when(
                            $data['value'],
                            fn($query, $value) =>
                            $query->where('name', 'like', "%{$value}%")
                        )
                    ),
                Filter::make('status')
                    ->label('Status')
                    ->form([
                        Select::make('value')
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
            ],  layout: FiltersLayout::AboveContent)
            ->hiddenFilterIndicators()
            ->actions([
                ViewCampaignAction::make()
                    ->label(false)
                    ->icon('heroicon-o-eye')
                    ->color('primary')
                    ->tooltip('Visualizar detalhes da campanha'),
                Tables\Actions\DeleteAction::make()
                    ->label(false)
                    ->color('danger')
                    ->requiresConfirmation()
                    ->icon('heroicon-o-trash')
                    ->tooltip('Excluir campanha'),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListCampaigns::route('/'),
            'create' => Pages\CreateCampaign::route('/create'),
            'edit' => Pages\EditCampaign::route('/{record}/edit'),
        ];
    }
}
