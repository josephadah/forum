<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class SubscribeToThreadsTest extends TestCase
{
	use RefreshDatabase;

    /** @test */
    function a_user_can_subscribe_to_thread()
    {
    	$this->be($user = factory('App\User')->create());

    	$thread = factory('App\Thread')->create();

    	$this->post($thread->path() . '/subscriptions');

    	$this->assertCount(1, $thread->subscriptions);
    }

    /** @test */
    function a_user_can_unsubscribe_from_thread()
    {
    	$this->be($user = factory('App\User')->create());

    	$thread = factory('App\Thread')->create();

    	$thread->subscribe();

    	$this->delete($thread->path() . '/subscriptions');

    	$this->assertCount(0, $thread->subscriptions);
    }

}
