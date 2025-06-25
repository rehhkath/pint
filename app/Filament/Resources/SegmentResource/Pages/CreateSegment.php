<?php

namespace App\Filament\Resources\SegmentResource\Pages;

use App\Filament\Resources\SegmentResource;
use Filament\Resources\Pages\CreateRecord;
use App\Models\SegmentFilter;
use Illuminate\Support\Facades\Log;

class CreateSegment extends CreateRecord
{
    protected static string $resource = SegmentResource::class;
    protected static ?string $title = 'Criar Segmento';


    protected function afterCreate(): void
    {
        $data = $this->form->getState();

        $groupCount = 0;

        foreach ($data['group'] as $group) {
            $groupCount++;

            $remainingAttributes = array_diff_key($group, [
                'operator' => true,
                'filter_type' => true,
                'entity_type' => true,
                'entity_id' => true,
                'property_filter' => true,
                'or' => true,
            ]);

            $keyValueArray = [];
            foreach ($remainingAttributes as $key => $value) {
                if (!is_null($value)) {
                    $keyValueArray[] = [
                        'key' => $key,
                        'value' => $value,
                    ];
                }
            }

            SegmentFilter::create([
                'segment_id' => $this->record->id,
                'operator' => isset($group['operator']) && $group['operator'] ? 'included' : 'excluded',
                'filter_type' => $group['filter_type'] ?? null,
                'entity_type' => $group['entity_type'] ?? null,
                'entity_id' => $group['entity_id'] ?? null,
                'property_filter' => json_encode($keyValueArray),
                'group' => $groupCount,
            ]);

            if (!empty($group['or']) && is_array($group['or'])) {
                foreach ($group['or'] as $orItem) {
                    $remainingOrAttributes = array_diff_key($orItem, [
                        'operator' => true,
                        'filter_type' => true,
                        'entity_type' => true,
                        'entity_id' => true,
                        'property_filter' => true,
                    ]);

                    $keyValueOrArray = [];
                    foreach ($remainingOrAttributes as $key => $value) {
                        if (!is_null($value)) {
                            $keyValueOrArray[] = [
                                'key' => $key,
                                'value' => $value,
                            ];
                        }
                    }

                    SegmentFilter::create([
                        'segment_id' => $this->record->id,
                        'operator' => isset($group['operator']) && $group['operator'] ? 'included' : 'excluded', 
                        'filter_type' => $orItem['filter_type'] ?? null,
                        'entity_type' => $orItem['entity_type'] ?? null,
                        'entity_id' => $orItem['entity_id'] ?? null,
                        'property_filter' => json_encode($keyValueOrArray),
                        'group' => $groupCount,
                    ]);
                }
            }
        }
    }
}
