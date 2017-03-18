<?php

namespace Siphon\Http\Routing;

use Illuminate\Contracts\Container\Container;

class ActionResolver
{
    /**
     * @var \Illuminate\Contracts\Container\Container
     */
    protected $container;

    /**
     * @param \Illuminate\Contracts\Container\Container $container
     */
    public function __construct(Container $container)
    {
        $this->container = $container;
    }

    /**
     * Resolve the route action
     *
     * @param string $action
     * @return \Siphon\Http\Routing\ActionInterface
     */
    public function resolve($action)
    {
        $instance = $this->container->make($action);

        if ($instance instanceof ActionInterface) {
            return $instance;
        }

        throw new \InvalidArgumentException(
            'Action must be an instance of Siphon\Http\Action\ActionInterface'
        );
    }
}