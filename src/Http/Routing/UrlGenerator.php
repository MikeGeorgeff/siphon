<?php

namespace Siphon\Http\Routing;

use Illuminate\Support\Str;

class UrlGenerator
{
    /**
     * @var \Siphon\Http\Routing\RouteCollection
     */
    protected $routes;

    /**
     * @param \Siphon\Http\Routing\RouteCollection $routes
     */
    public function __construct(RouteCollection $routes)
    {
        $this->routes = $routes;
    }

    /**
     * Generate a url to the given path
     *
     * @param string $path
     * @param bool   $secure
     * @return string
     */
    public function to($path, $secure = false)
    {
        if ($this->isValidUrl($path)) {
            return $path;
        }

        $path = '/'.trim($path, '/');

        return trim($this->root($secure) . $path, '/');
    }

    /**
     * Generate a url from a route name
     *
     * @param string $name
     * @param array  $parameters
     * @return string
     */
    public function route($name, array $parameters = [])
    {
        $path = $this->routes->getPath($name, $parameters);

        return $this->to($path);
    }

    /**
     * Generate a url to the current url
     *
     * @return string
     */
    public function current()
    {
        return $this->to($this->server('REQUEST_URI'));
    }

    /**
     * Generate a url to the previous location
     *
     * @param string|null $fallback
     * @return string
     */
    public function previous($fallback = null)
    {
        $referrer = $this->server('HTTP_REFERER');

        if ($referrer) {
            return $this->to($referrer);
        } elseif ($fallback) {
            return $this->to($fallback);
        } else {
            return $this->to('/');
        }
    }

    /**
     * Get the root url
     *
     * @param bool $secure
     * @return string
     */
    public function root($secure = false)
    {
        return $this->protocol($secure) . $this->server('SERVER_NAME');
    }

    /**
     * Determine if the given path is a valid URL
     *
     * @param  string  $path
     * @return bool
     */
    public function isValidUrl($path)
    {
        if (! Str::startsWith($path, ['#', '//', 'mailto:', 'tel:', 'http://', 'https://'])) {
            return filter_var($path, FILTER_VALIDATE_URL) !== false;
        }

        return true;
    }

    /**
     * Get the http protocol
     *
     * @param bool $forceSecure
     * @return string
     */
    public function protocol($forceSecure = false)
    {
        if ($forceSecure) {
            return 'https://';
        }

        return is_null($this->server('HTTPS')) ? 'http://' : 'https://';
    }

    /**
     * Access the $_SERVER superglobal
     *
     * @param $index
     * @return string|null
     */
    protected function server($index)
    {
        return isset($_SERVER[$index]) ? $_SERVER[$index] : null;
    }
}