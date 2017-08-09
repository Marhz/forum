<?php

namespace Tests\Features;

use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class FavoritesTest extends TestCase
{
	use DatabaseMigrations;

	/**
	 * @test
	 */
	function guest_cannot_favorite()
	{
		$this->withExceptionHandling()
			->post('replies/1/favorites')
			->assertRedirect('/login');
	}

    /**
     * @test
     */
	function an_authenticated_user_can_favorite_replies()
	{
		$this->signIn();
		$reply = create('App\Reply');
		$this->post('replies/'. $reply->id. '/favorites');
		$this->assertCount(1, $reply->favorites);
	}

    /**
     * @test
     */
	function an_authenticated_user_can_unfavorite_replies()
	{
		$this->signIn();
		$reply = create('App\Reply');
		$reply->favorite();
		$this->delete('replies/'. $reply->id. '/favorites/delete');
		$this->assertCount(0, $reply->favorites);
	}

	/**
	 * @test
	 */
	function a_user_can_only_favorite_once()
	{
		$this->signIn();
		$reply = create('App\Reply');
		try {
			$this->post('replies/'. $reply->id. '/favorites');
			$this->post('replies/'. $reply->id. '/favorites');
		}
		catch (\Exception $e) {
			$this->fail('Failed');
		}
		
		$this->assertCount(1, $reply->favorites);		
	}
}