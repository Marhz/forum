<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class CreateThreadsTest extends TestCase
{

	use DatabaseMigrations;

	/**
	 * @test
	 */
	function auth_user_can_create_threads()
	{
		$this->signIn();
		$thread = make('App\Thread');
		$response = $this->post('/threads', $thread->toArray());
		$this->get($response->headers->get('location'))
			 ->assertSee($thread->title)
			 ->assertSee($thread->body);
	}

	/**
	 * @test
	 */
	function guess_cannot_create_thread()
	{
		$this->withexceptionhandling();

		$this->get('threads/create')
			->assertRedirect('/login');	
		$this->post('threads/')
			->assertRedirect('/login');
	}

	/**
	 * @test
	 */
	function it_requires_a_title()
	{
		$this->publishThread(['title' => null])
			->assertSessionHasErrors();
	}

	/**
	 * @test
	 */
	function it_requires_a_body()
	{
		$this->publishThread(['body' => null])
			->assertSessionHasErrors();
	}

	/**
	 * @test
	 */
	function it_requires_a_channel()
	{
		$channels = factory('App\Channel', 2)->create();
		$this->publishThread(['channel_id' => 999])
			->assertSessionHasErrors();
	}


	public function publishThread($overrides = [])
	{
		$this->withExceptionHandling()->signIn();
		$thread = make('App\Thread', $overrides);
		return $this->post('/threads', $thread->toArray());					
	}

	/**
	 * @test
	 */
	function thread_can_only_be_deleted_by_owner_or_admin()
	{
		$this->withExceptionHandling();
		
		$thread = create('App\Thread');
		$response = $this->delete($thread->path())->assertRedirect('/login');
		$this->signIn();
		$response = $this->delete($thread->path())->assertStatus(403);
	}

	/**
	 * @test
	 */
	function a_thread_can_be_deleted()
	{
		$this->signIn();
		$thread = create('App\Thread', ['user_id' => auth()->id()]);
		$reply = create('App\Reply', ['thread_id' => $thread->id]);
		$response = $this->json('DELETE', $thread->path());
		$response->assertStatus(204);
		$this->assertDatabaseMissing('threads', ['id' => $thread->id]);
		$this->assertDatabaseMissing('replies', ['id' => $reply->id]);
		$this->assertDatabaseMissing('activities', ['subject_id' => $thread->id, 'subject_type' => get_class($thread)]);
		$this->assertDatabaseMissing('activities', ['subject_id' => $reply->id, 'subject_type' => get_class($reply)]);
	}
}
