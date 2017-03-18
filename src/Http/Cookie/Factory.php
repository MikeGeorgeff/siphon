<?php

namespace Siphon\Http\Cookie;

use Carbon\Carbon;
use Dflydev\FigCookies\SetCookie;

class Factory
{
    /**
     * @var string
     */
    protected $path;

    /**
     * @var string
     */
    protected $domain;

    /**
     * @var bool
     */
    protected $secure;

    /**
     * All cookies queued for sending
     *
     * @var array
     */
    protected $queued = [];

    /**
     * @param string $domain
     * @param string $path
     * @param bool   $secure
     */
    public function __construct($domain, $path = '/', $secure = false)
    {
        $this->domain = $domain;
        $this->path   = $path;
        $this->secure = $secure;
    }

    /**
     * Make a new cookie instance
     *
     * @param string $name
     * @param mixed  $value
     * @param int    $minutes
     * @return \Dflydev\FigCookies\SetCookie
     */
    public function make($name, $value = null, $minutes = 0)
    {
        $time = ($minutes == 0) ? 0 : Carbon::now()->getTimestamp() + ($minutes * 60);

        return SetCookie::create($name, $value)
                        ->withExpires($time)
                        ->withDomain($this->domain)
                        ->withPath($this->path)
                        ->withSecure($this->secure)
                        ->withHttpOnly(true);
    }

    /**
     * Make a new cookie instance that lasts forever (5 years)
     *
     * @param string $name
     * @param mixed  $value
     * @return \Dflydev\FigCookies\SetCookie
     */
    public function forever($name, $value = null)
    {
        return $this->make($name, $value, 2628000);
    }

    /**
     * Expire a cookie instance
     *
     * @param string $name
     * @return \Dflydev\FigCookies\SetCookie
     */
    public function expire($name)
    {
        return $this->make($name, null, -2628000);
    }

    /**
     * Queue a cookie to send with the next response
     *
     * @param \Dflydev\FigCookies\SetCookie $cookie
     * @return void
     */
    public function queue(SetCookie $cookie)
    {
        $this->queued[$cookie->getName()] = $cookie;
    }

    /**
     * Determine if the given cookie is queued
     *
     * @param string $name
     * @return bool
     */
    public function isQueued($name)
    {
        return array_key_exists($name, $this->queued);
    }

    /**
     * Get all the cookies that have been queued for the next request
     *
     * @return array
     */
    public function getQueuedCookies()
    {
        return $this->queued;
    }
}