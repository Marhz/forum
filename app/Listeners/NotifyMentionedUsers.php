<?php

namespace App\Listeners;

use App\User;
use App\Events\ThreadReceivedNewRemply;
use App\Notifications\MentionNotification;

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
     * @param  ThreadReceivedNewRemply  $event
     * @return void
     */
    public function handle(ThreadReceivedNewRemply $event)
    {
        User::whereIn('name', $event->reply->mentionedUsers())
            ->each(function ($user) use ($event) {
                $user->notify(new MentionNotification($event->reply));
            });
    }
}
