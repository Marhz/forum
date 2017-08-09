<?php

namespace App;

use App\Events\ThreadReceivedNewRemply;
use App\Notifications\ThreadWasUpdated;
use Illuminate\Database\Eloquent\Model;

class Thread extends Model
{
    use RecordsActivity;
    use Favoritable;

    protected $guarded = [];
    protected $subscribedTo;
    protected $withAppends = ['favoritesCount', 'isFavorited', 'isSubscribedTo'];
    protected $withRelations = ['channel', 'creator'];

    protected static function boot()
    {
        parent::boot();

        // static::addGlobalScope('replyCount', function ($builder) {
        //     $builder->withCount('replies');
        // });

        static::deleting(function ($thread) {
            $thread->replies->each->delete();
            // $thread->replies->each(function ($reply) {
            //     $reply->delete();
            // });
        });
    }

    public function path()
    {
    	return "/threads/" . $this->channel->slug . '/'. $this->id;
    }

    public function replies()
    {
    	return $this->HasMany(Reply::class);
    }

    public function creator()
    {
    	return $this->belongsTo(User::class, 'user_id');
    }

    public function addReply($reply)
    {
    	$reply = $this->replies()->create($reply);
        $reply->owner = auth()->user();
        event(new ThreadReceivedNewRemply($reply));
        return $reply;
    }

    public function channel()
    {
        return $this->belongsTo(Channel::class);
    }

    public function scopeFilter($query, $filters)
    {
        return $filters->apply($query);
    }

    public function subscribe($userId = null)
    {
        $this->subscriptions()->create([
            'user_id' => $userId ?: auth()->id()
        ]);
        return $this;
    }

    public function unsubscribe($userId = null)
    {
        $this->subscriptions()->where('user_id', $userId ?: auth()->id())->delete();
    }

    public function subscriptions()
    {
        return $this->hasMany(ThreadSubscription::class);
    }

    public function getIsSubscribedToAttribute()
    {
        if($this->subscribedTo !== null){
            return $this->subscribedTo;
        }
        return $this->subscribedTo = $this->subscriptions()->where('user_id', auth()->id())->exists();
    }

    public function loadRelations()
    {
        $this->load($this->withRelations);
        $this->appends = $this->withAppends;
        return $this;
    }

    public function hasUpdateFor(User $user)
    {
        return $this->updated_at > cache($user->visitedThreadCacheKey($this));
    }
}
