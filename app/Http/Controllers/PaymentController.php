<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Application;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use GuzzleHttp\Client;


class PaymentController extends Controller
{
    public function createPayment(Request $request)
    {
        $totalDue = $request->input('total_due'); 
        $client = new Client();

        // Log a warning if total due is below the minimum requirement
        if ($totalDue < 2000) {
            \Log::warning('Attempt to create payment below minimum amount: ' . $totalDue . ' PHP');
        } else {
            \Log::info('Attempting to create payment for amount: ' . $totalDue . ' PHP');
        }
        
        $successUrl = 'http://localhost:8000';  
        $failedUrl = route('application-pending');   

        try {
            $response = $client->request('POST', 'https://api.paymongo.com/v1/checkout_sessions', [
                'body' => json_encode ([
                    'data' => [
                        'attributes' => [
                            'send_email_receipt' => true,
                            'show_description' => false,
                            'show_line_items' => true,
                            'line_items' => [
                                [
                                    'currency' => 'PHP',
                                    'amount' => $totalDue * 100, 
                                    'description' => 'BU Motorpool Vehicle Sticker Pass', 
                                    'name' => 'Vehicle Sticker Pass',
                                    'quantity' => 1
                                ]
                            ],
                            'payment_method_types' => ['gcash'],
                            'reference_number' => 'PAYMENT' . date('YmdHis'),
                            'success_url' => $successUrl,
                            'failed_url' => $failedUrl,
                        ]
                    ]
                ]),
                'headers' => [
                    'Content-Type' => 'application/json',
                    'accept' => 'application/json',
                    'authorization' => 'Basic c2tfdGVzdF9kbmVkNDQ2ODlvS25wZ0hGODlQVExSc0E6',
                ]
            ]);

            $body = json_decode((string) $response->getBody(), true);
            $checkoutUrl = $body['data']['attributes']['checkout_url'];

            return redirect($checkoutUrl);
        } catch (\Exception $e) {

            \Log::error('Payment creation failed: '.$e->getMessage());
            return back()->withErrors(['error' => 'Unable to create payment session. Please try again.']);
        }
    }

    public function paymentSuccess(Request $request)
    {
        DB::beginTransaction();
        
        try {
            $applicationData = $request->session()->get('applicationData');
    

            $user = User::where('email', $applicationData['email'])->first();
    
            if (!$user) {

                $user = User::create([
                    'name' => $applicationData['name'],
                    'email' => $applicationData['email'],
                    'password' => bcrypt($applicationData['password'])
                ]);
            }
    
            $application = Application::create([
                'user_id' => $user->id, 
                'name' => $applicationData['name'],
                'applicant_type' => $applicationData['applicant_type'],
                'employee_id' => $applicationData['employee_id'],
                'email' => $applicationData['email'],
                'contact_no' => $applicationData['contact_no'],
                'vehicle_type' => $applicationData['vehicle_type'],
                'driver_license' => $applicationData['driver_license'],
                'vehicle_model' => $applicationData['vehicle_model'],
                'plate_number' => $applicationData['plate_number'],
                'or_number' => $applicationData['or_number'],
                'cr_number' => $applicationData['cr_number'],
                'expiration' => $applicationData['expiration'],
                'total_due' => $applicationData['total_due'],
            ]);
    
            DB::commit();
    
            return redirect()->route('payment-success')->with('success', 'Payment completed and data saved successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('Payment success handling failed: ' . $e->getMessage());
            return back()->withErrors(['error' => 'Payment success handling failed. Please try again.']);
        }
    }
    

    public function paymentFailed()
    {
        return view('payment-failed'); 
    }
}