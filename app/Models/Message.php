<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Message extends Model
{

    use HasFactory;
    protected $fillable = ["user_id","type","body"];
    public function sender(){
        return $this->belongsTo(User::class,"user_id");
    }
    public function conversation(){
        return $this->belongsTo(Conversation::class,"user_id");
    }
    public function recipients()
    {
        return $this->hasMany(Recipient::class);

    }

}
