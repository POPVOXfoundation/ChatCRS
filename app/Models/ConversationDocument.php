<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ConversationDocument extends Model
{
    use HasFactory;

    protected $fillable = [
        'conversation_id',
        'document_id'
    ];
}
