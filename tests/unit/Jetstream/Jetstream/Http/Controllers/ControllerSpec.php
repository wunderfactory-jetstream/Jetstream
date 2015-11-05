<?php

namespace unit\Jetstream\Jetstream\Http\Controllers;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class ControllerSpec extends ObjectBehavior
{
    function it_is_initializable()
    {
        $this->shouldHaveType('Jetstream\Jetstream\Http\Controllers\Controller');
    }
}
