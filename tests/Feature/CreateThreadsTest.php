<?php

namespace Tests\Feature;

use App\Activity;
use App\Thread;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class CreateThreadsTest extends TestCase
{
	use RefreshDatabase;

	/** @test */
	function guests_cannot_create_a_thread()
	{
    $this->withExceptionHandling();

    $this->get('/threads/create')
          ->assertRedirect('/login');

    $this->post('/threads')
          ->assertRedirect('/login');
	}


    /** @test */
    function an_unconfirmed_user_can_not_create_a_thread()
    {
      $this->withoutExceptionHandling();
      $this->be($user = factory('App\User')->create());

      $this->assertFalse($user->confirmed);

      $thread = factory('App\Thread')->make();

      $this->post('/threads', $thread->toArray())
            ->assertRedirect('/threads');
      $this->assertDatabaseMissing('threads', ['body' => $thread->body]);
    }


  	/** @test */
  	function an_authenticated_and_confirm_user_can_create_a_thread()
  	{
  		$this->be($user = factory('App\User')->create());

      $user->confirmed = true;

  		$thread = factory('App\Thread')->create();

  		$response = $this->post('/threads', $thread->toArray());

      $this->assertTrue($user->confirmed);

  		$this->get($response->headers->get('location'))
  		->assertSee($thread->title)
  		->assertSee($thread->body);
  	}


    /** @test */
    function an_unauthorized_user_cannot_delete_a_thread()
    {
      $this->withExceptionHandling();

      $threadCreator = factory('App\User')->create();

      $thread = factory('App\Thread')->create(['user_id' => $threadCreator->id]);

      $this->delete($thread->path())
          ->assertRedirect('login');

      $this->be($user = factory('App\User')->create());

      $this->delete($thread->path())
          ->assertStatus(403);
    }

    /** @test */
    function an_authorized_user_can_delete_a_thread()
    {
      $this->be($user = factory('App\User')->create());

      $thread = factory('App\Thread')->create(['user_id' => $user->id]);

      $reply = factory('App\Reply')->create(['thread_id' => $thread->id]);

      $this->json('DELETE', $thread->path());

      $this->assertDatabaseMissing('threads', ['id' => $thread->id]);
      $this->assertDatabaseMissing('replies', ['id' => $reply->id]);

      $this->assertEquals(0, Activity::count());
    }

    /** @test */
    function an_authenticated_user_must_confirmed_their_email_before_publishing_thread()
    {
      $this->withoutExceptionHandling();
      
      $this->be($user = factory('App\User')->create());

      $thread = factory('App\Thread')->make(['user_id' => $user->id]);

      $this->post('/threads', $thread->toArray())
          ->assertRedirect('/threads')
          ->assertSessionHas('flash', 'You must confirm your email address to publish post.');
    }

    // /** @test */
    // function a_thread_requires_a_unique_slug()
    // {
    //   $this->withoutExceptionHandling();
    //   $this->signIn();

    //   $thread = factory('App\Thread')->create(['title' => 'Foo Title', 'slug' => 'foo-title']);

    //   $this->assertEquals($thread->fresh()->slug, 'foo-title');

    //   $thread2 = factory('App\Thread')->create(['title' => 'Foo Title', 'slug' => 'foo-title']);

    //   $this->post('/threads', $thread2->toArray());

    //   $this->assertTrue(Thread::whereSlug('foo-title-2')->exists());
    // }

}
