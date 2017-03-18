<?php

namespace Siphon\Foundation;

use Illuminate\Support\ServiceProvider as BaseProvider;

class ServiceProvider extends BaseProvider
{
    /**
     * @var \Siphon\Foundation\Application
     */
    protected $app;

    /**
     * @param \Siphon\Foundation\Application $app
     */
    public function __construct(Application $app)
    {
        parent::__construct($app);
    }
}