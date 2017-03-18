<?php

namespace Siphon\Test\Redis;

use Siphon\Redis\Redis;

class RedisTest extends \Siphon\Test\TestCase
{
    protected $servers = [
        'default' => [
            'host'     => '127.0.0.1',
            'password' => null,
            'port'     => 6379,
            'database' => 0,
        ],
        'test' => [
            'host'     => '127.0.0.1',
            'password' => null,
            'port'     => 6379,
            'database' => 1,
        ]
    ];

    public function testConnection()
    {
        $redis = new Redis($this->servers);

        $this->assertInstanceOf(\Predis\Client::class, $redis->connection('default'));
        $this->assertInstanceOf(\Predis\Client::class, $redis->connection('test'));
    }
}