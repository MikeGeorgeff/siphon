<?php

namespace Siphon\Test\Http\Session;

use Siphon\Http\Session\Session;
use Siphon\Http\Session\Bag\FlashBag;
use Siphon\Http\Session\Bag\AttributeBag;
use Siphon\Http\Session\Bag\SessionBagInterface;

class SessionTest extends \Siphon\Test\TestCase
{
    public function testStart()
    {
        $session = $this->getSession();

        $session->getHandler()->shouldReceive('read')->andReturn(serialize([
            '_session.attributes' => ['foo' => 'bar'],
            '_session.flashes'    => ['current' => [], 'new' => ['warning' => ['string']]]
        ]));

        $session->start();

        $this->assertTrue($session->isStarted());
        $this->assertTrue($session->has('_token'));
        $this->assertTrue($session->has('foo'));
        $this->assertTrue($session->hasFlash('warning'));
        $this->assertInstanceOf(FlashBag::class, $session->getBag('flashes'));
        $this->assertInstanceOf(FlashBag::class, $session->getFlashBag());
        $this->assertInstanceOf(AttributeBag::class, $session->getAttributesBag());
    }

    public function testSave()
    {
        $session = $this->getSession();

        $session->getHandler()->shouldReceive('write')->once()->andReturn(true);

        $session->save();

        $this->assertFalse($session->isStarted());
    }

    public function testSet()
    {
        $session = $this->getSession();

        $session->set('foo', 'bar');

        $this->assertTrue($session->has('foo'));
    }

    public function testAll()
    {
        $session = $this->getSession();

        $session->set('foo', 'bar');

        $this->assertEquals(['foo' => 'bar'], $session->all());
    }

    public function testGet()
    {
        $session = $this->getSession();

        $session->set('foo', 'bar');

        $this->assertEquals('bar', $session->get('foo'));
        $this->assertEquals('default', $session->get('key', 'default'));
    }

    public function testPull()
    {
        $session = $this->getSession();

        $session->set('foo', 'bar');

        $this->assertEquals('bar', $session->pull('foo'));
        $this->assertFalse($session->has('foo'));
        $this->assertEquals('default', $session->pull('key', 'default'));
    }

    public function testRemove()
    {
        $session = $this->getSession();

        $session->set('foo', 'bar');
        $session->remove('foo');

        $this->assertFalse($session->has('foo'));
    }

    public function testFlash()
    {
        $session = $this->getSession();

        $session->flash('foo', 'bar');

        $this->assertEquals(['foo' => ['bar']], $session->getFlashBag()->getAttributes()['new']);
    }

    public function testHasFlash()
    {
        $session = $this->getSession(true);

        $this->assertTrue($session->hasFlash('warning'));
    }

    public function testAllFlash()
    {
        $session = $this->getSession(true);

        $this->assertEquals([
            'warning' => ['string'],
            '_input'  => ['key' => 'value'],
            '_errors' => ['key' => 'value']
        ], $session->allFlash());
    }

    public function testGetFlash()
    {
        $session = $this->getSession(true);

        $this->assertEquals(['string'], $session->getFlash('warning'));
    }

    public function testFlashInput()
    {
        $session = $this->getSession();

        $session->flashInput(['key' => 'value']);

        $this->assertEquals(['key' => 'value'], $session->getFlashBag()->getAttributes()['new']['_input']);
    }

    public function testGetFlashInput()
    {
        $session = $this->getSession(true);

        $this->assertEquals(['key' => 'value'], $session->input());
    }

    public function testGetFlashInputByKey()
    {
        $session = $this->getSession(true);

        $this->assertEquals('value', $session->input('key'));
    }

    public function testFlashErrors()
    {
        $session = $this->getSession();

        $session->flashErrors(['foo' => 'bar']);

        $this->assertEquals(['foo' => 'bar'], $session->getFlashBag()->getAttributes()['new']['_errors']);
    }

    public function testGetFlashErrors()
    {
        $session = $this->getSession(true);

        $this->assertEquals(['key' => 'value'], $session->errors());
    }

    public function testGetFlashErrorByKey()
    {
        $session = $this->getSession(true);

        $this->assertEquals('value', $session->errors('key'));
    }

    public function testFlush()
    {
        $session = $this->getSession();

        $session->set('foo', 'bar');
        $session->flash('baz', 'bam');
        $session->flush();

        $this->assertEmpty($session->all());
        $this->assertEmpty($session->getFlashBag()->all());
    }

    public function testMigrate()
    {
        $session = $this->getSession();

        $session->migrate();
        $this->assertNotEquals($this->getSessionId(), $session->getId());
    }

    public function testMigrateAndDestroy()
    {
        $session = $this->getSession();

        $session->getHandler()->shouldReceive('destroy')->once();

        $session->migrate(true);
    }

    public function testInvalidate()
    {
        $session = $this->getSession();

        $session->set('foo', 'bar');
        $session->flash('baz', 'bam');

        $session->getHandler()->shouldReceive('destroy')->once();

        $session->invalidate();

        $this->assertNotEquals($this->getSessionId(), $session->getId());
        $this->assertEmpty($session->all());
        $this->assertEmpty($session->getFlashBag()->all());
    }

    public function testCannotRegisterBagWhenSessionIsStarted()
    {
        $session = $this->getSession();

        $session->getHandler()->shouldReceive('read')->andReturn(serialize([]));

        $session->start();

        $this->expectException(\RuntimeException::class);

        $session->registerBag($this->mock(SessionBagInterface::class));
    }

    public function testCannotSetNameWhenSessionIsStarted()
    {
        $session = $this->getSession();

        $session->getHandler()->shouldReceive('read')->andReturn(serialize([]));

        $session->start();

        $this->expectException(\RuntimeException::class);

        $session->setName('foo');
    }

    /**
     * @param  bool $initialize
     * @return Session
     */
    protected function getSession($initialize = false)
    {
        $session = new Session($this->mock(\SessionHandlerInterface::class));

        $session->setId($this->getSessionId());
        $session->setName($this->getSessionName());

        if ($initialize) {
            $new = [
                'warning' => ['string'],
                '_input'  => ['key' => 'value'],
                '_errors' => ['key' => 'value']
            ];
            $session->getFlashBag()->initialize(['current' => [], 'new' => $new]);
        }

        return $session;
    }

    protected function getSessionId()
    {
        return '1111111111111111111111111111111111111111';
    }

    protected function getSessionName()
    {
        return 'name';
    }
}