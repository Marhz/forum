<?php

namespace App\Http\Controllers;

use App\User;
use App\Reply;
use App\Thread;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use App\Events\ThreadReceivedNewRemply;
use App\Http\Requests\CreatePostRequest;
use App\Notifications\MentionNotification;

class RepliesController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth', ['except' => 'index']);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($channelId, Thread $thread)
    {
        return $thread->replies()->paginate(20);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store($channelId, Thread $thread, CreatePostRequest $request)
    {
        $reply = $thread->addReply([
            'body' => request('body'),
            'user_id' => auth()->id()
        ]);
        if(request()->expectsJson())
            return $reply;
        return back();
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Reply  $reply
     * @return \Illuminate\Http\Response
     */
    
    public function update(Request $request, Reply $reply)
    {
        $this->authorize('update', $reply);

        $this->validate(request(), [
            'body' => 'required|spamfree'
        ]);
        $reply->update(['body' => request('body')]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Reply  $reply
     * @return \Illuminate\Http\Response
     */
    public function destroy(Reply $reply)
    {
        $this->authorize('update', $reply);
        $reply->delete();
        if(request()->expectsJson())
            return response(['status' => 'Reply erased']);
        return back();
    }
}
