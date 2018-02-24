<?php

namespace App\Listeners;

use App\Events\ThreadRecievedNewReply;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class NotifySubscriber
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
        $thread = $event->reply->thread;

        $thread->subscriptions
                ->where('user_id', '!=', $event->reply->user_id)
                ->each
                ->notify($event->reply);
    }
}
