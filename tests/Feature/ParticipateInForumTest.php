<?php

namespace Tests\Feature;

use Carbon\Carbon;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class ParticipateInForumTest extends TestCase
{
	use DatabaseMigrations;

	/**
	 * @test
	 */
	function logged_in_user_can_post_in_thread()
	{
		$this->be($user = factory('App\User')->create());
		$thread = factory('App\Thread')->create();
		$reply = factory('App\Reply')->make();
		$this->post($thread->path().'/replies', $reply->toArray());
		$this->assertDatabaseHas('replies', ['body' => $reply->body]);
		$this->assertEquals(1, $thread->fresh()->replies_count);
	}

	/**
	 * @test
	 */
	function not_logged_equals_no_replies()
	{
		$this->expectException('Illuminate\Auth\AuthenticationException');
		$this->post('threads/channel/1/replies', []);
	}

	/**
	 * @test
	 */
	function replies_requires_a_body()
	{
		$thread = create('App\Thread');
		$reply = make('App\Reply',['body' => null]);
		$this->withExceptionHandling()->signIn();
		$this->post($thread->path() . '/replies', $reply->toArray())
			->assertSessionHasErrors();
	}

	/**
	 * @test
	 */
	function unauthorized_users_cant_delete_reply()
	{
		$this->withExceptionHandling();
		$reply = create('App\Reply');
		$this->delete('/replies/'.$reply->id)
			->assertRedirect('login');
		$this->signIn();
		$this->delete('/replies/'.$reply->id)
			->assertStatus(403);
	}

	/**
	 * @test
	 */
	function authorized_users_can_delete_reply()
	{
		$this->signIn();
		$reply = create('App\Reply', ['user_id' => auth()->id()]);

		$this->delete('/replies/'.$reply->id)
			->assertStatus(302);
		$this->assertDatabaseMissing('replies', ['id' => $reply->id]);
		$this->assertEquals(0, $reply->thread->fresh()->replies_count);
	}

	/**
	 * @test
	 */
	function unauthorized_users_cant_update_reply()
	{
		$this->withExceptionHandling();
		$reply = create('App\Reply');
		$this->patch('/replies/'.$reply->id)
			->assertRedirect('login');
		$this->signIn();
		$this->patch('/replies/'.$reply->id)
			->assertStatus(403);
	}

	/**
	 * @test
	 */
	function authorized_users_can_update_reply()
	{
		$this->signIn();
		$reply = create('App\Reply', ['user_id' => auth()->id()]);

		$this->patch('/replies/'.$reply->id, ['body' => "yolo"]);
		$this->assertDatabaseHas('replies', ['id' => $reply->id, 'body' => "yolo"]);	
	}

	/**
	 * @test
	 */
	function it_detects_spam()
	{
		$this->withExceptionHandling();
		$this->signIn();
		$thread = create('App\Thread');
		$reply = make('App\Reply', [
			'body' => 'Yahoo Customer Support'
		]);
		$this->json('post', $thread->path() . '/replies', $reply->toArray())
			->assertStatus(422);
	}

	/**
	 * @test
	 */
	function a_user_can_only_post_once_every_minute()
	{
		$this->withExceptionHandling();
		$this->signIn();
		$thread = create('App\Thread');
		create('App\Reply', [
			'user_id' => auth()->id(), 
		]);
		$reply = make('App\Reply');
		$this->json('post', $thread->path() . '/replies', $reply->toArray())
			->assertStatus(429);
	}
}
