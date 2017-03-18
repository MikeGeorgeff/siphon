<?php

namespace Siphon\Http\Routing;

class Route
{
    /**
     * The http method
     *
     * @var string
     */
    public $method;

    /**
     * The route uri string
     *
     * @var string
     */
    public $uri;

    /**
     * The route's action class
     *
     * @var \Siphon\Http\Routing\ActionInterface
     */
    public $action;

    /**
     * @param string                               $method
     * @param string                               $uri
     * @param \Siphon\Http\Routing\ActionInterface $action
     */
    public function __construct($method, $uri, ActionInterface $action)
    {
        $this->method = $method;
        $this->uri    = $uri;
        $this->action = $action;
    }
}