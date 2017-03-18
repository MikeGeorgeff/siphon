<?php

namespace Siphon\Test\Event;

use Siphon\Event\EventGenerator;

class EventGeneratorTest extends \Siphon\Test\TestCase
{
    use EventGenerator;

    public function testRaiseRelease()
    {
        $this->raise('foo');
        $this->raise('bar');

        $this->assertEquals(['foo', 'bar'], $this->release());
    }
}