<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\AdyenService;

class PaymentController extends Controller
{
    protected $adyenService;

    public function __construct(AdyenService $adyenService)
    {
        $this->adyenService = $adyenService;
    }

    public function paymentSuccess()
    {
        // Logic to handle payment success
        return view('payment-success'); // Adjust with your actual view name
    }

    public function initiatePayment(Request $request)
    {
        // Example validation (you should implement your own validation rules)
        $request->validate([
            'amount' => 'required|numeric',
            // Add more validation rules as needed
        ]);

        // Example payment data
        $paymentData = [
            'amount' => $request->input('amount'),
            // Add more data fields as required by Adyen
        ];

        try {
            // Initiate payment through AdyenService
            $paymentResponse = $this->adyenService->initiatePayment($paymentData);

            // Assuming $paymentResponse contains the Adyen redirection URL
            return redirect()->away($paymentResponse['redirectUrl']); // Redirect to Adyen payment page
        } catch (\Exception $e) {
            // Handle any exceptions (e.g., log, return error response, etc.)
            return back()->withError('Failed to initiate payment: ' . $e->getMessage());
        }
    }
}
