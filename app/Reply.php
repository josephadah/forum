<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\User, App\Favorite, App\Favoritable;
use App\RecordsActivity;
use App\Thread;

class Reply extends Model
{
    use Favoritable, RecordsActivity;

	protected $guarded = [];
    protected $with = ['owner', 'favorites'];
    protected $appends = ['favoritesCount', 'isFavorited'];
	
    public function owner()
    {
    	return $this->belongsTo(User::class, 'user_id');
    }

    public function thread()
    {
    	return $this->belongsTo(Thread::class);
    }


    public function path()
    {
        return ($this->thread->path() . "#reply-" . $this->id);
    }

    public function favorites()
    {
        return $this->morphMany('App\Favorite', 'favorited');
    }

}
