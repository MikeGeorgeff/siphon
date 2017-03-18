<?php

namespace Siphon\Test\Cache;

use Siphon\Cache\ArrayStore;

class ArrayStoreTest extends \Siphon\Test\TestCase
{
    public function testSave()
    {
        $store = new ArrayStore;

        $store->save('foo', 'bar', 1);

        $this->assertTrue($store->exists('foo'));
    }

    public function testGet()
    {
        $store = new ArrayStore;

        $store->save('foo', 'bar', 0);

        $this->assertEquals('bar', $store->get('foo'));
    }

    public function testForever()
    {
        $store = new ArrayStore;

        $store->forever('foo', 'bar');

        $this->assertTrue($store->exists('foo'));
    }

    public function testRemove()
    {
        $store = new ArrayStore;

        $store->forever('foo', 'bar');
        $store->remove('foo');

        $this->assertFalse($store->exists('foo'));
    }

    public function testFlush()
    {
        $store = new ArrayStore;

        $store->forever('foo', 'bar');
        $store->flush();

        $this->assertFalse($store->exists('foo'));
    }
}