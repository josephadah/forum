<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ReadThreadsTest extends TestCase
{

    use RefreshDatabase;

    public function setUp() 
    {
    	parent::setUp();

    	$this->thread = factory('App\Thread')->create();
    }
    
    /** @test */
    public function a_user_can_view_all_threads()
    {
        $this->get('/threads')->assertSee($this->thread->title);
    }

    /** @test */
    public function a_user_can_read_a_single_thread()
    {
	    $this->get($this->thread->path())->assertSee($this->thread->title);
    }

    /** @test */
    public function a_user_can_read_reply_associated_to_a_thread()
    {
    	$reply = factory('App\Reply')
    	->create(['thread_id' => $this->thread->id, 'user_id' => $this->thread->user_id]);

    	$this->get($this->thread->path())
    	->assertSee($reply->body);
    } 

    /** @test */
    public function a_user_can_filter_threads_according_to_a_channel()
    {
        $channel = factory('App\Channel')->create();

        $threadInChannel = factory('App\Thread')->create(['channel_id' => $channel->id]);

        $threadNotInChannel = factory('App\Thread')->create();

        $this->get('/threads/' . $channel->slug)
            ->assertSee($threadInChannel->title)
            ->assertDontSee($threadNotInChannel->title);
    }

    /** @test */
    public function a_user_can_filter_threads_by_any_username()
    {
        $this->be($user = factory('App\User')->create(['name' => 'John']));

        $threadByJohn = factory('App\Thread')->create(['user_id' => $user->id]);

        $threadNotByJohn = factory('App\Thread')->create();

        $this->get('/threads?by=John')
            ->assertSee($threadByJohn->title)
            ->assertDontSee($threadNotByJohn->title);
    }

    /** @test */
    public function a_user_can_filter_threads_by_popularity()
    {
        $threadWithTwoReplies = factory('App\Thread')->create();
        factory('App\Reply', 2)->create(['thread_id' => $threadWithTwoReplies->id]);

        $threadWithThreeReplies = factory('App\Thread')->create();
        factory('App\Reply', 3)->create(['thread_id' => $threadWithThreeReplies->id]);

        $threadWithNoReply = $this->thread;

        // when i filter all threads by popularity
        $response = $this->getJson('threads?popular=1')->json();

        // Then they should be returned from most replies to least
        $this->assertEquals([3, 2, 0], array_column($response, 'replies_count'));

    }
}
