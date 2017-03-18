<?php

namespace Siphon\Event;

interface GeneratorInterface
{
    /**
     * Raise a new event
     *
     * @param string|object $event
     * @return void
     */
    public function raise($event);

    /**
     * Release all pending events
     *
     * @return array
     */
    public function release();
}