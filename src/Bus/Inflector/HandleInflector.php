<?php

namespace Siphon\Bus\Inflector;

use Siphon\Bus\MethodNameInflector;

class HandleInflector implements MethodNameInflector
{
    /**
     * {@inheritdoc}
     */
    public function getHandleMethod()
    {
        return 'handle';
    }
}