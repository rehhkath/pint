<?php

namespace App\Services;

use App\Models\Segment;

class SegmentFilterService
{
    public function __construct() {}

    public static function getSegmentData(Segment $segment): array
    {
        $segment->loadMissing('segmentFilters');

        $grouped = $segment->segmentFilters
            ->groupBy('group')
            ->map(function ($filters) {
                return $filters->map(function ($filter) {
                    $propertyFilter = collect($filter->property_filter ?? [])
                        ->filter(fn($item) => is_array($item) && isset($item['key']) && isset($item['value']))
                        ->mapWithKeys(fn($item) => [$item['key'] => $item['value']])
                        ->toArray();

                    return [
                        'operator' => $filter->operator,
                        'filter_type' => $filter->filter_type,
                        'property_filter' => $propertyFilter,
                    ];
                })->toArray();
            });

        return [
            'name' => $segment->name,
            'filterGroups' => $grouped->toArray(), // ['1' => [...], '2' => [...]]
        ];
    }
}
