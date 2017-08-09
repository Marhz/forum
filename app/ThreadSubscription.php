<?php

namespace App;

use App\DatabaseNotification;
use App\Notifications\ThreadWasUpdated;
use Illuminate\Database\Eloquent\Model;

class ThreadSubscription extends Model
{
    protected $guarded = [];

    public function user()
    {
    	return $this->belongsTo(User::class);
    }

    // public function thread()
    // {
    // 	return $this->belongsTo(Thread::class);
    // }

    public function notify($thread, $reply)
    {
        if($notification = DatabaseNotification::findSimilarNotification($this->user->id, $thread->id, $reply->owner->id))
            $notification->refresh();
    	else
    	   $this->user->notify(new ThreadWasUpdated($thread, $reply));
    }
}