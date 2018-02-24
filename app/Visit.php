<?php

namespace App;

use Illuminate\Support\Facades\Redis;


class Visit
{
	protected $thread;
	
	function __construct($thread)
	{
		$this->thread = $thread;
	}

	public function reset()
	{
		return Redis::del($this->cacheKey());
	}

	public function record()
	{
		return Redis::incr($this->cacheKey());
	}

	public function count()
	{
		return Redis::get($this->cacheKey()) ?? 0;
	}

	public function cacheKey()
	{
		return "threads.{$this->thread->id}.visits";
	}

}