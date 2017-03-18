<?php

namespace Siphon\Bus\Container;

use Siphon\Bus\ContainerInterface;

class DefaultContainer implements ContainerInterface
{
    /**
     * {@inheritdoc}
     */
    public function make($class)
    {
        return new $class;
    }
}