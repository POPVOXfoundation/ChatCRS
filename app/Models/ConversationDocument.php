<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ConversationDocument extends Model
{
    use HasFactory;

    protected $fillable = [
        'conversation_id',
        'document_id'
    ];

    public function document(): BelongsTo
    {
        return $this->belongsTo(Document::class);
    }
}
