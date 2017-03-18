<?php

namespace Siphon\Event;

trait EventGenerator
{
    /**
     * @var array
     */
    protected $pendingEvents = [];

    /**
     * {@inheritdoc}
     */
    public function raise($event)
    {
        $this->pendingEvents[] = $event;
    }

    /**
     * {@inheritdoc}
     */
    public function release()
    {
        $events = $this->pendingEvents;

        $this->pendingEvents = [];

        return $events;
    }
}