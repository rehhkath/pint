<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SegmentFilter extends Model
{
    protected $fillable = [
        'segment_id',
        'operator', //fizeram - não fizeram
        'filter_type', //comportamento, segement, característica, tipo de preferência
        'entity_type',
        'entity_id', 
        'property_filter',  //filtros
        'group', //e - ou
        'filter_type_option_id', //opções de filtro
    ];

    protected $casts = [
        'property_filter' => 'array',
    ];

    public function segment(): BelongsTo
    {
        return $this->belongsTo(Segment::class);
    }
}
