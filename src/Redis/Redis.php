<?php

namespace Siphon\Redis;

use Predis\Client;

class Redis
{
    /**
     * Array of predis clients
     *
     * @var array
     */
    protected $clients;

    /**
     * @param array $servers
     */
    public function __construct(array $servers)
    {
        $this->clients = $this->createClients($servers);
    }

    /**
     * Get the redis connection instance
     *
     * @param string $name
     * @return \Predis\ClientInterface
     */
    public function connection($name = 'default')
    {
        $key = array_key_exists($name, $this->clients) ? $name : 'default';

        return $this->clients[$key];
    }

    /**
     * Run a command against the redis database
     *
     * @param string $method
     * @param array  $parameters
     * @return mixed
     */
    public function command($method, array $parameters = [])
    {
        return call_user_func_array([$this->connection(), $method], $parameters);
    }

    /**
     * Create an array of clients
     *
     * @param array $servers
     * @return array
     */
    protected function createClients(array $servers)
    {
        $clients = [];

        foreach ($servers as $key => $server) {
            $clients[$key] = new Client($server, ['timeout' => 10.0]);
        }

        return $clients;
    }
}