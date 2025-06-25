<?php

namespace App\Models;

use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;


class Segment extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'segments';

    protected $fillable = [
        'id',
        'name',
        'status',
        'created_by',
        'updated_by',
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'deleted_at' => 'datetime',
    ];

    public function segmentFilters()
    {
        return $this->hasMany(SegmentFilter::class, 'segment_id');
    }

    public static function getNameById($id)
    {
        return self::where('id', $id)->select('name')->value('name');
    }
    protected static function booted(): void
    {
        static::creating(function ($campaign) {
            $campaign->created_by = Auth::id();
            $campaign->updated_by = Auth::id();
        });

        static::updating(function ($campaign) {
            $campaign->updated_by = Auth::id();
        });
    }

    public static function getAllOpt()
    {
        return self::where('status', true)
            ->select('id', 'name')
            ->get();
    }

    public static function getUsages(Segment $segment): array
    {
        $usedInSegments = \App\Models\SegmentFilter::where('filter_type', 'segment')
            ->where('entity_id', $segment->id)
            ->with('segment') // parent segment
            ->get()
            ->map(fn($filter) => [
                'id' => $filter->segment->id,
                'name' => $filter->segment->name,
            ])
            ->unique('id')
            ->values()
            ->toArray();

        $usedInCampaigns = \App\Models\Campaign::where('segment_id', $segment->id)
            ->get()
            ->map(fn($campaign) => [
                'id' => $campaign->id,
                'name' => $campaign->name,
                'status' => $campaign->status,
            ])
            ->toArray();

        return [
            'usedBySegments' => $usedInSegments,
            'usedByCampaigns' => $usedInCampaigns,
        ];
    }
}
