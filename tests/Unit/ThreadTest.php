<?php

namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Notification;
use App\Notifications\ThreadWasUpdated;
use Carbon\Carbon;

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
    function it_has_a_path()
    {
    	$this->assertEquals('/threads/' . $this->thread->channel->slug . '/' . $this->thread->slug, $this->thread->path());
    }


	/** @test */
	function it_has_a_creator()
	{
		$thread = factory('App\Thread')->create();

		$this->assertInstanceOf('App\User', $this->thread->creator);
	}

	/** @test */
	function it_can_add_a_reply()
	{
		$this->thread->addReply([
			'body' => 'foobar', 
			'user_id' => 1
		]);

		$this->assertCount(1, $this->thread->replies);
	}

	/** @test */
	function it_notifies_all_its_subscriber_when_it_adds_a_reply()
	{
		Notification::fake();

		$this->signIn();

		$this->thread->subscribe();

		$this->thread->addReply([
			'body' => 'foobar', 
			'user_id' => 1
		]);

		Notification::assertSentTo(auth()->user(), ThreadWasUpdated::class);
	}



	/** @test */
	function a_thread_belongs_to_a_channel()
	{
		$this->assertInstanceOf('App\Channel', $this->thread->channel);
	}

	/** @test */
	function a_thread_can_be_subscribed_to()
	{
		$thread = $this->thread;
		$thread->subscribe($user_id = 1);

		$this->assertEquals(
			1, 
			$thread->subscriptions()->where('user_id', 1)->count());
	}

	/** @test */
	function a_thread_can_be_unsubscribe_from()
	{
		$thread = $this->thread;
		$thread->subscribe($user_id = 1);
		$thread->unsubscribe($user_id);

		$this->assertCount(
			0, 
			$thread->subscriptions
		);
	}

	/** @test */ 
	function it_knows_if_the_authenticated_user_subscribe_to_it()
	{
		$this->signIn();

		$thread = factory('App\Thread')->create();

		$this->assertFalse($thread->isSubscribeTo);

		$thread->subscribe();

		$this->assertTrue($thread->isSubscribeTo);
	}

	/** @test */
	function it_can_check_if_the_authenticated_user_has_read_all_replies()
	{
		$this->signIn();

		$thread = factory('App\Thread')->create();

		$this->assertTrue($thread->hasUpdatesFor(auth()->user()));

		$key = sprintf("users.%s.visits.%s", auth()->id(), $thread->id);

		cache()->forever($key, Carbon::now());

		$this->assertFalse($thread->hasUpdatesFor(auth()->user()));
	}

	/**
	 @test
	 */
	 function it_records_each_visits_to_a_thread()
	 {
	 	$thread = factory('App\Thread')->make(['id' => 1]);

	 	$thread->visits()->reset();

	 	$this->assertSame(0, $this->thread->visits()->count());

	 	$thread->visits()->record();

	 	$this->assertEquals(1, $this->thread->visits()->count());

	 	$thread->visits()->record();

	 	$this->assertEquals(2, $this->thread->visits()->count());
	 }
}
