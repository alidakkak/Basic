<?php
namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Tymon\JWTAuth\Contracts\JWTSubject;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable implements JWTSubject
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'phone',
        'password',
        'code',
        'expired_at'
    ];


    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    public function getJWTCustomClaims()
    {
        return [];
    }
    public function conversations()
    {
        return $this->belongsToMany(Conversation::class,"members")
            ->withPivot([  'joined_at', 'role','is_block','is_mute','is_pinned','is_archived'])
            ->latest('last_message_id');
    }
    public function pinnedconversations()
    {
        return $this->belongsToMany(Conversation::class,"members")
            ->withPivot([  'joined_at', 'role','is_block','is_mute','is_pinned','is_archived'])
            ->where("is_archived",0)
            ->orderBy('is_pinned', 'desc')
            ->latest('last_message_id');
    }
    public function archivedconversations()
    {
        return $this->belongsToMany(Conversation::class,"members")
            ->withPivot([  'joined_at', 'role','is_block','is_mute','is_pinned','is_archived'])
            ->where("is_archived",1)
            ->latest('last_message_id');
    }
    public function unreadmessage()
    {
        return $this->hasMany(Recipient::class,"user_id")->whereNull("read_at")->count();
    }
    public function hidemessage()
    {
        return $this->belongsToMany(Message::class,HideMessage::class);
    }
    public function messages()
    {
        return $this->hasMany(Message::class,"user_id");
    }
    public function generate_code()
    {
        $this->timestamps = false;
        $this->code = rand(1000, 9999);
        $this->expired_at = now()->addMinute(10);
        $this->save();
    }
    public function reset_code()
    {
        $this->timestamps = false;
        $this->code = null;
        $this->expired_at = null;
        $this->save();
    }
}

