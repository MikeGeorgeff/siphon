<?php

namespace Siphon\Test\Bus;

use Siphon\Bus\CommandBus;
use Siphon\Bus\Dispatcher;

class DispatcherTest extends \Siphon\Test\TestCase
{
    public function testExecute()
    {
        $bus = $this->mock(CommandBus::class);

        $dispatcher = new Dispatcher($bus);

        $bus->shouldReceive('execute')->once()->andReturn('foo');

        $this->assertEquals('foo', $dispatcher->execute($this->mock(Fixture\Test::class)));
    }
}