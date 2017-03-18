<?php

namespace Siphon\Test\Http\Routing;

use Zend\Diactoros\Response;
use Siphon\Http\Routing\Redirector;
use Siphon\Http\Routing\UrlGenerator;

class RedirectorTest extends \Siphon\Test\TestCase
{
    public function testRedirect()
    {
        $redirect = $this->getRedirector();

        $response = $redirect->redirect('http://foo.com');

        $this->assertInstanceOf(Response::class, $response);
        $this->assertEquals(302, $response->getStatusCode());
    }

    public function testTo()
    {
        $redirect = $this->getRedirector();

        $redirect->getUrlGenerator()->shouldReceive('to')->once()->andReturn('http://foo.com');

        $response = $redirect->to('/');

        $this->assertEquals(['http://foo.com'], $response->getHeader('location'));
    }

    public function testRefresh()
    {
        $redirect = $this->getRedirector();

        $redirect->getUrlGenerator()->shouldReceive('current')->once()->andReturn('http://foo.com');

        $response = $redirect->current();

        $this->assertEquals(['http://foo.com'], $response->getHeader('location'));
    }

    public function testBack()
    {
        $redirect = $this->getRedirector();

        $redirect->getUrlGenerator()->shouldReceive('previous')->once()->andReturn('http://foo.com');

        $response = $redirect->previous();

        $this->assertEquals(['http://foo.com'], $response->getHeader('location'));
    }

    public function testRoute()
    {
        $redirect = $this->getRedirector();

        $redirect->getUrlGenerator()->shouldReceive('route')->once()->andReturn('http://foo.com/bar');

        $response = $redirect->route('name', ['bar']);

        $this->assertEquals(['http://foo.com/bar'], $response->getHeader('location'));
    }

    /**
     * Get the redirector instance
     *
     * @return Redirector
     */
    protected function getRedirector()
    {
        return new Redirector($this->mock(UrlGenerator::class));
    }
}