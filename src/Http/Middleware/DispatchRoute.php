<?php

namespace Siphon\Http\Middleware;

use FastRoute\Dispatcher;
use Siphon\Http\Exception;
use Siphon\Http\Routing\RouteCollection;
use Zend\Stratigility\MiddlewareInterface;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

class DispatchRoute implements MiddlewareInterface
{
    /**
     * @var \Siphon\Http\Routing\RouteCollection
     */
    protected $routes;

    /**
     * @var \FastRoute\Dispatcher
     */
    protected $dispatcher;

    /**
     * @param \Siphon\Http\Routing\RouteCollection $routes
     */
    public function __construct(RouteCollection $routes)
    {
        $this->routes = $routes;
    }

    /**
     * Process an incoming request and/or response
     *
     * @param \Psr\Http\Message\ServerRequestInterface $request
     * @param \Psr\Http\Message\ResponseInterface      $response
     * @param callable                                 $next
     * @return \Psr\Http\Message\ResponseInterface
     *
     * @throws \Siphon\Http\Exception\MethodNotAllowedException
     * @throws \Siphon\Http\Exception\RouteNotFoundException
     */
    public function __invoke(Request $request, Response $response, callable $next)
    {
        $method = $request->getMethod();
        $uri    = $request->getUri()->getPath();

        $routeInfo = $this->getDispatcher()->dispatch($method, $uri);

        switch ($routeInfo[0]) {
            case Dispatcher::NOT_FOUND:
                throw new Exception\RouteNotFoundException;
            case Dispatcher::METHOD_NOT_ALLOWED:
                throw new Exception\MethodNotAllowedException;
            case Dispatcher::FOUND:
                $action = $routeInfo[1];
                $params = $routeInfo[2];

                $request = $request->withQueryParams(
                    array_merge($request->getQueryParams(), $params)
                );

                return $action($request);
        }
    }

    /**
     * Get the dispatcher instance
     *
     * @return \FastRoute\Dispatcher
     */
    protected function getDispatcher()
    {
        if (! isset($this->dispatcher)) {
            $this->dispatcher = \FastRoute\simpleDispatcher(function ($r) {
                foreach ($this->routes->routes() as $route) {
                    $r->addRoute($route->method, $route->uri, $route->action);
                }
            });
        }

        return $this->dispatcher;
    }
}