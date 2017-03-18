<?php

namespace Siphon\Http\Routing;

use Siphon\Http\Request\RequestTrait;

abstract class Action implements ActionInterface
{
    use RequestTrait;
}