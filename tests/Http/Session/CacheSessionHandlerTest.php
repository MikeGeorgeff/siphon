<?php

namespace Siphon\Test\Http\Session;

use Siphon\Cache\Repository;
use Siphon\Http\Session\CacheSessionHandler;

class CacheSessionHandlerTest extends \Siphon\Test\TestCase
{
    /**
     * @var CacheSessionHandler
     */
    protected $handler;

    /**
     * @var \Mockery\MockInterface
     */
    protected $cache;

    protected function before()
    {
        $this->cache = $this->mock(Repository::class)->makePartial();

        $this->handler = new CacheSessionHandler($this->cache);
    }

    public function testOpen()
    {
        $this->assertTrue($this->handler->open('', ''));
    }

    public function testClose()
    {
        $this->assertTrue($this->handler->close());
    }

    public function testRead()
    {
        $this->cache->shouldReceive('get')->once()->andReturn('foo');

        $this->assertEquals('foo', $this->handler->read('id'));
    }

    public function testWrite()
    {
        $this->cache->shouldReceive('save')->once()->andReturn(true);

        $this->assertTrue($this->handler->write('id', 'data'));
    }

    public function testDestroy()
    {
        $this->cache->shouldReceive('remove')->once()->andReturn(true);

        $this->assertTrue($this->handler->destroy('id'));
    }

    public function testGc()
    {
        $this->assertTrue($this->handler->gc(1));
    }

    protected function after()
    {
        $this->cache   = null;
        $this->handler = null;
    }
}