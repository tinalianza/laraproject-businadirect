<?php
namespace App\Services;

use Illuminate\Support\Facades\Http;

class PayMongoService
{
    private $apiUrl = 'https://api.paymongo.com/v1/';
    private $apiKey = 'sk_test_dned44689oKnpgHF89PTLRsA';

    public function createPaymentLink($data)
    {
        $ch = curl_init($this->apiUrl . 'links');

        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Content-Type: application/json',
            'Authorization: Bearer ' . $this->apiKey
        ]);

        $response = curl_exec($ch);

        if (curl_errno($ch)) {
            return ['error' => curl_error($ch)];
        } else {
            return json_decode($response, true);
        }

        curl_close($ch);
    }
}
