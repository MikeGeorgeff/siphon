<?php

namespace Siphon\Http\Routing;

use FastRoute\RouteParser\Std;

class RouteCollection
{
    /**
     * @var \Siphon\Http\Routing\Route[]
     */
    protected $routes = [];

    /**
     * Add a route to the collection
     *
     * @param string                                $method
     * @param string                                $name
     * @param string                                $uri
     * @param \Siphon\Http\Routing\ActionInterface  $action
     * @return \Siphon\Http\Routing\RouteCollection
     */
    public function addRoute($method, $name, $uri, ActionInterface $action)
    {
        $uri = '/'.trim($uri, '/');

        $this->routes[$name] = new Route($method, $uri, $action);

        return $this;
    }

    /**
     * Get the uri path for a route
     *
     * @param string $name
     * @param array  $parameters
     * @return string
     *
     * @throws \RuntimeException
     * @throws \InvalidArgumentException
     */
    public function getPath($name, array $parameters = [])
    {
        if (isset($this->routes[$name])) {
            $parser = new Std;

            $routes = $parser->parse($this->routes[$name]->uri);

            foreach ($routes as $route) {
                $url   = '';
                $index = 0;

                foreach ($route as $part) {
                    if (is_string($part)) {
                        $url .= $part;
                        continue;
                    }

                    if ($index === count($parameters)) {
                        throw new \InvalidArgumentException('Not enough parameters given.');
                    }

                    $url .= $parameters[$index++];
                }

                if ($index === count($parameters)) {
                    return $url;
                }
            }

            throw new \InvalidArgumentException('Too many parameters given.');
        }

        throw new \RuntimeException(sprintf('The route name [%s] is not registered.', $name));
    }

    /**
     * Get all routes registered on the collection
     *
     * @return \Siphon\Http\Routing\Route[]
     */
    public function routes()
    {
        return $this->routes;
    }
}