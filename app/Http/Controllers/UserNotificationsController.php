<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Http\Request;

class UserNotificationsController extends Controller
{
	public function __construct()
	{
		$this->middleware('auth');	
	}

	public function index($user)
	{
		return auth()->user()->unreadNotifications;
	}

    public function destroy($user, $notificationId)
    {
    	auth()->user()->notifications()->findOrFail($notificationId)->markAsRead();
    }
}
