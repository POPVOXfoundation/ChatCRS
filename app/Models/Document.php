<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Document extends Model
{
    use HasFactory;

    protected $fillable = [
        'report_id',
        'title',
        'url'
    ];

    public function chunks()
    {
        return $this->hasMany(DocumentChunk::class);
    }
}
