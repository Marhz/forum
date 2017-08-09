<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Inspections\Spam;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class SpamTest extends TestCase
{
	use DatabaseMigrations;

	/**
	 * @test
	 */
	function it_checks_for_invalid_keywords()
	{
		$spam = new Spam();

		$this->assertFalse($spam->detect('not a spam'));
		$this->expectException(\Exception::class);
		$spam->detect('Yahoo Customer Support');
	}

	/**
	 * @test
	 */
	function it_checks_for_repeated_characters()
	{
		$spam = new Spam();
		$this->expectException(\Exception::class);
		$spam->detect("yolooooooooooooo");

	}
}
