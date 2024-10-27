<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Vehicle;
use GuzzleHttp\Client;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Auth;
use App\Models\Registration;
use App\Models\ApplicantType;
use App\Models\Employee;
use App\Models\Student;
use App\Models\VehicleType;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use Illuminate\Support\Facades\Storage;
use Endroid\QrCode\QrCode;
use Endroid\QrCode\Writer\PngWriter;
use Endroid\QrCode\ErrorCorrectionLevel\ErrorCorrectionLevelLow; 
use Endroid\QrCode\Encoding\Encoding;
use Endroid\QrCode\Builder\Builder;
use App\Models\VehicleOwner;
use Carbon\Carbon;
use App\Models\Transaction;
use Illuminate\Support\Facades\Crypt;

class AddVehicleController extends Controller
{
    /**
     * Display the form for renewing a vehicle.
     *
     * @param int $vehicle_id
     * @return \Illuminate\Http\Response
     */
    public function showAddVehicleForm($vehicle_id)
    {
        // Fetch the vehicle along with the owner and type
        $vehicle = Vehicle::with(['vehicleOwner', 'vehicleType'])->find($vehicle_id);

        // Check if the vehicle exists
        if (!$vehicle) {
            return redirect()->back()->withErrors('Vehicle not found.');
        }

        // Pass the vehicle data, vehicle owner, and vehicle type to the view
        return view('auth.addvehicle', [
            'vehicle' => $vehicle,
            'vehicle_owner' => $vehicle->vehicleOwner,
            'vehicle_type' => $vehicle->vehicleType,
            'applicant_type' => $vehicle->vehicleOwner->applicantType,
            'driver_license_no' => $vehicle->vehicleOwner->driver_license_no,
            'email' => $vehicle->vehicleOwner->user ? $vehicle->vehicleOwner->user->email : null,
        ]);
    }

    /**
     * Handle the vehicle renewal process.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $vehicle_id
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request) 
{
    // Validate the incoming request
    $validatedData = $request->validate([
        'vehicle_model' => 'required|string|max:255',
        'vehicle_type' => 'required|string|in:2-wheel,4-wheel',
        'plate_no' => [
            'required',
            'string',
            'max:10',
            Rule::unique('vehicle')->where(function ($query) {
                return $query->where('vehicle_owner_id', Auth::user()->vehicleOwner->id);
            }),
        ],
        'or_no' => 'required|string|max:18',
        'cr_no' => 'required|string|max:9',
        'scanned_or_cr' => 'required|file|mimes:pdf,jpg,jpeg,png|max:2048',
        'scanned_license' => 'required|file|mimes:pdf,jpg,jpeg,png|max:2048',
        'scanned_id' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:2048',
        'certificate' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:2048',
        'expiration' => 'required|date',
        'total_due' => 'required|numeric',
    ]);

    // Find the vehicle owner based on the logged-in user's ID
    $vehicleOwner = User::where('id', Auth::id())->first()->vehicleOwner;

    if (!$vehicleOwner) {
        return response()->json(['error' => 'Vehicle owner not found.'], 404);
    }

    // Create a new vehicle record for the same owner
    $vehicle = new Vehicle([
        'vehicle_owner_id' => $vehicleOwner->id, // Use the correct owner ID
        'model_color' => $validatedData['vehicle_model'],
        'plate_no' => $validatedData['plate_no'],
        'or_no' => $validatedData['or_no'],
        'cr_no' => $validatedData['cr_no'],
        'expiry_date' => $validatedData['expiration'],
        'vehicle_type_id' => VehicleType::where('vehicle_type', $request->input('vehicle_type'))->first()->id,
    ]);

    // Store files and hash their contents
    if ($request->hasFile('scanned_or_cr')) {
        $scannedOrCrPath = $request->file('scanned_or_cr')->store('public/files');
        $vehicle->copy_or_cr = Hash::make(Storage::get($scannedOrCrPath));
    }

    if ($request->hasFile('scanned_license')) {
        $scannedLicensePath = $request->file('scanned_license')->store('public/files');
        $vehicle->copy_driver_license = Hash::make(Storage::get($scannedLicensePath));
    }

    if ($request->hasFile('scanned_id')) {
        $scannedIdPath = $request->file('scanned_id')->store('public/files');
        $vehicle->copy_school_id = Hash::make(Storage::get($scannedIdPath));
    }

    if ($request->hasFile('certificate')) {
        $certificatePath = $request->file('certificate')->store('public/files');
        $vehicle->copy_cor = Hash::make(Storage::get($certificatePath));
    }

    try {
        // Save the vehicle
        $vehicle->save();
        // Generate registration and reference numbers
        $registrationNo = '2024-' . str_pad($vehicle->id, 5, '0', STR_PAD_LEFT);
        $referenceNo = 'REF' . str_pad($vehicle->id, 8, '0', STR_PAD_LEFT); // Assuming you want to use vehicle ID for REF
        
        // Create the transaction record
        $transaction = Transaction::create([
            'vehicle_id' => $vehicle->id,
            'transac_type' => 'first_registration',
            'amount_payable' => $validatedData['total_due'],
            'claiming_status_id' => 1,
            'vehicle_status' => $this->getVehicleStatus($validatedData['expiration']),
            'apply_date' => Carbon::now()->toDateString(),
            'registration_no' => $registrationNo,
            'reference_no' => $referenceNo, 
            'sticker_expiry' => Carbon::now()->addYear()->toDateString(),
        ]);

        return redirect()->route('new-application-confirmation');

    } catch (\Exception $e) {
        Log::error('Error adding vehicle: ' . $e->getMessage());
        return back()->withErrors('An error occurred. Please try again.');
    }
}
private function getVehicleStatus($expirationDate)
{
   
    $expirationDate = Carbon::parse($expirationDate);
    $today = Carbon::today();

    if ($expirationDate < $today) {
        return 'expired';
    } elseif ($expirationDate->diffInDays($today) <= 30) { 
        return 'pending for renewal';
    } else {
        return 'registered';
    }
}    



}