<?php

namespace Siphon\Bus\Inner;

use Siphon\Bus\CommandBus;
use Siphon\Bus\HandlerResolver;
use Siphon\Bus\MethodNameInflector;
use Siphon\Bus\Resolver\DefaultResolver;
use Siphon\Bus\Inflector\HandleInflector;

class Bus implements CommandBus
{
    /**
     * @var \Siphon\Bus\HandlerResolver
     */
    protected $resolver;

    /**
     * @var \Siphon\Bus\MethodNameInflector
     */
    protected $inflector;

    /**
     * @param \Siphon\Bus\HandlerResolver|null     $resolver
     * @param \Siphon\Bus\MethodNameInflector|null $inflector
     */
    public function __construct(HandlerResolver $resolver = null, MethodNameInflector $inflector = null)
    {
        $this->resolver  = $resolver ?: new DefaultResolver;
        $this->inflector = $inflector ?: new HandleInflector;
    }

    /**
     * {@inheritdoc}
     */
    public function execute($command)
    {
        $handler = $this->resolver->resolve($command);

        return call_user_func([$handler, $this->handleMethod()], $command);
    }

    /**
     * Get the handle method
     *
     * @return string
     */
    protected function handleMethod()
    {
        return $this->inflector->getHandleMethod();
    }
}