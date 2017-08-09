<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class MentionsUsersTest extends TestCase
{
	use DatabaseMigrations;

	/**
	 * @test
	 */
	function it_mentions_users()
	{
		$john = create('App\User', ['name' => 'johnDoe']);

		$this->signIn($john);

		$jane = create('App\User', ['name' => 'janeDoe']);

		$thread = create('App\Thread');

		$reply = make('App\Reply', [
			'body' => 'hey @janeDoe.'
		]);

		$this->json('post', $thread->path() . '/replies', $reply->toArray());
		$this->assertCount(1, $jane->notifications);
	}
}
