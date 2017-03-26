<?php

namespace Siphon\Cache;

use Siphon\Redis\Redis;

class RedisStore implements Store
{
    /**
     * The redis database instance
     *
     * @var \Siphon\Redis\Redis
     */
    protected $redis;

    /**
     * The redis connection name
     *
     * @var string
     */
    protected $connection;

    /**
     * @param \Siphon\Redis\Redis $redis
     * @param string              $connection
     */
    public function __construct(Redis $redis, $connection = 'default')
    {
        $this->redis = $redis;
        $this->setConnection($connection);
    }

    /**
     * {@inheritdoc}
     */
    public function exists($key)
    {
        return (bool) $this->connection()->exists($key);
    }

    /**
     * {@inheritdoc}
     */
    public function get($key)
    {
        if (! is_null($value = $this->connection()->get($key))) {
            return $this->unserialize($value);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function save($key, $value, $minutes)
    {
        $value = $this->serialize($value);

        $this->connection()->setex($key, (int) max(1, $minutes * 60), $value);
    }

    /**
     * {@inheritdoc}
     */
    public function forever($key, $value)
    {
        $this->connection()->set($key, $this->serialize($value));
    }

    /**
     * {@inheritdoc}
     */
    public function remove($key)
    {
        return (bool) $this->connection()->del($key);
    }

    /**
     * {@inheritdoc}
     */
    public function flush()
    {
        $this->connection()->flushdb();
    }

    /**
     * Get the redis connection instance
     *
     * @return \Predis\ClientInterface
     */
    public function connection()
    {
        return $this->redis->connection($this->connection);
    }

    /**
     * Set the redis connection instance
     *
     * @param string $connection
     * @return \Siphon\Cache\Store
     */
    public function setConnection($connection)
    {
        $this->connection = $connection;

        return $this;
    }

    /**
     * Get the redis database instance
     *
     * @return \Siphon\Redis\Redis
     */
    public function getRedis()
    {
        return $this->redis;
    }

    /**
     * Serialize the value
     *
     * @param mixed $value
     * @return mixed
     */
    protected function serialize($value)
    {
        return is_numeric($value) ? $value : serialize($value);
    }

    /**
     * Unserialize the value
     *
     * @param mixed $value
     * @return mixed
     */
    protected function unserialize($value)
    {
        return is_numeric($value) ? $value : unserialize($value);
    }
}