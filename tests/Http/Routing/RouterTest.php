<?php

namespace Siphon\Test\Http\Routing;

use Siphon\Http\Routing\Router;
use Siphon\Http\Routing\ActionResolver;
use Siphon\Http\Routing\RouteCollection;

class RouterTest extends \Siphon\Test\TestCase
{
    public function testAddRoute()
    {
        $collection = $this->mock(RouteCollection::class);
        $resolver   = $this->mock(ActionResolver::class);

        $router = new Router($collection, $resolver);

        $resolver->shouldReceive('resolve')->andReturn(new Fixture\TestAction);

        $collection->shouldReceive('addRoute')->once();

        $this->assertInstanceOf(
            Router::class, $router->addRoute('POST', 'test', 'test', Fixture\TestAction::class)
        );
    }
}