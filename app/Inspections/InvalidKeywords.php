<?php

namespace App\Inspections;

class InvalidKeywords implements Inspectable
{
	protected $keywords = [
		'yahoo customer support'
	];

	public function detect($body)
	{
		foreach($this->keywords as $keyword) {
			if(stripos($body, $keyword) !== false)
				throw new \Exception('Reply contains spam.');
		}
	}
}