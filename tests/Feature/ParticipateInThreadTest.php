<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Exception;

class ParticipateInThreadTest extends TestCase
{
	use RefreshDatabase;

	/** @test */
	public function an_unauthenticated_user_cannot_participate_in_a_thread()
	{
      $this->withExceptionHandling();

		$this->post('/threads/channel/1/replies', [])
         ->assertRedirect('/login');
	}

   /** @test */
   public function an_authenticated_user_can_participate_in_a_thread()
   {
    $this->withoutExceptionHandling();
   	$this->be($user = factory('App\User')->create());

   	$thread = factory('App\Thread')->create();

   	$reply = factory('App\Reply')->make();

   	//when the user adds a reply to the thread
   	$this->post('/threads/channel/'.$thread->slug.'/replies', $reply->toArray());

   	$this->assertDatabaseHas('replies', ['thread_id' => $thread->id]);
      $this->assertEquals(1, $thread->fresh()->replies_count);
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
      $this->assertEquals(0, $reply->thread->fresh()->replies_count);
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

    /** @test */
    function replies_that_contain_spam_may_not_be_created()
    {
        $this->be(factory('App\User')->create());

        $thread = factory('App\Thread')->create();
        $reply = factory('App\Reply')->make([
            'body' => 'Yahoo Customer Support'
        ]);

        $this->json('post', $thread->path() . '/replies', $reply->toArray())
          ->assertStatus(422);
    }

    /** @test */
    function a_user_may_only_reply_once_per_minute()
    {
        $this->withExceptionHandling();
        $this->signIn();

        $thread = factory('App\Thread')->create();
        $reply = factory('App\Reply')->make([
            'body' => 'Some reply comment'
        ]);

        $this->post($thread->path() . '/replies', $reply->toArray())
            ->assertStatus(200);

        $this->json('post', $thread->path() . '/replies', $reply->toArray())
            ->assertStatus(422);
    }

}
