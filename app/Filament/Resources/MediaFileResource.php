<?php

namespace App\Filament\Resources;

use App\Filament\Resources\MediaFileResource\Pages;
use App\Models\MediaFile;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Resource;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\TernaryFilter;
use Filament\Tables\Table;
use Filament\Forms\Form;

class MediaFileResource extends Resource
{
    protected static ?string $model = MediaFile::class;

    protected static ?string $navigationIcon = 'heroicon-o-photo';
    protected static ?string $navigationLabel = 'Mídias';

    public static function form(Form $form): Form
    {
        return $form->schema([
            TextInput::make('name')
                ->label('Nome do Arquivo')
                ->required()
                ->maxLength(255),

            Select::make('media_type')
                ->label('Tipo de Mídia')
                ->options(MediaFile::MEDIA_TYPES)
                ->required(),

            FileUpload::make('file_path')
                ->label('Arquivo (Imagem ou PDF)')
                ->image()
                ->acceptedFileTypes(['image/*', 'application/pdf'])
                ->visibility('public')
                ->imagePreviewHeight('200')
                ->preserveFilenames()
                ->disk('public')
                ->downloadable()
                ->directory('media-files')
                ->required(),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->label('Nome')
                    ->limit(40)
                    ->searchable(),

                TextColumn::make(('media_type'))
                    ->label('Tipo de Mídia')
                    ->formatStateUsing(fn(MediaFile $record) => $record->media_type_name)
                    ->searchable(),

                IconColumn::make('is_active')
                    ->label('Ativo')
                    ->boolean(),

                TextColumn::make('created_at')
                    ->label('Enviado em')
                    ->dateTime('d/m/Y H:i'),
            ])
            ->filters([
                SelectFilter::make('media_type')
                    ->label('Tipo de Mídia')
                    ->options(MediaFile::MEDIA_TYPES),

                TernaryFilter::make('is_active')
                    ->label('Ativo'),
            ])
            ->actions([
                // \Filament\Tables\Actions\ViewAction::make(), // Adiciona ação de visualizar
                \Filament\Tables\Actions\EditAction::make(), // Adiciona ação de editar
            ])
            ->bulkActions([
                \Filament\Tables\Actions\DeleteBulkAction::make(),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListMediaFiles::route('/'),
            'create' => Pages\CreateMediaFile::route('/create'),
            'edit' => Pages\EditMediaFile::route('/{record}/edit'),
        ];
    }
}
