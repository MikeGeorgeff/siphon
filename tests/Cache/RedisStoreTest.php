<?php

namespace Siphon\Test\Cache;

use Siphon\Redis\Redis;
use Predis\ClientInterface;
use Siphon\Cache\RedisStore;

class RedisStoreTest extends \Siphon\Test\TestCase
{
    /**
     * @var RedisStore
     */
    protected $store;

    /**
     * @var \Mockery\MockInterface
     */
    protected $redis;

    protected function before()
    {
        $this->redis = $this->mock(Redis::class);
        $this->store = new RedisStore($this->redis);
    }

    public function testGetRedis()
    {
        $this->assertInstanceOf(Redis::class, $this->store->getRedis());
    }

    public function testSetConnection()
    {
        $this->store->setConnection('foo');

        $reflect = new \ReflectionClass($this->store);
        $property = $reflect->getProperty('connection');
        $property->setAccessible(true);

        $this->assertEquals('foo', $property->getValue($this->store));
    }

    public function testConnection()
    {
        $this->redis->shouldReceive('connection')->once()->andReturn($this->mock(ClientInterface::class));

        $this->assertInstanceOf(ClientInterface::class, $this->store->connection());
    }

    public function testExists()
    {
        $client = $this->mock(ClientInterface::class);
        $this->redis->shouldReceive('connection')->once()->andReturn($client);

        $client->shouldReceive('exists')->once()->andReturn(true);

        $this->assertTrue($this->store->exists('foo'));
    }

    public function testGet()
    {
        $client = $this->mock(ClientInterface::class);
        $this->redis->shouldReceive('connection')->once()->andReturn($client);

        $client->shouldReceive('get')->once()->andReturn(serialize('foo'));

        $this->assertEquals('foo', $this->store->get('bar'));
    }

    public function testSave()
    {
        $client = $this->mock(ClientInterface::class);
        $this->redis->shouldReceive('connection')->once()->andReturn($client);

        $client->shouldReceive('setex')->once();

        $this->store->save('foo', 'bar', 1);
    }

    public function testForever()
    {
        $client = $this->mock(ClientInterface::class);
        $this->redis->shouldReceive('connection')->once()->andReturn($client);

        $client->shouldReceive('set')->once();

        $this->store->forever('foo', 'bar');
    }

    public function testRemove()
    {
        $client = $this->mock(ClientInterface::class);
        $this->redis->shouldReceive('connection')->once()->andReturn($client);

        $client->shouldReceive('del')->once()->andReturn(1);

        $this->assertTrue($this->store->remove('foo'));
    }

    public function testFlush()
    {
        $client = $this->mock(ClientInterface::class);
        $this->redis->shouldReceive('connection')->once()->andReturn($client);

        $client->shouldReceive('flushdb')->once();

        $this->store->flush();
    }

    protected function after()
    {
        $this->redis = null;
        $this->store = null;
    }
}