<?php
namespace App\Filament\Resources\Actions;

use App\Models\Message;
use Filament\Forms\Components\Actions\Action;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\ViewField;

class ViewOldMessages
{
    public static function make(): Action
    {
        $messages = Message::getAllMessages();

        $formattedMessages = [];
        foreach ($messages as $message) {
            $formattedMessages[] = [
                'mensagem_antiga' => $message->text,
            ];
        }

        return Action::make('selecionarMensagemAntiga')
            ->label('Visualizar Mensagens Anteriores')
            ->icon('heroicon-o-archive-box-arrow-down')
            ->color('gray')
            ->modalHeading('Mensagens de Campanhas Anteriores')
            ->modalSubmitActionLabel('Inserir na campanha')
            ->modalCancelActionLabel('Fechar')
            ->modalWidth('xl')
            ->form([
                Repeater::make('mensagens_antigas')
                    ->label('Mensagens disponíveis')
                    ->schema([
                        Textarea::make('mensagem_antiga')
                            ->label('Mensagem')
                            ->rows(5)
                            ->disabled()
                            ->columnSpanFull(),

                        ViewField::make('copy_button')
                            ->view('filament.buttons.copy'),
                    ])
                    ->default($formattedMessages)
                    ->minItems(1)
                    ->collapsible()
                    ->reorderable(false)
                    ->deletable(false)
                    ->columnSpanFull()
                    ->addable(false),
            ]);
    }
}
