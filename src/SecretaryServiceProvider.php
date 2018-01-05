<?php

namespace Dymantic\Secretary;

use Illuminate\Support\ServiceProvider;

class SecretaryServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->app->bind(Secretary::class, function() {
           return new Secretary(config('secretary'));
        });
    }
}