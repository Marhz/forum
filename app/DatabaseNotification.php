<?php

namespace App;

use Carbon\Carbon;
use Illuminate\Notifications\DatabaseNotification as Daddy;

class DatabaseNotification extends Daddy
{
    public static function findSimilarNotification($subscriptionId, $threadId, $replyOwnerId)
    {
		return static::where('notifiable_id', $subscriptionId)
			->where("read_at", null)
			->where("data", "like", '%"thread_id":'.$threadId."%")
			->where("data", "like", '%"user_id":'.$replyOwnerId."%")
			->first();
    }

    public function refresh()
    {
		$this->created_at = Carbon::now();
		$this->updated_at = Carbon::now();
		$this->save();
    }
}
