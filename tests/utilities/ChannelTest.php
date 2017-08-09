<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class ChannelTest extends TestCase
{
	use DatabaseMigrations;

	public function setUp()
	{
		$this->channel = create('App\Channel');
	}

	/**
	 * @test
	 */
	function it_consits_threads()
	{
		$thread = create('thread', ['channel_id' => $this->channel->id]);
		$this->assertTrue($channel->threads->contains($thread));
	}
}
