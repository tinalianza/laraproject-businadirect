<?php

namespace App\Http;

class AdyenClient
{
    public $service;

    function __construct() {
        $client = new \Adyen\Client();
        $client->setXApiKey(env('AQEhhmfuXNWTK0Qc+iSSp3cxqeXMRE1voKO8kg3A9Gl3i5ZVEMFdWw2+5HzctViMSCJMYAc=-kaT8Z8+O19iqA+kvNMxJYL0IzFT+X7Y2g4KjgW1BljA=-i1in&_v)c.{7IL(uGR+'));
        $client->setEnvironment(\Adyen\Environment::TEST);

        $this->service = new \Adyen\Service\Checkout($client);
    }
}


