<?php

namespace Siphon\Test\Bus;

use Illuminate\Container\Container;
use Siphon\Bus\Container\IlluminateContainer;

class IlluminateContainerTest extends \Siphon\Test\TestCase
{
    public function testMake()
    {
        $container = new IlluminateContainer(new Container);

        $instance = $container->make(\stdClass::class);

        $this->assertInstanceOf(\stdClass::class, $instance);
    }
}