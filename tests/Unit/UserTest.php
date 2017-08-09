<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class UserTest extends TestCase
{
	use DatabaseMigrations;

	/**
	 * @test
	 */
	function it_can_fetch_its_most_recent_reply()
	{
		$user = create('App\User');
		$reply = create('App\Reply', ['user_id' => $user->id]);

		$this->assertEquals($user->lastReply->id, $reply->id);

		$reply = create('App\Reply');

		$this->assertNotEquals($user->lastReply->id, $reply->id);
	}
}
