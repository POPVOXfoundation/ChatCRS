<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Document extends Model
{
    use HasFactory;

    protected $fillable = [
        'report_id',
        'title',
        'hash',
        'document_date',
        'url'
    ];

    protected $casts = [
        'document_date' => 'datetime',
    ];

    protected static function booted(): void
    {
        static::deleting(function (Document $document) {
            $document->chunks()->delete();
        });
    }

    public function chunks(): HasMany
    {
        return $this->hasMany(DocumentChunk::class);
    }
}
