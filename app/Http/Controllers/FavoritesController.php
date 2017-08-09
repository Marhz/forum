<?php

namespace App\Http\Controllers;

use App\Reply;
use App\Thread;
use App\Favorite;
use Illuminate\Http\Request;

class FavoritesController extends Controller
{
	public function __construct()
	{
		$this->middleware('auth');
	}

    public function storeReply(Reply $reply)
    {
		$reply->favorite();
        if(request()->expectsJson())
            return response(['status' => 'Favorite added']);
		return back();
    }

    public function storeThread(Thread $thread)
    {
    	$thread->favorite();
        if(request()->expectsJson())
            return response(['status' => 'Favorite removed']);
    	return back();
    }

    public function destroyReply(Reply $reply)
    {
        $reply->unfavorite();
        if(request()->expectsJson())
            return response(['status' => 'Favorite added']);
        return back();
    }
    public function destroyThread(Thread $thread)
    {
        $thread->unfavorite();
        if(request()->expectsJson())
            return response(['status' => 'Favorite removed']);
        return back();
    }
}
