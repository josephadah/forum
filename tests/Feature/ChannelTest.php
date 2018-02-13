<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ChannelTest extends TestCase
{
	use RefreshDatabase;

    /** @test */
    function a_channel_has_threads()
    {
    	$channel = factory('App\Channel')->create();

    	$thread = factory('App\Thread')->create(['channel_id' => $channel->id]);

    	$this->assertTrue($channel->threads->contains($thread));
    }
 
}
