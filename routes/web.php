<?php

use App\User;
use App\Thread;
use Illuminate\Support\Facades\Redis;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
	// for($i = 0; $i < 100; $i++){
	//  	$thread = factory('App\Thread')->create();
	//  	$nbReplies = rand(0,50);
	//  	for($y = 0; $y < $nbReplies; $y++) {
	//  		factory('App\Reply')->create(['thread_id' => $thread->id]);
	//  	}
	// }
 //    return view('welcome');
});

Auth::routes();

Route::get('/home', 'HomeController@index');

Route::get('/threads', 'ThreadsController@index');
Route::get('threads/{chanel}/{thread}', 'ThreadsController@show');
Route::delete('threads/{chanel}/{thread}', 'ThreadsController@destroy');
Route::post('/threads', 'ThreadsController@store');
Route::get('threads/create', 'ThreadsController@create');
Route::post('/threads/{channel}/{thread}/replies', 'RepliesController@store');
Route::get('/threads/{channel}/{thread}/replies', 'RepliesController@index');
Route::get('threads/{channel}', 'ThreadsController@index');
Route::post('replies/{reply}/favorites', 'FavoritesController@storeReply');
Route::delete('threads/{thread}/favorites/delete', 'FavoritesController@destroyThread');
Route::delete('replies/{reply}/favorites/delete', 'FavoritesController@destroyReply');
Route::delete('/replies/{reply}', 'RepliesController@destroy');
Route::patch('/replies/{reply}', 'RepliesController@update');
Route::post('threads/{thread}/favorites', 'FavoritesController@storeThread');
Route::post('threads/{channel}/{thread}/subscriptions', 'ThreadSubscriptionsController@store')->middleware('auth');
Route::delete('threads/{channel}/{thread}/subscriptions', 'ThreadSubscriptionsController@destroy')->middleware('auth');

Route::get('/profiles/{user}', 'ProfilesController@show')->name('profile');
Route::get('/profiles/{user}/notifications', 'UserNotificationsController@index');
Route::delete('/profiles/{user}/notifications/{notification}', 'UserNotificationsController@destroy');
