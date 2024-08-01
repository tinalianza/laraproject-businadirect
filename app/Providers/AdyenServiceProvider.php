<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Adyen\Client;
use Adyen\Service\Checkout\PaymentsApi;

class AdyenServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton(Client::class, function ($app) {
            $client = new Client();
            $client->setXApiKey(config('adyen.api_key'));
            $client->setEnvironment(config('adyen.environment') === 'live' ? \Adyen\Environment::LIVE : \Adyen\Environment::TEST);
            return $client;
        });

        $this->app->singleton(PaymentsApi::class, function ($app) {
            $client = $app->make(Client::class);
            return new PaymentsApi($client);
        });
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
