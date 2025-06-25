<?php

namespace App\Filament\Resources\CampaignResource\Pages;

use App\Filament\Resources\CampaignResource;
use Illuminate\Database\Eloquent\Builder;
use Filament\Resources\Pages\EditRecord;
use Filament\Resources\Pages\Page;
use Filament\Actions;
use Illuminate\Support\Facades\Log;

class EditCampaign extends EditRecord
{
    protected static string $resource = CampaignResource::class;
    protected static ?string $title = 'Editar Campanha';

    protected function mutateFormDataBeforeFill(array $data): array
    {
        if (!empty($data['global_interval'])) {
            $data['global_interval_enabled'] = 'global';
        }

        if (empty($data['distribution_name'])) {
            $data['use_default_name'] = true;
        }

        return $data;
    }

    protected function afterSave(): void
    {
        $data = $this->form->getState();

        if ($data['status'] && $data['start_when_activate']) {
            $this->record->update(['start_date' => now()]);
        }
    }

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
