<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\AdyenService;
use Illuminate\Support\Facades\Log;

class RegistrationController extends Controller
{
    public function showRegistrationForm()
    {
        return view('auth.register');
    }

    public function register(Request $request, AdyenService $adyenService)
    {
        Log::info('Register request data:', $request->all());

        // Validate the form data
        $validatedData = $request->validate([
            'total_due' => 'required|string',
        ]);

        $amount = (int)($validatedData['total_due']);
        Log::info('Calculated amount:', ['amount' => $amount]);

        $paymentData = [
            'amount' => [
                'currency' => 'PHP',
                'value' => $amount,
            ],
            'reference' => uniqid('BU-'),
            'paymentMethod' => [
                'type' => 'scheme',
            ],
            'returnUrl' => route('payment.success'),
        ];

        try {
            $paymentResponse = $adyenService->initiatePayment($paymentData);
            Log::info('Adyen payment response:', $paymentResponse);

            return redirect()->away($paymentResponse['redirectUrl']);
        } catch (\Exception $e) {
            Log::error('Failed to initiate payment:', ['error' => $e->getMessage()]);
            return redirect()->back()->with('error_message', 'Failed to initiate payment: ' . $e->getMessage());
        }
    }
}
