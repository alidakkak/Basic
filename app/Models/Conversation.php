<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Conversation extends Model
{
    use HasFactory;

    protected $fillable = ["user_id","label", "type","last_message_id","avatar","description"];
    public function lastMessage()
    {
        return $this->belongsTo(Message::class, 'last_message_id', 'id')
            ->whereNull('deleted_at')
            ->withDefault([
                'body' => 'Message deleted'
            ]);

}

    public function recipients()
    {
        return $this->hasManyThrough(
            Recipient::class,
            Message::class,
            'conversation_id',
            'message_id',
            'id',
            'id'
        );
    }
    public function members()
    {
        return $this->belongsToMany(User::class, Member::class)
            ->withPivot([
                'joined_at', 'role','is_block','is_mute','is_pinned','is_archived'
            ]);
    }



    public function messages()
    {
        return $this->hasMany(Message::class);
    }
}

