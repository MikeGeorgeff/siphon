<?php

namespace Siphon\Bus\Resolver;

use Siphon\Bus\HandlerResolver;
use Siphon\Bus\ContainerInterface;
use Siphon\Bus\Container\DefaultContainer;
use Siphon\Bus\Exception\MissingHandlerException;

class DefaultResolver implements HandlerResolver
{
    /**
     * @var \Siphon\Bus\ContainerInterface
     */
    protected $container;

    /**
     * @param \Siphon\Bus\ContainerInterface|null $container
     */
    public function __construct(ContainerInterface $container = null)
    {
        $this->container = $container ?: new DefaultContainer;
    }

    /**
     * {@inheritdoc}
     */
    public function resolve($command)
    {
        $class = get_class($command);

        $handler = $class . 'Handler';

        if (class_exists($handler)) {
            return $this->container->make($handler);
        }

        $parts       = explode('\\', $class);
        $commandName = array_pop($parts);

        $handler = implode('\\', $parts) . '\\Handler\\' . $commandName;

        if (class_exists($handler)) {
            return $this->container->make($handler);
        }

        $handler = implode('\\', $parts) . '\\Handlers\\' . $commandName;

        if (class_exists($handler)) {
            return $this->container->make($handler);
        }
        
        throw new MissingHandlerException('Missing handler for class ['. $class . ']');
    }
}