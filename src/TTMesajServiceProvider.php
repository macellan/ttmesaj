<?php

namespace Macellan\TTMesaj;

use Illuminate\Notifications\ChannelManager;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\ServiceProvider;

class TTMesajServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     */
    public function boot()
    {
        Notification::resolved(function (ChannelManager $service) {
            $service->extend('ttmesaj', function ($app) {
                return new TTMesajChannel(config('services.ttmesaj'));
            });
        });
    }

    /**
     * Register the application services.
     */
    public function register()
    {
    }
}
