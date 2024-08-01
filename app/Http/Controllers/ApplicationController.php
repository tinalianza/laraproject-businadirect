<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User; // Import User model
use App\Models\MotorpoolApplication; // Import MotorpoolApplication model

class ApplicationController extends Controller
{
    public function showConfirmation(Request $request)
    {

        $data = $request->session()->get('applicationData');

        return view('auth.application-confirmation', ['data' => $data]);
    }

    public function store(Request $request)
    {

        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'applicant_type' => 'required|string|max:255',
            'contact_no' => 'required|string|max:20',
            'email' => 'required|string|email|max:255',
            'vehicle_model' => 'required|string|max:255',
            'plate_number' => 'required|string|max:20',
            'total_due' => 'required|numeric',
        ]);


        $applicationData = $request->session()->get('applicationData');


        if (!$applicationData) {
            return redirect()->route('register')->with('error', 'No application data found.');
        }

        $user = User::create([
            'name' => $applicationData['name'],
            'email' => $applicationData['email'],
            'password' => bcrypt($applicationData['password']), // Encrypt password
        ]);

        MotorpoolApplication::create([
            'user_id' => $user->id,
            'applicant_type' => $applicationData['applicant_type'],
            'contact_no' => $applicationData['contact_no'],
            'vehicle_model' => $applicationData['vehicle_model'],
            'plate_number' => $applicationData['plate_number'],
            'total_due' => $applicationData['total_due'],

        ]);


        $request->session()->forget('applicationData');

        return redirect()->route('payment-success'); 
    }

    public function confirmation()
{
    // Check if the registration process is completed
    if (!session('registration_completed')) {
        return redirect('/'); // Redirect to home or another page
    }

    // Continue with the logic for displaying the confirmation page
    return view('application-confirmation');
}

}
