<?php

namespace Siphon\Bus;

interface MethodNameInflector
{
    /**
     * The method to be called in the handler
     * 
     * @return string
     */
    public function getHandleMethod();
}