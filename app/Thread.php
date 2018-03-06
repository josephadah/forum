<?php

namespace App;

use App\Events\ThreadRecievedNewReply;
use App\Filters\ThreadFilters;
use App\Notifications\ThreadWasUpdated;
use App\RecordsActivity;
use App\Visit;
use Illuminate\Database\Eloquent\Model;
use Laravel\Scout\Searchable;

class Thread extends Model
{
    use RecordsActivity;

    protected $guarded = [];
    protected $with = ['creator', 'channel'];

    protected $casts = ['locked' => 'boolean'];

    protected $appends = ['isSubscribeTo'];

    protected static function boot() 
    {
        parent::boot();

        // static::addGlobalScope('replyCount', function ($builder) {
        //     $builder->withCount('replies');
        // });

        static::deleting(function ($thread) {
            $thread->replies->each(function ($reply) {
                $reply->delete();
            });
        });

    }

    public function path()
    {
    	return '/threads/' .$this->channel->slug . '/' . $this->slug;
    }

    public function replies()
    {
    	return $this->hasMany('App\Reply');
    }

    public function creator()
    {
    	return $this->belongsTo('App\User', 'user_id');
    }

    public function addReply($reply)
    {
        $reply = $this->replies()->create($reply);

        event(new ThreadRecievedNewReply($reply));

        return $reply;
    }

    public function channel()
    {
        return $this->belongsTo('App\Channel');
    }

   public function scopeFilter($query, ThreadFilters $filters)
    {
        return $filters->apply($query);
    }

    public function subscribe($user_id = null) 
    {
        $this->subscriptions()->create([
            'user_id' => $user_id ?: auth()->id()
        ]);

        return $this;
    }

    public function unsubscribe($user_id = null) 
    {
        $this->subscriptions()
        ->where('user_id', $user_id ?: auth()->id())
        ->delete();
    }

    public function subscriptions() 
    {
        return $this->hasMany('App\ThreadSubscription');
    }

    public function getIsSubscribeToAttribute()
    {
        return $this->subscriptions()->where('user_id', auth()->id())->exists();
    }

    public function hasUpdatesFor()
    {
        $key = auth()->user()->visitedThreadCacheKey($this);

        return $this->updated_at > cache($key);
    }

    public function visits()
    {
        return new Visit($this);
    }

    public function getRouteKeyName()
    {
        return 'slug';
    }

    public function makeSlug()
    {
        if($lastThread = $this->latest()->first()) {
            return $slug = str_slug(request('title')) . '-' . ($lastThread->id + 1);
        } else {
            return $slug = str_slug(request('title')) . '-1';
        }
    }

    public function markBestReply($reply)
    {
        $this->update(['best_reply_id' => $reply->id]);
    }
}
