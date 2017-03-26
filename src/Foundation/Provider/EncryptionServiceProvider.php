<?php

namespace Siphon\Foundation\Provider;

use Illuminate\Support\Str;
use Illuminate\Encryption\Encrypter;
use Siphon\Foundation\ServiceProvider;
use Illuminate\Contracts\Encryption\Encrypter as EncrypterContract;

class EncryptionServiceProvider extends ServiceProvider
{
    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton('encrypter', function ($app) {
            $config = $app->make('config')->get('app');

            if (Str::startsWith($key = $config['key'], 'base64:')) {
                $key = base64_decode(substr($key, 7));
            }

            return new Encrypter($key, $config['cipher']);
        });

        $this->app->alias('encrypter', Encrypter::class);
        $this->app->alias('encrypter', EncrypterContract::class);
    }
}