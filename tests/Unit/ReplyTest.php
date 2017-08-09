<?php

namespace Tests\Unit;

use Carbon\Carbon;
use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class ReplyTest extends TestCase
{
	use DatabaseMigrations;
    /**
     * @test
     */
    public function reply_has_owner()
    {
        $reply = factory('App\Reply')->create();
        $this->assertInstanceOf('App\User', $reply->owner);
    }

    /**
     * @test
     */
    function it_knows_it_was_just_published()
    {
    	$reply = create('App\Reply');

    	$this->assertTrue($reply->wasJustPublished());

    	$reply->created_at = Carbon::now()->subMonth();

    	$this->assertFalse($reply->wasJustPublished());
    }

    /**
     * @test
     */
    function it_can_detect_mentioned_users()
    {
        $reply = create('App\Reply', [
            'body' => 'hello @user1 @user2'
        ]);

        $this->assertEquals(['user1', 'user2'], $reply->mentionedUsers());
    }
}