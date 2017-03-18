<?php

namespace Siphon\Test\View;

use League\Plates\Engine;
use Siphon\Http\Session\Session;
use Siphon\View\Extension\Session as Extension;

class SessionExtensionTest extends \Siphon\Test\TestCase
{
    /**
     * @var \Mockery\MockInterface
     */
    protected $session;

    public function testRegister()
    {
        $ext    = $this->extension();
        $engine = $this->mock(Engine::class);

        $engine->shouldReceive('registerFunction')->once();

        $ext->register($engine);
    }

    public function testGetObject()
    {
        $ext = $this->extension();

        $this->assertInstanceOf(Extension::class, $ext);
    }

    public function testGet()
    {
        $ext = $this->extension();

        $this->session->shouldReceive('get')->once()->andReturn('bar');

        $this->assertEquals('bar', $ext->get('foo'));
    }

    public function testHasFlash()
    {
        $ext = $this->extension();

        $this->session->shouldReceive('hasFlash')->once()->andReturn(true);

        $this->assertTrue($ext->hasFlash('foo'));
    }

    public function testGetFlash()
    {
        $ext = $this->extension();

        $this->session->shouldReceive('getFlash')->once()->andReturn(['bar']);

        $this->assertEquals(['bar'], $ext->getFlash('foo'));
    }

    public function testInput()
    {
        $ext = $this->extension();

        $this->session->shouldReceive('input')->once()->andReturn(['foo' => 'bar']);

        $this->assertEquals(['foo' => 'bar'], $ext->input());
    }

    public function testHasErrors()
    {
        $ext = $this->extension();

        $this->session->shouldReceive('errors')->once()->andReturn([]);
        $this->assertFalse($ext->hasErrors());

        $this->session->shouldReceive('errors')->once()->andReturn(['foo' => 'bar']);
        $this->assertTrue($ext->hasErrors());
    }

    public function testErrors()
    {
        $ext = $this->extension();

        $this->session->shouldReceive('errors')->once()->andReturn(['foo' => 'bar']);

        $this->assertEquals(['foo' => 'bar'], $ext->errors());
    }

    public function testCsrfToken()
    {
        $ext = $this->extension();

        $this->session->shouldReceive('csrfToken')->once()->andReturn('1111');

        $this->assertEquals('1111', $ext->csrfToken());
    }

    protected function extension()
    {
        $this->session = $this->mock(Session::class);

        return new Extension($this->session);
    }

    protected function after()
    {
        $this->session = null;
    }
}