<?php

namespace Siphon\Test\Http\Session;

use Siphon\Http\Session\Bag\AttributeBag;

class AttributeBagTest extends \Siphon\Test\TestCase
{
    /**
     * @var AttributeBag
     */
    protected $bag;

    protected function before()
    {
        $this->bag = new AttributeBag;

        $this->bag->initialize(['foo' => 'bar']);
    }

    public function testHas()
    {
        $this->assertTrue($this->bag->has('foo'));
    }

    public function testGet()
    {
        $this->assertEquals('bar', $this->bag->get('foo'));
        $this->assertEquals('default', $this->bag->get('key', 'default'));
    }

    public function testPull()
    {
        $this->assertEquals('bar', $this->bag->pull('foo'));
        $this->assertFalse($this->bag->has('foo'));
        $this->assertEquals('default', $this->bag->pull('key', 'default'));
    }

    public function testAll()
    {
        $this->assertEquals(['foo' => 'bar'], $this->bag->all());
    }

    public function testSet()
    {
        $this->bag->set('key', 'value');

        $this->assertEquals('value', $this->bag->get('key'));
    }

    public function testRemove()
    {
        $this->assertTrue($this->bag->remove('foo'));
        $this->assertFalse($this->bag->has('foo'));
        $this->assertFalse($this->bag->remove('key'));
    }

    public function testClear()
    {
        $this->assertEquals(['foo' => 'bar'], $this->bag->clear());
        $this->assertEmpty($this->bag->all());
    }

    public function testGetName()
    {
        $this->assertEquals('attributes', $this->bag->getName());
    }

    protected function after()
    {
        $this->bag = null;
    }
}