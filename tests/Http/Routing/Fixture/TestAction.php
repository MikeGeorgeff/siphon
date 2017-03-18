<?php

namespace Siphon\Test\Http\Routing\Fixture;

use Siphon\Http\Routing\ActionInterface;
use Psr\Http\Message\ServerRequestInterface;
use Zend\Diactoros\Response\TextResponse as Response;

class TestAction implements ActionInterface
{
    public function __invoke(ServerRequestInterface $request)
    {
        $name = $request->getQueryParams()['name'];

        return new Response('Hello '.$name);
    }
}