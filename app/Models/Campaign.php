<?php

namespace App\Models;

use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

use App\Models\Segment;
use App\Models\Message;
use App\Models\Behavior;
use App\Enums\ContactType;
use App\Enums\ConsumerSource;
use App\Enums\DistributionType;

class Campaign extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'campaigns';

    protected $fillable = [
        'name',
        'distribution_name',
        'status',
        'distribution',
        'consumer_source',
        'segment_id',
        'behavior_done_id',
        'behavior_not_done_id',
        'start_date',
        'end_date',
        'sort_order',
        'global_interval',
        'contact_type',
        'available_until',
        'next_impact',
        'created_by',
        'updated_by',
        'repick',
        'start_when_activate',
    ];

    protected $casts = [
        'distribution' => DistributionType::class,
        'consumer_source' => ConsumerSource::class,
        'contact_type' => ContactType::class,

        'start_date' => 'datetime',
        'end_date' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'deleted_at' => 'datetime',

        'repick' => 'boolean',
        'start_when_activate' => 'boolean',
    ];

    public function segment()
    {
        return $this->belongsTo(Segment::class);
    }

    public function behaviorDone()
    {
        return $this->belongsTo(Behavior::class, 'behavior_done_id');
    }

    public function behaviorNotDone()
    {
        return $this->belongsTo(Behavior::class, 'behavior_not_done_id');
    }

    public function messages()
    {
        return $this->hasMany(Message::class, 'campaign_id');
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
}
