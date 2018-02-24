<?php

namespace App\Listeners;

use App\Events\ThreadRecievedNewReply;
use App\User;
use App\Notifications\YouAreMentioned;

class NotifyMentionedUsers
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  ThreadRecievedNewReply  $event
     * @return void
     */
    public function handle(ThreadRecievedNewReply $event)
    {
        User::whereIn('name', $event->reply->mentionedUsers())
        ->get()
        ->each(function($user) use ($event) {
            $user->notify(new YouAreMentioned($event->reply));
        });
        $mentionedUsers = $event->reply->mentionedUsers();
    }
}
