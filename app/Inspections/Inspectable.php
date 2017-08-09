<?php

namespace App\Inspections;

interface Inspectable
{
	public function detect($body);
}