<?php

namespace Siphon\Test\Http\Routing;

use Siphon\Http\Routing\UrlGenerator;
use Siphon\Http\Routing\RouteCollection;

class UrlGeneratorTest extends \Siphon\Test\TestCase
{
    /**
     * @var \Mockery\MockInterface
     */
    protected $routes;

    /**
     * @var \Mockery\MockInterface
     */
    protected $url;

    protected function before()
    {
        $this->routes = $this->mock(RouteCollection::class);

        $this->url    = $this->mock(UrlGenerator::class, [$this->routes])
                             ->shouldAllowMockingProtectedMethods()
                             ->makePartial();
    }

    public function testProtocol()
    {
        $this->assertEquals('https://', $this->url->protocol(true));

        $this->url->shouldReceive('server')->once()->with('HTTPS')->andReturn('string');
        $this->assertEquals('https://', $this->url->protocol());

        $this->url->shouldReceive('server')->once()->with('HTTPS')->andReturn(null);
        $this->assertEquals('http://', $this->url->protocol());
    }

    public function testIsValidUrl()
    {
        $this->assertTrue($this->url->isValidUrl('http://test.app'));
        $this->assertFalse($this->url->isValidUrl('foo'));
    }

    public function testRoot()
    {
        $this->url->shouldReceive('protocol')->once()->andReturn('http://');
        $this->url->shouldReceive('server')->once()->with('SERVER_NAME')->andReturn('test.app');

        $this->assertEquals('http://test.app', $this->url->root());
    }

    public function testTo()
    {
        $this->assertEquals('http://test.app', $this->url->to('http://test.app'));

        $this->url->shouldReceive('root')->once()->andReturn('http://test.app');
        $this->assertEquals('http://test.app/foo/bar', $this->url->to('foo/bar'));
    }

    public function testRoute()
    {
        $this->routes->shouldReceive('getPath')->once()->andReturn('/foo/bar');
        $this->url->shouldReceive('root')->once()->andReturn('http://test.app');

        $this->assertEquals('http://test.app/foo/bar', $this->url->route('name'));
    }

    public function testCurrent()
    {
        $this->url->shouldReceive('server')->once()->with('REQUEST_URI')->andReturn('http://test.app');

        $this->assertEquals('http://test.app', $this->url->current());
    }

    public function testPrevious()
    {
        $this->url->shouldReceive('server')->once()->with('HTTP_REFERER')->andReturn('http://test.app');
        $this->assertEquals('http://test.app', $this->url->previous());

        $this->url->shouldReceive('server')->once()->with('HTTP_REFERER')->andReturn(null);
        $this->url->shouldReceive('root')->once()->andReturn('http://foo.app');
        $this->assertEquals('http://foo.app/bar', $this->url->previous('/bar'));

        $this->url->shouldReceive('server')->once()->with('HTTP_REFERER')->andReturn(null);
        $this->url->shouldReceive('root')->once()->andReturn('http://bar.app');
        $this->assertEquals('http://bar.app', $this->url->previous());
    }

    protected function after()
    {
        $this->routes = null;
        $this->url    = null;
    }
}