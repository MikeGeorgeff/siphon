<?php

namespace Siphon\Bus;

interface ContainerInterface
{
    /**
     * Instantiate the handler instance
     * 
     * @param string $class
     * @return object
     */
    public function make($class);
}