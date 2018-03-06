<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class LockThreadsTest extends TestCase
{
	use RefreshDatabase;

	/** @test */ 
	public function an_unauthorized_user_cannot_lock_a_thread()
	{
		$this->signIn();

		$thread = factory('App\Thread')->create();

		$this->post(route('locked-threads.store', $thread))
			->assertStatus(403);

		$this->assertFalse($thread->fresh()->locked);
	}

	/** @test */ 
	public function a_thread_can_be_locked_and_may_not_recieve_any_more_reply()
	{
		$this->signIn();

		$thread = factory('App\Thread')->create(['locked' => true]);

		$this->post($thread->path() . '/replies', [
			'body' => 'foo bar', 
			'user_id' => auth()->id()
		])->assertStatus(422);
	}

	/** @test */
	public function an_administrator_can_lock_any_thread()
	{
		$this->actingAs(factory('App\User')->create(['name' => 'John']));

		$thread = factory('App\Thread')->create();

		$this->post(route('locked-threads.store', $thread));

		$this->assertTrue($thread->fresh()->locked);
	}

	/** @test */
	public function an_administrator_can_unlock_any_thread()
	{
		$this->actingAs(factory('App\User')->create(['name' => 'John']));

		$thread = factory('App\Thread')->create(['locked' => true]);

		$this->delete(route('locked-threads.delete', $thread));

		$this->assertFalse($thread->fresh()->locked);

	}

}
