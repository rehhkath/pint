<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;


class Behavior extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'behaviors';

    protected $fillable = [
        'id',
        'name',
        'description',
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'deleted_at' => 'datetime',
    ];

    public static function getNameById($id)
    {
        return self::where('id', $id)->select('name')->value('name');
    }

    public static function getAllOpt()
    {
        return collect([
            ['id' => 1, 'name' => 'Comprou'],
            ['id' => 2, 'name' => 'Devolveu'],
            ['id' => 3, 'name' => 'Resgatou cashback'],
            ['id' => 4, 'name' => 'Comprou a mais de 2 meses'],
        ]);
    }
}
