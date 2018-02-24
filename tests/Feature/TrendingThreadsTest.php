<?php

namespace Tests\Feature;

use App\Trending;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Redis;
use Tests\TestCase;

class TrendingThreadsTest extends TestCase
{
	use RefreshDatabase;

	public function setUp()
	{
		parent::setUp();

		$this->trending = new Trending;

		$this->trending->reset();
	}

    /**
     * A basic test example.
     @test
     */
    public function it_increments_a_threads_score_each_time_it_is_read()
    {
    	$this->assertEmpty($this->trending->get());

        $thread = factory('App\Thread')->create();

        $this->call('GET', $thread->path());

        $trending = $this->trending->get();

        $this->assertCount(1, $trending);

        $this->assertEquals($thread->title, $trending[0]->title);
    }
}
