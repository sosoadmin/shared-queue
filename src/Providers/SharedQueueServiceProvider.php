<?php
// src/Providers/SharedQueueServiceProvider.php

namespace SharedQueue\Providers;

use Illuminate\Support\ServiceProvider;
use SharedQueue\Services\QueueService;

class SharedQueueServiceProvider extends ServiceProvider
{
    public function boot()
    {
        $this->publishes([
            __DIR__.'/../Config/shared-queue.php' => config_path('shared-queue.php'),
        ]);
    }

    public function register()
    {
        $this->app->singleton(QueueService::class, function ($app) {
            return new QueueService(config('shared-queue'));
        });
    }
}