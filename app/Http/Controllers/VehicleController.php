<?php
namespace App\Http\Controllers;

use App\Models\Vehicle;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\User;        // Add this line for the User model
use App\Models\Application; // Add this line for the Application model
use GuzzleHttp\Client;
use App\Models\VehicleOwner;  
class VehicleController extends Controller 
{
    public function index()
    {
        // Fetch the logged-in user's vehicle owner record
        $vehicleOwner = Auth::user()->vehicleOwner;

        // Retrieve the vehicles owned by this vehicle owner
        $vehicles = $vehicleOwner ? $vehicleOwner->vehicles : [];

        // Return the view with the vehicles
        return view('vehiclelist', compact('vehicles', 'vehicleOwner'));
    }

    public function addvehicleshow()
    {
        $vehicleOwner = auth()->user(); // Get the authenticated user
        return view('auth.addvehicle'); // Replace with your actual view name
    }

    public function renewshow()
    {
        $vehicleOwner = auth()->user(); // Get the authenticated user
        return view('auth.renew'); // Replace with your actual view name
    }

public function paymentSuccess(Request $request)
{
    DB::beginTransaction();

    try {
        // Fetch application data from session
        $applicationData = $request->session()->get('applicationData');

        // Find the vehicle by plate number
        $vehicle = Vehicle::where('plate_no', $applicationData['plate_number'])->first();

        if ($vehicle) {
            // Update vehicle's sticker expiry and transaction type
            $vehicle->sticker_expiry = now()->addYear(); // Renew sticker expiry for 1 year
            $vehicle->transaction_type = '2'; // Renewal type
            $vehicle->save();

            // Insert a new record in the transaction table
            Transaction::create([
                'vehicle_id' => $vehicle->id,
                'reference_no' => 'REF' . strtoupper(uniqid()), // Generate unique reference number
                'registration_no' => 'REG' . strtoupper(uniqid()), // Generate unique registration number
                'emp_id' => $applicationData['employee_id'] ?? null, // Use null if not present
                'created_at' => now(),
                'updated_at' => now(),
                'claiming_status_id' => 1, // Adjust status based on your logic
                'apply_date' => now(),
                'issued_date' => now(),
                'vehicle_status' => 'active', // Set vehicle status
                'sticker_expiry' => now()->addYear(), // Set the sticker expiry for one year from today
                'amount_payable' => $applicationData['total_due'] ?? 0, // Use 0 if not present
                'transac_type' => '2', // 2 indicates renewal
            ]);
        }

        DB::commit();

        // Redirect to payment success page
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
