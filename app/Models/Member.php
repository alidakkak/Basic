<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Member extends Model
{
    use HasFactory;
    protected $fillable=["conversation_id","user_id","is_block","is_mute","is_pinned","is_archived","role","joined_at"];
}
