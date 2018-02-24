<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class MentionUserTest extends TestCase
{
	use RefreshDatabase;

    /** @test */
    public function notify_a_user_when_they_are_mentioned_by_another_user()
    {
    	$this->withoutExceptionHandling();
    	// given we have a signed in john
    	$this->be($john = factory('App\User')->create(['name' => 'john']));
    	// given we have another jane
    	$jane = factory('App\User')->create(['name' => 'jane']);
    	// given we have a thread
    	$thread = factory('App\Thread')->create();
    	// if john mentioned jane in a reply
    	$reply = factory('App\Reply')->make(['body' => 'hello @jane take a look at @yaku.']);

    	$this->json('post', $thread->path() . '/replies', $reply->toArray());
    	// jane should get a notification
    	$this->assertCount(1, $jane->notifications);
    }

    /** @test */
    public function it_can_fetch_mentioned_users_when_given_some_characters()
    {
        factory('App\User')->create(['name' => 'johndoe']);
        factory('App\User')->create(['name' => 'johnny']);
        factory('App\User')->create(['name' => 'adah']);

        $result = $this->json('GET', '/api/users', ['name' => 'john']);

        $this->assertCount(2, $result->json());
    }
}
