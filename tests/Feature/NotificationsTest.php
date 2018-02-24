<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Notifications\DatabaseNotification;

class NotificationsTest extends TestCase
{
	use RefreshDatabase;

	public function setUp()
	{
		parent::setUp();

		$this->be($user = factory('App\User')->create());
	}

    /** @test */
    function a_thread_subscriber_is_sent_a_notification_when_a_new_reply_is_added_by_others_to_the_thread()
    {
    	$thread = factory('App\Thread')->create()->subscribe();

    	$this->assertCount(0, auth()->user()->notifications);

    	$thread->addReply([
    		'user_id' => auth()->id(), 
    		'body' => 'This is a dummy reply'
    	]);

    	$this->assertCount(0, auth()->user()->fresh()->notifications);

    	$thread->addReply([
    		'user_id' => factory('App\User')->create()->id, 
    		'body' => 'This is a dummy reply'
    	]);

    	$this->assertCount(1, auth()->user()->fresh()->notifications);
    }

    /** @test */
    function a_user_can_fetch_their_unread_notifications()
    {
    	factory(DatabaseNotification::class)->create();

    	$response = $this->getJson('profiles/' . auth()->user()->name . '/notifications')->json();

    	$this->assertCount(1, $response);
    }

    /** @test */
    function a_user_can_mark_their_notifications_as_read()
    {
    	factory(DatabaseNotification::class)->create();

    	$this->assertCount(1, auth()->user()->unreadNotifications);

    	$notificationId = auth()->user()->unreadNotifications->first()->id;

    	$endpoint = "/profiles/". auth()->user()->name. "/notifications/{$notificationId}";
    	$this->delete($endpoint);

    	$this->assertCount(0, auth()->user()->fresh()->unreadNotifications);
    }
}
