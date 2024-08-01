<?php

namespace App\Services;

use Adyen\Client;
use Adyen\Environment;
use Adyen\Service\Checkout;
use Illuminate\Http\Request;

class AdyenService
{
    protected $client;
    protected $checkout;

    public function __construct()
    {
        $this->client = new Client();
        $this->client->setXApiKey(env('ADYEN_API_KEY'));

        if (env('ADYEN_ENVIRONMENT') === 'live') {
            $this->client->setEnvironment(Environment::LIVE);
        } else {
            $this->client->setEnvironment(Environment::TEST);
        }

        $this->checkout = new Checkout($this->client);
    }

    public function createPaymentSession($amount, $reference, $countryCode = 'PH')
    {
        if ($amount <= 0) {
            throw new \Exception('Amount must be greater than zero.');
        }

        $params = [
            "amount" => [
                "currency" => "PHP",
                "value" => $amount * 100 // Adyen requires amount in the smallest currency unit (e.g., cents)
            ],
            "reference" => $reference,
            "merchantAccount" => env('ADYEN_MERCHANT_ACCOUNT'),
            "returnUrl" => route('payment.success'),
            "countryCode" => $countryCode
        ];

        try {
            $response = $this->checkout->paymentSession($params);
            return $response;
        } catch (\Adyen\AdyenException $e) {
            throw new \Exception('Adyen Exception: ' . $e->getMessage());
        } catch (\Exception $e) {
            throw new \Exception('Failed to create payment session: ' . $e->getMessage());
        }
    }
}
