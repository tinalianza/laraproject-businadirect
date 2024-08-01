<?php

namespace App\Providers;


use Illuminate\Support\ServiceProvider;
use App\Services\AdyenService;
use App\Services\PaymentService;

class AppServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->app->singleton(AdyenService::class, function ($app) {
            return new AdyenService();
        });

        $this->app->singleton(PaymentService::class, function ($app) {
            return new PaymentService();
        });
    }
}
