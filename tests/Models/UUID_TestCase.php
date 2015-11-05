<?php

namespace Jetstream\Jetstream\Tests\Models;

use Jetstream\Jetstream\Models\UUID;
use Orchestra\Testbench\TestCase;

class UUID_TestCase extends TestCase {

	function test_it_generates_uuids_of_given_length()
	{
		for($i = 1; $i <= 50; $i++)
		{
			$this->assertRegExp("/[0-9]{".$i."}/", UUID::randomUUID($i), "UUID was not able to generate a matching uuid");
		}
	}

	function test_it_generates_number_only_uuids()
	{
		$this->assertRegExp("/[0-9]/", UUID::randomUUID(10), "UUID was not able to generate a matching uuid");
	}
}