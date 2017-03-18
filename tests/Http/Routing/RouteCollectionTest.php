<?php

namespace Siphon\Test\Http\Routing;

use Siphon\Http\Routing\Route;
use Siphon\Http\Routing\RouteCollection;

class RouteCollectionTest extends \Siphon\Test\TestCase
{
    public function testAddRoute()
    {
        $collection = new RouteCollection;

        $collection->addRoute('GET', 'test', 'test/{param}', new Fixture\TestAction);

        $this->assertArrayHasKey('test', $collection->routes());
        $this->assertInstanceOf(Route::class, $collection->routes()['test']);
    }

    public function testGetPath()
    {
        $collection = new RouteCollection;

        $collection->addRoute('GET', 'test', 'test/{param}', new Fixture\TestAction);

        $this->assertEquals('/test/foo', $collection->getPath('test', ['foo']));
    }

    public function testGetPathNotEnoughParams()
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('Not enough parameters given.');

        $collection = new RouteCollection;

        $collection->addRoute('GET', 'test', 'test/{param}', new Fixture\TestAction);

        $collection->getPath('test');
    }

    public function testGetPathTooManyParams()
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('Too many parameters given.');

        $collection = new RouteCollection;

        $collection->addRoute('GET', 'test', 'test/{param}', new Fixture\TestAction);

        $collection->getPath('test', ['foo', 'bar']);
    }

    public function testGetPathThrowExceptionIfRouteNameIsNotRegistered()
    {
        $this->expectException(\RuntimeException::class);
        $this->expectExceptionMessage('The route name [test] is not registered.');

        (new RouteCollection)->getPath('test');
    }
}
