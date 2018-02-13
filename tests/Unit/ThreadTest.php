<?php

namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ThreadTest extends TestCase
{
	use RefreshDatabase;

	protected $thread;

	public function setUp() 
    {
    	parent::setUp();

    	$this->thread = factory('App\Thread')->create();
    }

    /** @test */
    function a_thread_can_make_a_slug_url()
    {
    	$this->assertEquals('/threads/' . $this->thread->channel->slug . '/' . $this->thread->id, $this->thread->path());
    }


	/** @test */
	function it_has_a_creator()
	{
		$thread = factory('App\Thread')->create();

		$this->assertInstanceOf('App\User', $this->thread->creator);
	}

	function it_can_add_a_reply()
	{
		$this->thread->addReply([
			'body' => 'foobar', 
			'user_id' => 1
		]);

		$this->assertCount(1, $this->thread->replies);
	}


	/** @test */
	function a_thread_belongs_to_a_channel()
	{
		$this->assertInstanceOf('App\Channel', $this->thread->channel);
	}

}
