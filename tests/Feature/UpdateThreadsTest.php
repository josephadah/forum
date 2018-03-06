<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class UpdateThreadsTest extends TestCase
{
	use RefreshDatabase;
	
	/** @test */ 
    function thread_must_contain_title_and_body_before_it_is_updated()
    {
      $this->signIn();

      $thread = factory('App\Thread')->create(['user_id' => auth()->id()]);

      $this->patch($thread->path(), ['title' => 'changed'])
          ->assertSessionHasErrors('body');

      $thread = factory('App\Thread')->create(['user_id' => auth()->id()]);

      $this->patch($thread->path(), ['body' => 'changed'])
          ->assertSessionHasErrors('title');
    }

    /** @test */ 
    function an_authorized_user_can_edit_a_thread()
    {
      $this->signIn();

      $thread = factory('App\Thread')->create(['user_id' => auth()->id()]);

      $this->patch($thread->path(), ['title' => 'Changed', 'body' => 'Changed body']);

      $thread = $thread->fresh();

      $this->assertEquals('Changed', $thread->title);
      $this->assertEquals('Changed body', $thread->body);
    }


    /** @test */ 
    function an_unauthorized_user_cannot_edit_a_thread()
    {
      $this->signIn();

      $thread = factory('App\Thread')->create(['user_id' => factory('App\User')->create()]);

      $this->patch($thread->path(), ['title' => 'Changed', 'body' => 'Changed body'])
            ->assertStatus(403);
    }
}
