<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class BestReplyTest extends TestCase
{
	use RefreshDatabase;

	/** @test */
	function a_thread_creator_may_mark_any_reply_as_the_best_reply()
	{
		$this->withoutExceptionHandling();

		$this->signIn();

		$thread = factory('App\Thread')->create(['user_id' => auth()->id()]);

		$replies = factory('App\Reply', 2)->create(['thread_id' => $thread->id]);

		$this->assertFalse($replies[1]->isBest());

		$this->postJson(route('best-replies.store', [$replies[1]->id]));

		$this->assertTrue($replies[1]->fresh()->isBest());
	}

	/** @test */
	function only_a_thread_creator_can_mark_a_reply_as_the_best()
	{
		$this->signIn();

		$thread = factory('App\Thread')->create(['user_id' => auth()->id()]);

		$replies = factory('App\Reply', 2)->create(['thread_id' => $thread->id]);

		$this->assertFalse($replies[1]->isBest());

		$this->be($user2 = factory('App\User')->create());

		$this->postJson(route('best-replies.store', [$replies[1]->id]))
			->assertStatus(403);

		$this->assertFalse($replies[1]->fresh()->isBest());
	}

	/** @test */
	function if_a_best_reply_is_deleted_then_the_thread_is_properly_updated_to_reflect_that()
	{
		$this->signIn();

		$reply = factory('App\Reply')->create(['user_id' => auth()->id()]);

		$reply->thread->markBestReply($reply);

		$this->assertEquals($reply->id, $reply->thread->best_reply_id);

		$this->deleteJson(route('replies.delete', $reply));

		$this->assertNull($reply->thread->fresh()->best_reply_id);
	}
}
