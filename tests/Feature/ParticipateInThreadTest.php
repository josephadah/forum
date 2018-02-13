<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ParticipateInThreadTest extends TestCase
{
	use RefreshDatabase;

	/** @test */
	public function an_unauthenticated_user_cannot_participate_in_a_thread()
	{
      $this->withExceptionHandling();

		$this->post('/threads/1/replies', [])
         ->assertRedirect('/login');
	}

   /** @test */
   public function an_authenticated_user_can_participate_in_a_thread()
   {
   	$this->be($user = factory('App\User')->create());

   	$thread = factory('App\Thread')->create();

   	$reply = factory('App\Reply')->make();

   	//when the user adds a reply to the thread
   	$this->post('/threads/'.$thread->id.'/replies', $reply->toArray());

   	//then their reply should be visible on the page
   	$this->get($thread->path())
   		->assertSee($reply->body);
   }

   /** @test */
   public function an_unauthorized_user_cannot_delete_a_reply()
   {
      $this->withExceptionHandling();

      $replyCreator = factory('App\User')->create();

      $reply = factory('App\Reply')->create(['user_id' => $replyCreator->id]);

      $this->delete('/reply/'.$reply->id)
          ->assertRedirect('login');

      $this->be($user = factory('App\User')->create());

      $this->delete('/reply/'.$reply->id)
          ->assertStatus(403);
   }

   /** @test */
   public function an_authorized_user_can_delete_a_reply()
   {
      $this->be($user = factory('App\User')->create());

      $reply = factory('App\Reply')->create(['user_id' => $user->id]);

      $this->delete('/reply/'.$reply->id)
            ->assertStatus(302);
      $this->assertDatabaseMissing('replies', ['id' => $reply->id]);
   }

   /** @test */
   public function an_unauthorized_user_cannot_update_a_reply()
   {
      $this->withExceptionHandling();

      $replyCreator = factory('App\User')->create();

      $reply = factory('App\Reply')->create(['user_id' => $replyCreator->id]);

      $this->patch('/reply/'.$reply->id)
          ->assertRedirect('login');

      $this->be($user = factory('App\User')->create());

      $this->patch('/reply/'.$reply->id)
          ->assertStatus(403);
   }

   /** @test */
   public function an_authorized_user_can_update_a_reply()
   {
      $this->be($user = factory('App\User')->create());

      $reply = factory('App\Reply')->create(['user_id' => $user->id]);
      $updatedBody = "New updated reply body";

      $this->patch("/reply/{$reply->id}", ['body' => $updatedBody]);
      $this->assertDatabaseHas('replies', ['id' => $reply->id, 'body' => $updatedBody]);
   }
}
