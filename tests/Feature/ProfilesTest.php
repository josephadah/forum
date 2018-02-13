<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ProfilesTest extends TestCase
{
	use RefreshDatabase;
	
    /** @test */
    function a_user_has_a_profile_page()
    {
    	$user = factory('App\User')->create();

    	$this->get('/profiles/' . $user->name)
    		->assertSee($user->name);
    }

    /** @test */
    function profile_page_contains_posts_by_profile_owner()
    {
        $this->be($user = factory('App\User')->create());
        
    	$thread = factory('App\Thread')->create(['user_id' => $user->id]);

    	$this->get('/profiles/' . $user->name)
    		->assertSee($thread->title)
    		->assertSee($thread->body);
    }
}
