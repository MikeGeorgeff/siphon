<?php

namespace Siphon\Test\Http\Cookie;

use Carbon\Carbon;
use Siphon\Http\Cookie\Factory;
use Dflydev\FigCookies\SetCookie;

class FactoryTest extends \Siphon\Test\TestCase
{
    public function testMake()
    {
        $factory = new Factory('domain.app');

        $cookie = $factory->make('foo', 'bar', 10);

        $this->assertInstanceOf(SetCookie::class, $cookie);
        $this->assertEquals('foo', $cookie->getName());
        $this->assertEquals('bar', $cookie->getValue());
        $this->assertEquals('domain.app', $cookie->getDomain());
        $this->assertEquals('/', $cookie->getPath());
        $this->assertEquals($this->getTimestamp(10), $cookie->getExpires());
        $this->assertFalse($cookie->getSecure());
        $this->assertTrue($cookie->getHttpOnly());
    }

    public function testForever()
    {
        $factory = new Factory('domain.app');

        $cookie = $factory->forever('foo', 'bar');

        $this->assertEquals($this->getTimestamp(2628000), $cookie->getExpires());
    }

    public function testExpire()
    {
        $factory = new Factory('domain.app');

        $cookie = $factory->expire('foo');

        $this->assertNull($cookie->getValue());
        $this->assertEquals($this->getTimestamp(-2628000), $cookie->getExpires());
    }

    public function testQueue()
    {
        $factory = new Factory('domain.app');

        $cookie = $factory->make('foo', 'bar', 5);

        $factory->queue($cookie);

        $this->assertTrue($factory->isQueued('foo'));
    }

    public function testGetQueuedCookies()
    {
        $factory = new Factory('domain.app');

        $cookie = $factory->make('foo', 'bar', 5);

        $factory->queue($cookie);

        $queued = $factory->getQueuedCookies();

        $this->assertCount(1, $queued);
        $this->assertInstanceOf(SetCookie::class, $queued['foo']);
    }

    /**
     * @param int $minutes
     * @return int
     */
    protected function getTimestamp($minutes)
    {
        return Carbon::now()->getTimestamp() + ($minutes * 60);
    }
}