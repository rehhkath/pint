<?php

namespace App\Filament\Resources\CampaignResource\Pages;

use App\Filament\Resources\CampaignResource;
use Filament\Resources\Pages\CreateRecord;
use Filament\Forms\Form;

class CreateCampaign extends CreateRecord
{
    protected static string $resource = CampaignResource::class;
    protected static ?string $title = 'Criar Campanha';

    protected function afterCreate(): void
    {
        $data = $this->form->getState();

        if ($data['status'] && $data['start_when_activate']) {
            $this->record->update(['start_date' => now()]);
        }

        foreach (['message1', 'message2', 'message3'] as $key) {
            if (!empty($data[$key])) {
                $this->record->messages()->create([
                    'text' => $data[$key],
                ]);
            }
        }
    }
}
