<?php

namespace Siphon\Bus\Container;

use Siphon\Bus\ContainerInterface;
use Illuminate\Contracts\Container\Container;

class IlluminateContainer implements ContainerInterface
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
     * {@inheritdoc}
     */
    public function make($class)
    {
        return $this->container->make($class);
    }
}