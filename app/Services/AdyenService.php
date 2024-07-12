<?php

namespace App\Services;

use Adyen\Client;
use Adyen\Environment;
use Adyen\Service\Checkout;

class AdyenService
{
    protected $client;
    protected $checkout;

    public function __construct()
    {
        $this->client = new Client();
        $this->client->setXApiKey(env('ADYEN_API_KEY'));

        // Determine environment (test or live) and set API URL accordingly
        if (env('ADYEN_ENVIRONMENT') === 'live') {
            $this->client->setEnvironment(Environment::LIVE);
        } else {
            $this->client->setEnvironment(Environment::TEST);
        }

        $this->checkout = new Checkout($this->client);
    }

    public function initiatePayment(array $paymentData)
    {
        try {
            $response = $this->checkout->payments($paymentData);
            return $response;
        } catch (\Exception $e) {
            throw new \Exception('Failed to initiate payment: ' . $e->getMessage());
        }
    }
}
