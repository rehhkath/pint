<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FilterTypeOption extends Model
{
    
    protected $fillable = [
        'filter_type',
        'entity',
        'entity_column',
    ];
}