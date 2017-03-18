<?php

namespace Siphon\Test\Http\Routing;

use Illuminate\Container\Container;
use Siphon\Http\Routing\ActionResolver;

class ActionResolverTest extends \Siphon\Test\TestCase
{
    public function testResolve()
    {
        $resolver = new ActionResolver(new Container);

        $class = $resolver->resolve(Fixture\TestAction::class);

        $this->assertInstanceOf(Fixture\TestAction::class, $class);
    }

    public function testClassMustImplementActionInterface()
    {
        $this->expectExceptionMessage(\InvalidArgumentException::class);
        $this->expectExceptionMessage('Action must be an instance of Siphon\Http\Action\ActionInterface');

        $resolver = new ActionResolver(new Container);
        $resolver->resolve(Fixture\InvalidAction::class);
    }
}