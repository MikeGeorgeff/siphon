<?php

namespace Siphon\Bus;

use Siphon\Bus\Inner\Bus;

class Dispatcher implements CommandBus
{
    /**
     * @var \Siphon\Bus\CommandBus
     */
    protected $bus;

    /**
     * @param \Siphon\Bus\CommandBus|null $bus
     */
    public function __construct(CommandBus $bus = null)
    {
        $this->bus = $bus ?: new Bus;
    }

    /**
     * {@inheritdoc}
     */
    public function execute($command)
    {
        return $this->bus->execute($command);
    }
}