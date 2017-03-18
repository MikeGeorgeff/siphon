<?php

namespace Siphon\Test\Http\Session;

use Siphon\Http\Session\Bag\FlashBag;

class FlashBagTest extends \Siphon\Test\TestCase
{
    /**
     * @var FlashBag
     */
    protected $bag;

    protected function before()
    {
        $this->bag = new FlashBag;

        $this->bag->initialize([
            'current' => [],
            'new'     => [
                'message' => ['Sample flash message'],
                'array'   => ['foo' => 'bar', 'baz' => 'bam']
            ]
        ]);
    }

    public function testHas()
    {
        $this->assertTrue($this->bag->has('message'));
    }

    public function testGet()
    {
        $this->assertEquals(['Sample flash message'], $this->bag->get('message'));
        $this->assertEquals(['default'], $this->bag->get('foo', 'default'));
    }

    public function testAll()
    {
        $expected = [
            'message' => ['Sample flash message'],
            'array'   => ['foo' => 'bar', 'baz' => 'bam']
        ];

        $this->assertEquals($expected, $this->bag->all());
    }

    public function testSet()
    {
        $this->bag->set('success', 'message');

        $expected = [
            'current' => [
                'message' => ['Sample flash message'],
                'array'   => ['foo' => 'bar', 'baz' => 'bam']
            ],
            'new'     => [
                'success' => ['message']
            ]
        ];

        $this->assertEquals($expected, $this->bag->getAttributes());
    }

    public function testGetName()
    {
        $this->assertEquals('flashes', $this->bag->getName());
    }

    protected function after()
    {
        $this->bag = null;
    }
}