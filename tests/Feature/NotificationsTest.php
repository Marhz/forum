<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Notifications\DatabaseNotification;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class NotificationsTest extends TestCase
{
	use DatabaseMigrations;

	public function setUp()
	{
		parent::setUp();
		$this->signIn();
	}

	/**
	 * @test
	 */
	function a_notif_is_prepared_when_a_subscribed_thread_receives_a_reply_from_another_dude()
	{
		$thread = create('App\Thread')->subscribe();
		$this->assertCount(0, auth()->user()->notifications);

		$thread->addReply([
			'user_id' => auth()->id(),
			'body' => 'frffazfe'
		]);
		$this->assertCount(0, auth()->user()->fresh()->notifications);

		$thread->addReply([
			'user_id' => create('App\User')->id,
			'body' => 'frzffzaef'
		]);
		$this->assertCount(1, auth()->user()->fresh()->notifications);
	}

	/**
	 * @test
	 */
	function a_user_can_get_his_unread_notifications()
	{
		create(DatabaseNotification::class);
		$user = auth()->user();
		$response = $this->getJson("/profiles/{$user->name}/notifications")->json();
		$this->assertCount(1, $response);		
	}

	/**
	 * @test
	 */
	function a_user_can_clear_a_notification()
	{
		create(DatabaseNotification::class);
		$user = auth()->user();
		$this->assertCount(1, $user->unreadNotifications);
		$notificationId = $user->unreadNotifications->first()->id;
		$this->delete("/profiles/{$user->name}/notifications/{$notificationId}");

		$this->assertCount(0, $user->fresh()->unreadNotifications);
	}

	/**
	 * @test
	 */
	function it_doesnt_add_notification_if_the_new_reply_is_from_the_same_thread_and_user()
	{
		$thread = create('App\Thread')->subscribe();
		$otherUser = create('App\User')->id;
		$thread->addReply([
			'user_id' => $otherUser,
			'body' => 'frffazfe'
		]);
		$this->assertCount(1, auth()->user()->fresh()->notifications);

		$thread->addReply([
			'user_id' => $otherUser,
			'body' => 'frzffzaef'
		]);
		$this->assertCount(1, auth()->user()->fresh()->notifications);		
	}
}