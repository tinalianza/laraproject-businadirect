<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Vehicle;
use App\Models\VehicleOwner;
use App\Models\Registration;
use App\Models\ApplicantType;
use App\Models\Employee;
use App\Models\Student;
use App\Models\VehicleType;
use App\Models\User;
use App\Models\Transaction;
use GuzzleHttp\Client;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;
use PHPMailer\PHPMailer\PHPMailer;

class RenewController extends Controller
{
    /**
     * Display the form for renewing a vehicle.
     *
     * @param int $vehicle_id
     * @return \Illuminate\Http\Response
     */
    public function showRenewForm($vehicle_id)
    {
        // Fetch the vehicle along with the owner and type
        $vehicle = Vehicle::with(['vehicleOwner', 'vehicleType', 'vehicleOwner.user'])->find($vehicle_id);

        // Check if the vehicle exists
        if (!$vehicle) {
            return redirect()->back()->withErrors('Vehicle not found.');
        }

        // Pass the vehicle data, vehicle owner, and vehicle type to the view
        return view('auth.renew', [
            'vehicle' => $vehicle,
            'vehicle_owner' => $vehicle->vehicleOwner,
            'vehicle_type' => $vehicle->vehicleType, // Pass the vehicle type
            'applicant_type' => $vehicle->vehicleOwner->applicantType, // Pass the applicant type
            'driver_license_no' => $vehicle->vehicleOwner->driver_license_no, // Pass the driver's license number
            'email' => $vehicle->vehicleOwner->user ? $vehicle->vehicleOwner->user->email : null, // Safely access email
        ]);
    }


    public function store(Request $request)
    {
        // Retrieve the current vehicle owner
        $vehicleOwner = User::where('id', Auth::id())->first();
        if (!$vehicleOwner) {
            return back()->withErrors(['error' => 'Vehicle owner not found.']);
        }
    
        // Retrieve the vehicle by owner and plate number
        $vehicle = Vehicle::where('vehicle_owner_id', $vehicleOwner->id)
            ->where('plate_no', $request->input('plate_no'))
            ->first();
    
        if (!$vehicle) {
            return back()->withErrors(['error' => 'Vehicle not found.']);
        }
    
         // Generate registration and reference numbers before creating the transaction
        $registrationNo = '2024-' . str_pad($vehicle->id, 5, '0', STR_PAD_LEFT); // Use vehicle ID or transaction ID for this
        $referenceNo = 'REF' . str_pad($vehicle->id, 8, '0', STR_PAD_LEFT); // Same here, adjust as needed
        
        // Validate the incoming request
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'applicant_type' => 'required|string|in:BU-personnel,Non-Personnel,Student,VIP',
            'id_no' => 'nullable|string',
            'email' => [
                'required', 'email', 'max:255',
                Rule::unique('users')->ignore(Auth::id()), // Ignore logged-in user's email
            ],
            'contact_no' => [
                'required', 'digits:10',
                Rule::unique('vehicle_owner')->ignore($vehicleOwner->id),
            ],
            'vehicle_type' => 'required|string|in:2-wheel,4-wheel',
            'driver_license_no' => [
                'required', 'string', 'max:20',
                Rule::unique('vehicle_owner')->ignore($vehicleOwner->id),
            ],
            'vehicle_model' => 'required|string|max:255',
            'plate_no' => [
                'required', 'string', 'max:10',
                Rule::unique('vehicle')->ignore($vehicle->id), // Ignore the current vehicle ID
            ],
            'or_no' => [
                'required', 'string', 'max:18',
                Rule::unique('vehicle')->ignore($vehicle->id), // Ignore the current vehicle ID
            ],
            'cr_no' => [
                'required', 'string', 'max:9',
                Rule::unique('vehicle')->ignore($vehicle->id), // Ignore the current vehicle ID
            ],
            'scanned_or_cr' => 'required|file|mimes:pdf,jpg,jpeg,png|max:2048',
            'scanned_license' => 'required|file|mimes:pdf,jpg,jpeg,png|max:2048',
            'expiration' => 'required|date',
            'total_due' => 'required|numeric',
        ]);
    
        // Store and encrypt uploaded files
        $scannedOrCrPath = $request->file('scanned_or_cr')->store('public/files');
        $scannedLicensePath = $request->file('scanned_license')->store('public/files');
    
        $encryptedOrCr = Crypt::encrypt(Storage::get($scannedOrCrPath));
        $encryptedDriverLicense = Crypt::encrypt(Storage::get($scannedLicensePath));
    
        // Update vehicle owner and vehicle details
        $vehicleOwner->update([
            'fname' => trim(explode(',', $validatedData['name'])[1] ?? ''),
            'lname' => trim(explode(',', $validatedData['name'])[0] ?? ''),
            'mname' => trim(explode(',', $validatedData['name'])[2] ?? ''),
            'contact_no' => $validatedData['contact_no'],
            'driver_license_no' => $validatedData['driver_license_no'],
        ]);
    
        $vehicle->update([
            'or_no' => $validatedData['or_no'],
            'cr_no' => $validatedData['cr_no'],
            'expiry_date' => $validatedData['expiration'],
            'copy_or_cr' => $encryptedOrCr,
            'copy_driver_license' => $encryptedDriverLicense,
        ]);
    
        // Create a new renewal transaction
        $transaction = Transaction::create([
            'vehicle_id' => $vehicle->id,
            'transac_type' => 'renewal',
            'amount_payable' => $validatedData['total_due'],
            'apply_date' => now()->toDateString(),
            'issued_date' => now()->toDateString(),
            'sticker_expiry' => now()->addYear()->toDateString(),
            'claiming_status_id' => 1,
            'vehicle_status' => $this->getVehicleStatus($validatedData['expiration']),
            'registration_no' => $registrationNo, // Include registration number here
            'reference_no' => $referenceNo, //
        ]);
    
    
        // Send confirmation email
        $this->sendConfirmationEmail($validatedData, $registrationNo);

        return redirect()->route('new-application-confirmation');
    }

    /**
     * Send confirmation email to the user.
     */
    private function sendConfirmationEmail($data, $registrationNo)
    {
        $mail = new PHPMailer(true);
        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com';
        $mail->SMTPAuth = true;
        $mail->Username = 'ronabalangat2003@gmail.com';
        $mail->Password = 'dsae bzxj zikj tbxy';
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port = 587;
    
        $mail->setFrom('busina@example.com', 'BUsina');
        $mail->addAddress($data['email'], $data['name']);
    
        $mail->isHTML(true);
        $mail->Subject = 'Vehicle Renewal Successful';
        $mail->Body = "
            <html>
            <body>
                <h2>Renewal Successful!</h2>
                <p>Your vehicle renewal is complete. Here are your details:</p>
                <p><strong>Registration No:</strong> {$registrationNo}</p>
                <p><strong>Vehicle Model:</strong> {$data['vehicle_model']}</p>
                <p><strong>Plate No:</strong> {$data['plate_no']}</p>
                <p>Thank you for using our service!</p>
            </body>
            </html>";
    
        $mail->send();
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