<?php

namespace Siphon\Test\Bus;

use Siphon\Bus\Inner\Bus;

class BusTest extends \Siphon\Test\TestCase
{
    public function testExecute()
    {
        $bus = new Bus;

        $this->assertEquals('Hi Mike', $bus->execute(new Fixture\Test('Mike')));
    }
}