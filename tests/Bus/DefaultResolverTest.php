<?php

namespace Siphon\Test\Bus;

use Siphon\Bus\Resolver\DefaultResolver;
use Siphon\Bus\Container\DefaultContainer;
use Siphon\Bus\Exception\MissingHandlerException;

class DefaultResolverTest extends \Siphon\Test\TestCase
{
    public function testResolve()
    {
        $resolver = $this->resolver();

        $instance = $resolver->resolve(new Fixture\Test('foo'));

        $this->assertInstanceOf(Fixture\TestHandler::class, $instance);
    }

    public function testMissingHandler()
    {
        $resolver = $this->resolver();

        $this->expectException(MissingHandlerException::class);

        $resolver->resolve(new Fixture\Missing);
    }

    protected function resolver()
    {
        return new DefaultResolver(new DefaultContainer);
    }
}