<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class ThreadsTest extends TestCase
{
    use DatabaseMigrations;

    public function setUp()
    {
        parent::setup();
        $this->thread = factory('App\Thread')->create();
    }

    /**
     * @test
     */
    public function a_user_can_see_thrads()
    {
        $response = $this->get('/threads');
        $response->assertSee($this->thread->title);
    }

    /**
     * @test
     */
    public function a_user_can_see_a_single_thread()
    {
        $response = $this->get($this->thread->path());
        $response->assertSee($this->thread->title);
    }

    // /**
    //  * @test
    //  */
    // function user_can_read_thread_replies()
    // {
    //     $reply = factory('App\Reply')->create(['thread_id' => $this->thread->id]);
    //     $this->get($this->thread->path())
    //         ->assertSee($reply->body);
    // }

    /**
     * @test
     */
    function a_user_can_sort_threads_by_channel()
    {
        $channel = create('App\Channel');
        $threadInChannel = create('App\Thread', ['channel_id' => $channel->id]);
        $threadNotInChannel = create('App\Thread');
        $this->get('/threads/'.$channel->slug)
            ->assertSee($threadInChannel->title)
            ->assertDontSee($threadNotInChannel->title);
    }

    /**
     * @test
     */
    function a_user_can_filter_threads_by_author()
    {
        $this->signIn(create('App\User', ['name' => 'JohnDoe']));

        $threadByJohn = create('App\Thread', ['user_id' => auth()->id()]);
        $thread = create('App\Thread');
        $this->get('threads?by=JohnDoe')
            ->assertSee($threadByJohn->title)
            ->assertDontSee($thread->title);
    }

    /**
     * @test
     */
    function user_can_filter_threads_by_poplarity()
    {
        $threadWithTwoReplies = create('App\Thread');
        create('App\Reply', ['thread_id' => $threadWithTwoReplies->id], 2);
        $threadWithThreeReplies = create('App\Thread');
        create('App\Reply', ['thread_id' => $threadWithThreeReplies->id], 3);
        $threadWithNoReplies = $this->thread;
        $response = $this->getJson('threads?popular=1')->json();
        $this->assertEquals([3,2,0], array_column($response, 'replies_count'));
    }

    /**
     * @test
     */
    function user_can_filter_unanswered_threads()
    {
        $thread = create('App\Thread');
        create('App\Reply', ['thread_id' => $thread->id]);
        $response = $this->getJson('threads?unanswered=1')->json();
        $this->assertCount(1, $response);
    }

    /**
     * @test
     */
    function a_user_can_request_all_replies_for_a_thread()
    {
        $thread = create('App\Thread');
        create('App\Reply', ['thread_id' => $thread->id], 2);
        $response = $this->getJson($thread->path().'/replies')->json();
        $this->assertCount(2, $response['data']);
        $this->assertEquals(2, $response['total']);
    }
}
