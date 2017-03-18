<?php

namespace Siphon\Test\Cache;

use Siphon\Cache\ArrayStore;
use Siphon\Cache\Repository;

class RepositoryTest extends \Siphon\Test\TestCase
{
    /**
     * @var Repository
     */
    protected $repo;

    protected function before()
    {
        $store = new ArrayStore;

        $store->forever('foo', 'bar');

        $this->repo = new Repository($store);
    }

    public function testExists()
    {
        $this->assertTrue($this->repo->exists('foo'));
    }

    public function testGet()
    {
        $this->assertEquals('bar', $this->repo->get('foo'));
    }

    public function testGetReturnsDefaultValue()
    {
        $this->assertEquals('default', $this->repo->get('baz', 'default'));
    }

    public function testPull()
    {
        $this->assertEquals('bar', $this->repo->pull('foo'));
        $this->assertFalse($this->repo->exists('foo'));
    }

    public function testSave()
    {
        $this->assertTrue($this->repo->save('baz', 'boom', 60));
        $this->assertEquals('boom', $this->repo->get('baz'));
    }

    public function testPreventOverwriteExistingValue()
    {
        $this->assertFalse($this->repo->save('foo', 'baz', 60, false));
        $this->assertEquals('bar', $this->repo->get('foo'));
    }

    public function testForever()
    {
        $this->repo->forever('baz', 'bam');

        $this->assertEquals('bam', $this->repo->get('baz'));
    }

    public function testRemember()
    {
        $this->assertEquals('bam', $this->repo->remember('baz', 1, function () { return 'bam'; }));
        $this->assertEquals('bam', $this->repo->get('baz'));
    }

    public function testRememberReturnsExistingValue()
    {
        $this->assertEquals('bar', $this->repo->remember('foo', 1, function () { return 'baz'; }));
    }

    public function testRememberForever()
    {
        $this->assertEquals('bam', $this->repo->rememberForever('baz', function () { return 'bam'; }));
        $this->assertEquals('bam', $this->repo->get('baz'));
    }

    public function testRememberForeverReturnsExistingValue()
    {
        $this->assertEquals('bar', $this->repo->rememberForever('foo', function () { return 'baz'; }));
    }

    public function testRemove()
    {
        $this->repo->remove('foo');

        $this->assertFalse($this->repo->exists('foo'));
    }

    public function testFlush()
    {
        $this->repo->flush();

        $this->assertFalse($this->repo->exists('foo'));
    }

    public function testSetPrefix()
    {
        $this->repo->setPrefix('test');

        $this->assertEquals('test:', $this->repo->getPrefix());
    }

    protected function after()
    {
        $this->repo = null;
    }
}