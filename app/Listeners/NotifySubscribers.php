<?php

namespace App\Listeners;

use App\ThreadSubscription;
use App\Events\ThreadReceivedNewRemply;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class NotifySubscribers
{
    /**
     * Handle the event.
     *
     * @param  ThreadReceivedNewRemply  $event
     * @return void
     */
    public function handle(ThreadReceivedNewRemply $event)
    {
        ThreadSubscription::where('thread_id', $event->reply->thread->id)
            ->with('user')
            ->where('user_id', '!=', $event->reply->user_id)
            ->get()
            ->each
            ->notify($event->reply->thread, $event->reply);
    }
}
