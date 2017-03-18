<?php

namespace Siphon\View\Extension;

use League\Plates\Engine;
use Siphon\Http\Routing\UrlGenerator;
use League\Plates\Extension\ExtensionInterface;

class Url implements ExtensionInterface
{
    /**
     * @var \Siphon\Http\Routing\UrlGenerator
     */
    protected $url;

    /**
     * @param \Siphon\Http\Routing\UrlGenerator $url
     */
    public function __construct(UrlGenerator $url)
    {
        $this->url = $url;
    }

    /**
     * Register view functions
     *
     * @param \League\Plates\Engine $engine
     * @return void
     */
    public function register(Engine $engine)
    {
        $engine->registerFunction('route', [$this->url, 'route']);
        $engine->registerFunction('currentUrl', [$this->url, 'current']);
    }
}