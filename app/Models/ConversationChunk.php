<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ConversationChunk extends Model
{
    use HasFactory;

    protected $fillable = [
        'conversation_id',
        'document_id',
        'chunk_id'
    ];

    public function document() {
        return $this->belongsTo(Document::class);
    }
}
