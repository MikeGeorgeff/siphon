<?php

namespace Siphon\Http\Session;

use SessionHandlerInterface;
use Siphon\Cache\Repository;

class CacheSessionHandler implements SessionHandlerInterface
{
    /**
     * @var \Siphon\Cache\Repository
     */
    protected $cache;

    /**
     * The length the session will be stored in minutes
     *
     * @var int
     */
    protected $ttl;

    /**
     * @param \Siphon\Cache\Repository $cache
     * @param int                      $ttl
     */
    public function __construct(Repository $cache, $ttl = 120)
    {
        $this->cache = $cache;
        $this->ttl   = $ttl;
    }

    /**
     * {@inheritdoc}
     */
    public function open($savePath, $sessionName)
    {
        return true;
    }

    /**
     * {@inheritdoc}
     */
    public function close()
    {
        return true;
    }

    /**
     * {@inheritdoc}
     */
    public function read($sessionId)
    {
        return $this->cache->get($sessionId, '');
    }

    /**
     * {@inheritdoc}
     */
    public function write($sessionId, $data)
    {
        return $this->cache->save($sessionId, $data, $this->ttl);
    }

    /**
     * {@inheritdoc}
     */
    public function destroy($sessionId)
    {
        return $this->cache->remove($sessionId);
    }

    /**
     * {@inheritdoc}
     */
    public function gc($lifetime)
    {
        return true;
    }
}