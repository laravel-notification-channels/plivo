<?php

namespace NotificationChannels\Plivo;

use Illuminate\Support\ServiceProvider;

class PlivoServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     */
    public function boot()
    {
        $this->app->when(PlivoChannel::class)
            ->needs(Plivo::class)
            ->give(function () {
                return new Plivo(config('services.plivo'));
            });
    }
}
