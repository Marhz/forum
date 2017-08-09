<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Notifications\ThreadWasUpdated;
use Illuminate\Support\Facades\Notification;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Support\Testing\Fakes\NotificationFake;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class ThreadTest extends TestCase
{
	use DatabaseMigrations;

	protected $thread;

	public function setUp()
	{
		parent::setUp();
    	$this->thread = factory('App\Thread')->create();
	}

    /**
     * @test
     */
    public function thread_has_replies()
    {
        $this->assertInstanceOf('Illuminate\Database\Eloquent\Collection', $this->thread->replies);
    }

    /**
     * @test
     */
    function thread_belongs_to_user()
    {
        $this->assertInstanceOf('App\User', $this->thread->creator);	
    }

    /**
     * @test
     */
    function it_can_add_a_reply()
    {
    	$this->thread->addReply([
    		'body' => 'foo',
    		'user_id' => 1
    	]);
    	$this->assertCount(1, $this->thread->replies);
    }

    /**
     * @test
     */
    function a_thread_notifies_subscribers_when_it_has_a_new_reply()
    {
        Notification::fake();
        $this->signIn()
            ->thread
            ->subscribe()
            ->addReply([
            "body" => "hey",
            "user_id" => create('App\User')->id
        ]);
        Notification::assertSentTo(auth()->user(), ThreadWasUpdated::class);
    }

    /**
     * @test
     */
    function it_belongs_to_a_channel()
    {
        $this->assertInstanceOf('App\Channel', $this->thread->channel);
    }

    /**
     * @test
     */
    function it_can_make_a_path()
    {
        $this->assertEquals('/threads/'.$this->thread->channel->slug.'/'.$this->thread->id,$this->thread->path());
    }

    /**
     * @test
     */
    function it_can_be_subscribed_to()
    {
        $thread = create('App\Thread');
        $thread->subscribe($userId = 1);

        $this->assertEquals(
            1,
            $thread->subscriptions()->where('user_id', $userId)->count()
        );
    }

    /**
     * @test
     */
    function it_can_be_unsubscribed_from()
    {
        $thread = create('App\Thread');
        $thread->subscribe($userId = 1);
        $thread->unsubscribe($userId);
        $this->assertCount(
            0,
            $thread->subscriptions
        );
    }

    /**
     * @test
     */
    function it_knows_if_auth_is_subscribed_to_it()
    {
        $thread = create('App\Thread');
        $this->signIn();
        $this->assertFalse($thread->isSubscribedTo);
        $thread->subscribe();
        $this->assertTrue($thread->fresh()->isSubscribedTo);
    }

    /**
     * @test
     */
    function it_can_check_if_an_authenticated_user_has_red_all_replies()
    {
        $this->signIn();
        $thread = create('App\Thread');
        $this->assertTrue($thread->hasUpdateFor(auth()->user()));
        $this->get("/threads/{$thread->channel->name}/{$thread->id}");
        $this->assertFalse($thread->hasUpdateFor(auth()->user()));
    }
}
