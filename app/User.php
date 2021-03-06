<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use App\Reply;

class User extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password', 'avatar_path', 'confirmation_token', 'confirmed',
    ];

    protected $casts = [
        'confirmed' => 'boolean'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token', 'email',
    ];

    public function getRouteKeyName()
    {
        return 'name';
    }

    public function isAdmin()
    {
        return in_array($this->name, ['John', 'Joseph', 'Rose']);
    }

    public function confirm()
    {
        $this->confirmed = true;

        $this->confirmation_token = null;

        $this->save();
    }

    public function threads()
    {
        return $this->hasMany('App\Thread');
    }

    public function activity()
    {
        return $this->hasMany(Activity::class);
    }

    public function visitedThreadCacheKey($thread)
    {
        return sprintf("users.%s.visits.%s", $this->id, $thread->id);
    }

    public function readThread($thread)
    {
        // $this->visitedThreadCacheKey($thread);

        cache()->forever(
            $this->visitedThreadCacheKey($thread), 
            \Carbon\Carbon::now()
        );
    }

    public function lastReply()
    {
        return $this->hasOne(Reply::class)->latest();
    }

    public function getAvatarPathAttribute($avatar)
    {
        if (! $avatar) {
            return asset("storage/avatars/default.jpg");
        }
        return asset("storage/" . $avatar);
    }
}
