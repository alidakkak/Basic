<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Story extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    public function user() {
        return $this->belongsTo(User::class);
    }

    public function views() {
        return $this->hasMany(Views::class);
    }

    public function setImageAttribute ($image){
        $newImageName = uniqid() . '_' . 'story_image' . '.' . $image->extension();
        $image->move(public_path('story_image') , $newImageName);
        return $this->attributes['image'] =  '/'.'story_image'.'/' . $newImageName;
    }

}
