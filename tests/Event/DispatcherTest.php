<?php

namespace Siphon\Test\Event;

use Siphon\Event\Dispatcher;
use Siphon\Event\GeneratorInterface;
use Illuminate\Contracts\Events\Dispatcher as BaseDispatcher;

class DispatcherTest extends \Siphon\Test\TestCase
{
    public function testDispatch()
    {
        $event = $this->dispatcher();

        $event->getBaseDispatcher()->shouldReceive('dispatch')->once()->andReturn(null);

        $this->assertNull($event->dispatch('foo'));
    }

    public function testDispatchFor()
    {
        $event  = $this->dispatcher();
        $entity = $this->mock(GeneratorInterface::class);

        $entity->shouldReceive('release')->once()->andReturn(['foo', 'bar']);

        $event->getBaseDispatcher()->shouldReceive('dispatch')->twice();

        $event->dispatchFor($entity);
    }

    public function testListen()
    {
        $event = $this->dispatcher();

        $event->getBaseDispatcher()->shouldReceive('listen')->once();

        $event->listen('foo', function () {});
    }

    protected function dispatcher()
    {
        $base = $this->mock(BaseDispatcher::class);

        return new Dispatcher($base);
    }
}