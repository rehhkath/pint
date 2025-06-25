<?php

namespace App\Filament\Resources\SegmentResource\Pages;

use App\Filament\Resources\SegmentResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
use App\Models\SegmentFilter;

class EditSegment extends EditRecord
{
    protected static string $resource = SegmentResource::class;
    protected static ?string $title = 'Editar Segmento';

    protected function afterSave(): void
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

            SegmentFilter::updateOrCreate(
                ['segment_id' => $this->record->id, 'group' => $groupCount],
                [
                    'operator' => isset($group['operator']) && $group['operator'] ? 'included' : 'excluded',
                    'filter_type' => $group['filter_type'] ?? null,
                    'entity_type' => $group['entity_type'] ?? null,
                    'entity_id' => $group['entity_id'] ?? null,
                    'property_filter' => json_encode($keyValueArray),
                ]
            );

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

                    SegmentFilter::updateOrCreate(
                        ['segment_id' => $this->record->id, 'group' => $groupCount],
                        [
                            'operator' => isset($orItem['operator']) && $orItem['operator'] ? 'included' : 'excluded',
                            'filter_type' => $orItem['filter_type'] ?? null,
                            'entity_type' => $orItem['entity_type'] ?? null,
                            'entity_id' => $orItem['entity_id'] ?? null,
                            'property_filter' => json_encode($keyValueOrArray),
                        ]
                    );
                }
            }
        }
    }

    public function mutateFormDataBeforeFill(array $data): array
    {
        $segmentFilters = SegmentFilter::where('segment_id', $data['id'])->get();

        $groups = [];

        foreach ($segmentFilters->groupBy('group') as $groupItems) {
            $main = $groupItems->first();

            $groupData = [
                'filter_type' => $main->filter_type,
                'entity_type' => $main->entity_type,
                'entity_id' => $main->entity_id,
            ];

            $groupData['operator'] = $main->operator === 'included' ? true : false;
            $extras = json_decode($main->property_filter ?? '[]', true);
            foreach ($extras as $item) {
                $groupData[$item['key']] = $item['value'];
            }

            $orItems = $groupItems->slice(1)->map(function ($item) {
                $orData = [
                    'filter_type' => $item->filter_type,
                    'entity_type' => $item->entity_type,
                    'entity_id' => $item->entity_id,
                ];
                $orData['operator'] = $item->operator === 'included' ? true : false;

                $extras = json_decode($item->property_filter ?? '[]', true);
                foreach ($extras as $extra) {
                    $orData[$extra['key']] = $extra['value'];
                }

                return $orData;
            })->values()->all();

            if (!empty($orItems)) {
                $groupData['or'] = $orItems;
            }

            $groups[] = $groupData;
        }

        $data['group'] = $groups;

        return $data;
    }


    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
