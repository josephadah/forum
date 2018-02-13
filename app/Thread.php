<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Filters\ThreadFilters;
use App\RecordsActivity;

class Thread extends Model
{
    use RecordsActivity;

    protected $guarded = [];
    protected $with = ['creator', 'channel'];

    protected static function boot() 
    {
        parent::boot();

        static::addGlobalScope('replyCount', function ($builder) {
            $builder->withCount('replies');
        });

        static::deleting(function ($thread) {
            $thread->replies->each(function ($reply) {
                $reply->delete();
            });
        });

    }

    public function path()
    {
    	return '/threads/' .$this->channel->slug . '/' .$this->id;
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
        return $this->replies()->create($reply)->load('owner');
    }

    public function channel()
    {
        return $this->belongsTo('App\Channel');
    }

   public function scopeFilter($query, ThreadFilters $filters)
    {
        return $filters->apply($query);
    }
}
