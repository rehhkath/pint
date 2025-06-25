<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use Illuminate\Database\Eloquent\Factories\HasFactory;

class MediaFile extends Model
{
    use HasFactory;

    protected $table = 'media_files';

    protected $fillable = [
        'name',
        'file_path',
        'file_type',
        'media_type',
        'is_active',
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'deleted_at' => 'datetime',
        'is_active' => 'boolean',
    ];

    /**
     * Tipos de mídia disponíveis.
     */
    public const MEDIA_TYPES = [
        'current_collection' => 'Coleção Atual',
        'catalog' => 'Catálogo',
        'current_campaign' => 'Campanha Atual',
        'promotion' => 'Promoção',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($mediaFile) {
            if ($mediaFile->media_type === 'catalog') {
                $mediaFile->file_type = '.pdf';
            } else {
                $mediaFile->file_type = '.jpg';
            }
        });
    }

    /**
     * Retorna o nome do tipo de mídia.
     */
    public function getMediaTypeNameAttribute(): string
    {
        return self::MEDIA_TYPES[$this->media_type] ?? 'Desconhecido';
    }
}