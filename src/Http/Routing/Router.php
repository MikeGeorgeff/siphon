<?php

namespace Siphon\Http\Routing;

class Router
{
    /**
     * @var \Siphon\Http\Routing\RouteCollection
     */
    protected $collection;

    /**
     * @var \Siphon\Http\Routing\ActionResolver
     */
    protected $resolver;

    /**
     * @param \Siphon\Http\Routing\RouteCollection $collection
     * @param \Siphon\Http\Routing\ActionResolver  $resolver
     */
    public function __construct(RouteCollection $collection, ActionResolver $resolver)
    {
        $this->collection = $collection;
        $this->resolver   = $resolver;
    }

    /**
     * Register a GET route on the collection
     *
     * @param string $name
     * @param string $uri
     * @param string $action
     * @return \Siphon\Http\Routing\Router
     */
    public function get($name, $uri, $action)
    {
        return $this->addRoute('GET', $name, $uri, $action);
    }

    /**
     * Register a POST route on the collection
     *
     * @param string $name
     * @param string $uri
     * @param string $action
     * @return \Siphon\Http\Routing\Router
     */
    public function post($name, $uri, $action)
    {
        return $this->addRoute('POST', $name, $uri, $action);
    }

    /**
     * Register a PUT route on the collection
     *
     * @param string $name
     * @param string $uri
     * @param string $action
     * @return \Siphon\Http\Routing\Router
     */
    public function put($name, $uri, $action)
    {
        return $this->addRoute('PUT', $name, $uri, $action);
    }

    /**
     * Register a PATCH route on the collection
     *
     * @param string $name
     * @param string $uri
     * @param string $action
     * @return \Siphon\Http\Routing\Router
     */
    public function patch($name, $uri, $action)
    {
        return $this->addRoute('PATCH', $name, $uri, $action);
    }

    /**
     * Register a DELETE route on the collection
     *
     * @param string $name
     * @param string $uri
     * @param string $action
     * @return \Siphon\Http\Routing\Router
     */
    public function delete($name, $uri, $action)
    {
        return $this->addRoute('DELETE', $name, $uri, $action);
    }

    /**
     * Register a OPTIONS route on the collection
     *
     * @param string $name
     * @param string $uri
     * @param string $action
     * @return \Siphon\Http\Routing\Router
     */
    public function options($name, $uri, $action)
    {
        return $this->addRoute('OPTIONS', $name, $uri, $action);
    }

    /**
     * Add a route to the route collection
     *
     * @param string $method
     * @param string $name
     * @param string $uri
     * @param string $action
     * @return \Siphon\Http\Routing\Router
     */
    public function addRoute($method, $name, $uri, $action)
    {
        $this->collection->addRoute($method, $name, $uri, $this->resolve($action));

        return $this;
    }

    /**
     * Resolve the action class
     *
     * @param string $action
     * @return \Siphon\Http\Routing\ActionInterface
     */
    protected function resolve($action)
    {
        return $this->resolver->resolve($action);
    }
}