<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Application;
use Illuminate\Support\Facades\Hash;

class PaymentSuccessController extends Controller
{
    public function index(Request $request)
{

    $data = $request->session()->get('applicationData');

    if (!$data) {
        return redirect()->route('register')->withErrors(['error' => 'No application data found.']);
    }

    $requiredFields = ['name', 'email', 'applicant_type', 'employee_id', 'contact_no', 'vehicle_type', 'driver_license', 'vehicle_model', 'plate_number', 'or_number', 'cr_number', 'expiration', 'total_due'];

    foreach ($requiredFields as $field) {
        if (!isset($data[$field])) {
            return redirect()->route('register')->withErrors(['error' => "Missing required field: $field"]);
        }
    }

    try {

        $user = User::where('email', $data['email'])->first();

        if (!$user) {

            $user = User::create([
                'name' => $data['name'],
                'email' => $data['email'],
                'password' => Hash::make('BUsina2024'),
            ]);
        }

        Application::create([
            'user_id' => $user->id,
            'applicant_type' => $data['applicant_type'],
            'employee_id' => $data['employee_id'],
            'contact_no' => $data['contact_no'],
            'vehicle_type' => $data['vehicle_type'],
            'driver_license' => $data['driver_license'],
            'vehicle_model' => $data['vehicle_model'],
            'plate_number' => $data['plate_number'],
            'or_number' => $data['or_number'],
            'cr_number' => $data['cr_number'],
            'expiration' => $data['expiration'],
            'total_due' => $data['total_due'],
    
        ]);

       
        $request->session()->forget('applicationData');

        return view('payment-success'); 
    } catch (\Exception $e) {

        \Log::error('Payment success handling failed: '.$e->getMessage());
        return back()->withErrors(['error' => 'Failed to complete payment process. Please contact support.']);
    }
}

}