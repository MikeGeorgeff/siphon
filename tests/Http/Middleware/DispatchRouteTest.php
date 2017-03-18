<?php

namespace Siphon\Test\Http\Middleware;

use Siphon\Http\Exception;
use Zend\Diactoros\Response;
use Zend\Diactoros\ServerRequest;
use Siphon\Http\Routing\RouteCollection;
use Siphon\Http\Middleware\DispatchRoute;
use Siphon\Test\Http\Routing\Fixture\TestAction;

class DispatchRouteTest extends \Siphon\Test\TestCase
{
    public function testHandleFoundRoute()
    {
        $request = new ServerRequest([], [], 'http://localhost/hello/mike', 'GET');

        $routes = new RouteCollection;

        $routes->addRoute('GET', 'hello', 'hello/{name}', new TestAction);

        $mw = new DispatchRoute($routes);

        $response = $mw($request, new Response, function () {});

        $this->assertEquals('Hello mike', $response->getBody()->getContents());
    }

    public function testRouteNotFound()
    {
        $this->expectException(Exception\RouteNotFoundException::class);

        $request = new ServerRequest([], [], 'http://localhost/hello/mike', 'GET');

        $routes = new RouteCollection;

        $mw = new DispatchRoute($routes);

        $mw($request, new Response, function () {});
    }

    public function testMethodNotAllowed()
    {
        $this->expectException(Exception\MethodNotAllowedException::class);

        $request = new ServerRequest([], [], 'http://localhost/hello/mike', 'GET');

        $routes = new RouteCollection;

        $routes->addRoute('POST', 'hello', 'hello/{name}', new TestAction);

        $mw = new DispatchRoute($routes);

        $mw($request, new Response, function () {});
    }
}