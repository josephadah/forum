<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Activity;
use Illuminate\Support\Carbon;

class ActivitiesTest extends TestCase
{
	use RefreshDatabase;

    /** @test */
    function records_activity_when_a_thread_is_created()
    {
    	$this->be($user = factory('App\User')->create());

    	$thread = factory('App\Thread')->create(['user_id' => $user->id]);

    	$this->assertDatabaseHas('activities', [
    		'type' => 'created_thread', 
    		'user_id' => auth()->id(),
    		'subject_id' => $thread->id,
    		'subject_type' => 'App\Thread'
    	]);

    	$activity = Activity::first();

    	$this->assertEquals($activity->subject->id, $thread->id);
    }

    /** @test */
    function records_activity_when_a_reply_is_created()
    {
    	$this->be($user = factory('App\User')->create());

    	factory('App\Reply')->create();

    	$this->assertEquals(2, Activity::count());
    }


    /** @test */
    function it_fetches_the_activity_feed_for_any_user()
    {
        $this->be($user = factory('App\User')->create());

        factory('App\Thread', 2)->create(['user_id' => $user->id]);

        $user->activity()->first()->update(['created_at' => Carbon::now()->subWeek()]);

        $feed = Activity::feed($user);

        $this->assertTrue($feed->keys()->contains(
            Carbon::now()->format('Y-m-d')
        ));

        $this->assertTrue($feed->keys()->contains(
            Carbon::now()->subWeek()->format('Y-m-d')
        ));
    }

}
