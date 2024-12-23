<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Conversation extends Model
{
    use HasFactory;

    protected $fillable = [
        'session_id'
    ];

    public function messages(): HasMany
    {
        return $this->hasMany(Message::class);
    }

    public function documents(): HasMany
    {
        return $this->hasMany(ConversationDocument::class);
    }

    public function chunks(): HasMany
    {
        return $this->hasMany(ConversationChunk::class);
    }
}
