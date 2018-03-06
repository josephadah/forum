<?php

namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Carbon\Carbon;

class ReplyTest extends TestCase
{
	use RefreshDatabase;
	
	/** @test */
	function it_has_an_owner()
	{
		$reply = factory('App\Reply')->create();

		$this->assertInstanceOf('App\User', $reply->owner);
	}

	/** @test */
	function it_know_if_it_was_just_published()
	{
		$reply = factory('App\Reply')->create();

		$this->assertTrue($reply->wasJustPublished());

		$reply->created_at = Carbon::now()->subMonth();

		$this->assertFalse($reply->wasJustPublished());
	}

	/** @test */
	function it_know_all_mentioned_users_in_the_body()
	{
		$this->withoutExceptionHandling();
		$reply = factory('App\Reply')->create([
			'body' => '@john are you with @jane.'
		]);

		$this->assertEquals(['john', 'jane'], $reply->mentionedUsers());
	}

	/** @test */ 
	function it_wraps_all_mentioned_username_within_an_anchor_tag_that_leads_to_the_users_profile()
	{
		$reply = new \App\Reply([
			'body' => 'Hello @john. cool.'
		]);

		$this->assertEquals(
			'Hello <a href="/profiles/john">@john</a>. cool.',
			$reply->body
		);
	}

	/** @test */
	function it_knows_if_it_is_the_best_reply()
	{
		$reply = factory('App\Reply')->create();

		$this->assertFalse($reply->isBest());

		$reply->thread->update(['best_reply_id' => $reply->id]);

		$this->assertTrue($reply->fresh()->isBest());
	}

}
