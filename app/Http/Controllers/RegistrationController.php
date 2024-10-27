<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Registration;
use App\Models\ApplicantType;
use App\Models\Employee;
use App\Models\Student;
use App\Models\VehicleType;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use Illuminate\Support\Facades\Storage;
use Endroid\QrCode\QrCode;
use Endroid\QrCode\Writer\PngWriter;
use Endroid\QrCode\ErrorCorrectionLevel\ErrorCorrectionLevelLow; 
use Endroid\QrCode\Encoding\Encoding;
use Endroid\QrCode\Builder\Builder;
use App\Models\VehicleOwner;
use App\Models\Vehicle;
use Carbon\Carbon;
use App\Models\Transaction;
use Illuminate\Support\Facades\Crypt;


class RegistrationController extends Controller
{
    public function showRegistrationForm()
    {
        $applicantTypes = ApplicantType::all();

        $vehicleTypes = VehicleType::all();
        return view('auth.register', compact('applicantTypes', 'vehicleTypes'));
    }
    public function showAddVehicleForm()
    {
           // Assuming you want to get the authenticated user
    $user = auth()->user();

    return view('auth.addvehicle', compact('user'));
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

    public function showDashboard() {
        $registration = Registration::where('user_id', Auth::id())->first();
        
        $user = Auth::user();
        $fullName = $user->name; 
        $nameParts = explode(' ', $fullName);
        $initials = strtoupper(substr($nameParts[0], 0, 1) . (isset($nameParts[1]) ? substr($nameParts[1], 0, 1) : ''));
        
        return view('dashboard', compact('registration', 'initials'));
    }
       

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'name' => 'required|string|max:255|unique:vehicle_owner,fname',
            'applicant_type' => 'required|string|in:BU-personnel,Non-Personnel,Student,VIP',
            'id_no' => 'nullable|string',
            'email' => 'required|email|max:255|unique:users,email',
            'contact_no' => 'required|digits:10|unique:vehicle_owner,contact_no',
            'vehicle_type' => 'required|string|in:2-wheel,4-wheel',
            'driver_license' => 'required|string|max:20|unique:vehicle_owner,driver_license_no',
            'vehicle_model' => 'required|string|max:255',
            'plate_number' => 'required|string|max:10|unique:vehicle,plate_no',
            'or_number' => 'required|string|max:18|unique:vehicle,or_no',
            'cr_number' => 'required|string|max:9|unique:vehicle,cr_no',
            'scanned_or_cr' => 'required|file|mimes:pdf,jpg,jpeg,png|max:2048',
            'scanned_license' => 'required|file|mimes:pdf,jpg,jpeg,png|max:2048',
            'scanned_id' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:2048',
            'certificate' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:2048',
            'expiration' => 'required|date',
            'total_due' => 'required|numeric',
        ]);
    
     //   dd($validatedData);

        $nameParts = explode(',', $validatedData['name'] ?? '');

        $lname = trim($nameParts[0]);
        $fname = trim($nameParts[1]);
        $mname = count($nameParts) === 3 ? trim($nameParts[2]) : ''; 
    
            // Check if name already exists
        $nameExists = VehicleOwner::where('fname', $fname)
        ->where('lname', $lname)
        ->where('mname', $mname)
        ->exists();

        if ($nameExists) {
            return back()->withErrors(['name' => 'The name already exists in the vehicle owner records.']);
        }

        $applicantType = ApplicantType::where('type', $validatedData['applicant_type'])->first();
        if (!$applicantType) {
            return back()->withErrors(['applicant_type' => 'Invalid applicant type.']);
        }
    
        $vehicleType = VehicleType::where('vehicle_type', $validatedData['vehicle_type'])->first();
        if (!$vehicleType) {
            return back()->withErrors(['vehicle_type' => 'Invalid vehicle type.']);
        }

        // Check if ID No already exists in vehicle_owner table
        if (!empty($validatedData['id_no'])) {
            $idNoExistsInOwner = VehicleOwner::where('id_no', $validatedData['id_no'])->exists();
            if ($idNoExistsInOwner) {
                return back()->withErrors(['id_no' => 'The ID No already exists in the vehicle owner records.']);
            }
        }

         // Check if employee or student ID exists based on applicant type
        if ($applicantType->type !== 'Non-Personnel' && isset($validatedData['id_no'])) {
            $idNoExists = false;

            if ($applicantType->type === 'Student') {
                // Validate Student ID
                $idNoExists = Student::where('std_no', $validatedData['id_no'])->exists();
                if (!$idNoExists) {
                    return back()->withErrors(['id_no' => 'Student ID No does not exist. Contact the registrar.']);
                }
            } elseif (in_array($applicantType->type, ['BU-personnel', 'VIP'])) {
                // Validate Employee ID
                $idNoExists = Employee::where('emp_no', $validatedData['id_no'])->exists();
                if (!$idNoExists) {
                    return back()->withErrors(['id_no' => 'Employee ID No does not exist. Contact the registrar.']);
                }
            }
        }
        
        try {

            $scannedOrCrPath = $request->file('scanned_or_cr')->store('public/files');
            $scannedLicensePath = $request->file('scanned_license')->store('public/files');
            $scannedIdPath = $request->file('scanned_id') ? $request->file('scanned_id')->store('public/files') : null;
            $certificatePath = $request->file('certificate') ? $request->file('certificate')->store('public/files') : null;
    
            $vehicleOwner = VehicleOwner::create([
                'fname' => $fname,
                'mname' => $mname,
                'lname' => $lname,
                'id_no' => $validatedData['id_no'] ?? null,
                'contact_no' => $validatedData['contact_no'],
                'applicant_type_id' => $applicantType->id,
                'driver_license_no' => $validatedData['driver_license'],
                'qr_code' => null,
            ]);
    
            // Read the image file
            $orCrContent = Storage::get($scannedOrCrPath);
            $driverLicenseContent = Storage::get($scannedLicensePath);
            $corContent = $scannedIdPath ? Storage::get($scannedIdPath) : null;
            $schoolIdContent = $certificatePath ? Storage::get($certificatePath) : null;

            // Encrypt
            $encryptedOrCr = Crypt::encrypt($orCrContent);
            $encryptedDriverLicense = Crypt::encrypt($driverLicenseContent);
            $encryptedCor = $corContent ? Crypt::encrypt($corContent) : null;
            $encryptedSchoolId = $schoolIdContent ? Crypt::encrypt($schoolIdContent) : null;

            $vehicle = Vehicle::create([
                'vehicle_owner_id' => $vehicleOwner->id,
                'model_color' => $request->input('vehicle_model'),
                'plate_no' => $request->input('plate_number'),
                'or_no' => $request->input('or_number'),
                'cr_no' => $request->input('cr_number'),
                'expiry_date' => $request->input('expiration'),
                'copy_or_cr' => $encryptedOrCr,
                'copy_driver_license' => $encryptedDriverLicense,
                'copy_cor' => $encryptedCor,
                'copy_school_id' => $encryptedSchoolId,
                'vehicle_type_id' => VehicleType::where('vehicle_type', $request->input('vehicle_type'))->first()->id,
            ]);
    
            $transaction = Transaction::create([
                'vehicle_id' => $vehicle->id,
                'transac_type' => 'first_registration', 
                'amount_payable' => $validatedData['total_due'], 
                'reference_no' => '', 
                'claiming_status_id' => 1, 
                'vehicle_status' => $this->getVehicleStatus($validatedData['expiration']),
                'apply_date' => Carbon::now()->toDateString(),
                'issued_date' => null,
                'sticker_expiry' => Carbon::now()->addYear()->toDateString(),
            ]);
    
            $registrationNo = '2024-' . str_pad($transaction->id, 5, '0', STR_PAD_LEFT);
            $transaction->update([
                'registration_no' => $registrationNo,
            ]);
    
            $transaction->update([
                'reference_no' => 'REF' . str_pad($transaction->id, 8, '0', STR_PAD_LEFT),
            ]);

           // Generate QR Code
            $qr_data = $vehicleOwner->driver_license_no; 
            $qr_temp_dir = 'qrcodes/'; 
            $qr_file = $qr_temp_dir . 'user_' . $vehicleOwner->id . '.png'; 

            $result = Builder::create()
                ->writer(new PngWriter()) 
                ->data($qr_data) 
                ->encoding(new Encoding('UTF-8')) 
                ->errorCorrectionLevel(new ErrorCorrectionLevelLow())
                ->size(300) 
                ->margin(10) 
                ->build();

            // Save the QR code image to the specified file path
            $fullFilePath = storage_path('app/public/' . $qr_file); 
            $result->saveToFile($fullFilePath);

            // Read the contents of the file
            $qrCodeBinary = file_get_contents($fullFilePath);

            // Encode the binary data to base64
            $qrCodeBase64 = base64_encode($qrCodeBinary);

            // Update the vehicle owner record with the base64-encoded QR code
            $vehicleOwner->update([
                'qr_code' => $qrCodeBase64 
            ]);

            $user = User::create([
            'email' => $validatedData['email'],
            'password' => Hash::make('BUsina2024'), 
            'vehicle_owner_id' => $vehicleOwner->id,
            'authorized_user_id' => null,
            ]);

            $mail = new PHPMailer(true);


            $mail->isSMTP();
            $mail->Host       = 'smtp.gmail.com';
            $mail->SMTPAuth   = true;
            $mail->Username   = 'ronabalangat2003@gmail.com'; 
            $mail->Password   = 'dsae bzxj zikj tbxy';       
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
            $mail->Port       = 587;

            $mail->setFrom('busina@example.com', 'BUsina');
            $mail->addAddress($validatedData['email'], $validatedData['name']); 


            $mail->isHTML(true); 
            $mail->Subject = 'Registration Successful';
            $mail->Body    = "
            <html>
            <head>
                <link rel='stylesheet' href='https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css'>
            </head>
            <body style='font-family: Arial, sans-serif; background-color: #f4f4f4; margin: 0; padding: 0; display: flex; justify-content: center; align-items: center; font-weight: 500;'>
                <div style='background-color: white; border-radius: 10px; width: 100%; max-width: 600px; margin: 20px auto; text-align: left;'>
                    <div style='background-color: #161a39; align-items: center; text-align: center; padding: 20px;'>
                        <h3 style='color: white; font-size: 20px;'>Registration Successful</h3>
                    </div>
                    <div style='padding: 40px;'>
                        <p style='margin: 10px 0; color: #666666; font-size: 14px;'>Dear <span style='font-weight: 600;'>{$validatedData['name']}</span>,</p>
                        <p style='margin: 10px 0; color: #666666; font-size: 14px;'>Your registration was successful.</p>
                        <p style='margin: 10px 0; color: #666666; font-size: 14px;'>Your password is: <strong>BUsina2024</strong></p>
                        <p style='margin: 10px 0; color: #666666; font-size: 14px;'>Please log in to your account and change your password as soon as possible to prevent unauthorized access.</p>
                        <p style='margin: 10px 0; color: #666666; font-size: 14px;'>Thank you!</p>
                    </div>
                    <div style='background-color: #161a39; padding: 20px 20px 5px 20px;'>
                        <div style='color: #f4f4f4; font-size: 12px;'>
                            <p><span style='font-size: 14px; font-weight: 600;'>Contact</span></p>
                            <p>busina@gmail.com</p>
                            <p>Legazpi City, Albay, Philippines 13°08′39″N 123°43′26″E</p>
                        </div>
                        <div style='text-align: center;'>
                            <p style='color: #f4f4f4; font-size: 14px;'>Company © All Rights Reserved</p>
                        </div>
                    </div>
                </div>
            </body>
            </html>
            ";

            $mail->send();
    
            Auth::login($user);

            session(['registration_completed' => true]);

            return redirect()->route('application-confirmation');

        } catch (\Exception $e) {
            Log::error('Registration failed: ' . $e->getMessage());
            return back()->withErrors(['error' => 'Failed to register. Please try again.']);
        }
    }

    public function editVehicle($id)
{
    // Retrieve the vehicle details using the ID
    $vehicle = Vehicle::with('vehicleOwner')->findOrFail($id);
    
    // Get the logged-in user's data to pre-fill non-editable fields
    $user = Auth::user();

    $vehicleTypes = VehicleType::all();

    // Pass data to the view
    return view('vehicle.edit', compact('vehicle', 'user', 'vehicleTypes'));
}

public function updateVehicle(Request $request, $id)
{
    // Validate only the editable fields
    $validatedData = $request->validate([
        'vehicle_type' => 'required|string|in:2-wheel,4-wheel',
        'driver_license' => 'required|string|max:20|unique:vehicle_owner,driver_license_no,' . $id, // Ignore the current vehicle
        'vehicle_model' => 'required|string|max:255',
        'plate_number' => 'required|string|max:10|unique:vehicle,plate_no,' . $id,
        'or_number' => 'required|string|max:18|unique:vehicle,or_no,' . $id,
        'cr_number' => 'required|string|max:9|unique:vehicle,cr_no,' . $id,
        'scanned_or_cr' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:2048',
        'scanned_license' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:2048',
        'scanned_id' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:2048',
        'certificate' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:2048',
        'expiration' => 'required|date',
        'total_due' => 'required|numeric',
    ]);

    // Find the vehicle and update its data
    $vehicle = Vehicle::findOrFail($id);

    $vehicleOwner = $vehicle->vehicleOwner;

    // Store any uploaded files
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

    // Update vehicle fields
    $vehicle->update([
        'model_color' => $validatedData['vehicle_model'],
        'plate_no' => $validatedData['plate_number'],
        'or_no' => $validatedData['or_number'],
        'cr_no' => $validatedData['cr_number'],
        'expiry_date' => $validatedData['expiration'],
        'vehicle_type_id' => VehicleType::where('vehicle_type', $validatedData['vehicle_type'])->first()->id,
    ]);

    // Update the transaction if necessary
    $transaction = $vehicle->transaction;
    $transaction->update([
        'amount_payable' => $validatedData['total_due'],
        'vehicle_status' => $this->getVehicleStatus($validatedData['expiration']),
    ]);

    return redirect()->route('vehicles.list')->with('success', 'Vehicle details updated successfully!');
}
public function addVehicle(Request $request)
{
    // Validate incoming request data
    $validatedData = $request->validate([
        'vehicle_type' => 'required|string|in:2-wheel,4-wheel',
        'driver_license' => 'required|string|max:20|unique:vehicle_owner,driver_license_no',
        'vehicle_model' => 'required|string|max:255',
        'plate_number' => 'required|string|max:10|unique:vehicle,plate_no',
        'or_number' => 'required|string|max:18|unique:vehicle,or_no',
        'cr_number' => 'required|string|max:9|unique:vehicle,cr_no',
        'scanned_or_cr' => 'required|file|mimes:pdf,jpg,jpeg,png|max:2048',
        'scanned_license' => 'required|file|mimes:pdf,jpg,jpeg,png|max:2048',
        'scanned_id' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:2048',
        'certificate' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:2048',
        'expiration' => 'required|date',
        'total_due' => 'required|numeric',
    ]);

    try {
        $scannedOrCrPath = $request->file('scanned_or_cr')->store('public/files');
        $scannedLicensePath = $request->file('scanned_license')->store('public/files');
        $scannedIdPath = $request->file('scanned_id') ? $request->file('scanned_id')->store('public/files') : null;
        $certificatePath = $request->file('certificate') ? $request->file('certificate')->store('public/files') : null;

        $vehicleOwner = VehicleOwner::create([
            'fname' => $request->input('fname'),
            'mname' => $request->input('mname'),
            'lname' => $request->input('lname'),
            'contact_no' => $request->input('contact_no'),
            'id_no' => $validatedData['id_no'] ?? null,
            'applicant_type_id' => $request->input('applicant_type_id'),
            'driver_license_no' => $request->input('driver_license'),
            'qr_code' => null,
        ]);

        $vehicle = Vehicle::create([
            'vehicle_owner_id' => $vehicleOwner->id,
            'model_color' => $request->input('vehicle_model'),
            'plate_no' => $request->input('plate_number'),
            'or_no' => $request->input('or_number'),
            'cr_no' => $request->input('cr_number'),
            'expiry_date' => $request->input('expiration'),
            'copy_or_cr' => Hash::make(Storage::get($scannedOrCrPath)),
            'copy_driver_license' => Hash::make(Storage::get($scannedLicensePath)),
            'copy_cor' => $scannedIdPath ? Hash::make(Storage::get($scannedIdPath)) : null,
            'copy_school_id' => $certificatePath ? Hash::make(Storage::get($certificatePath)) : null,
            'vehicle_type_id' => VehicleType::where('vehicle_type', $request->input('vehicle_type'))->first()->id,
        ]);

        $transaction = Transaction::create([
            'vehicle_id' => $vehicle->id,
            'transac_type' => 'first_registration',
            'amount_payable' => $request->input('total_due'),
            'reference_no' => '',
            'claiming_status_id' => 1,
            'vehicle_status' => $this->getVehicleStatus($request->input('expiration')),
            'apply_date' => Carbon::now()->toDateString(),
            'issued_date' => null,
            'sticker_expiry' => Carbon::now()->addYear()->toDateString(),
        ]);

        $registrationNo = '2024-' . str_pad($transaction->id, 5, '0', STR_PAD_LEFT);
        $transaction->update(['registration_no' => $registrationNo]);

        $transaction->update(['reference_no' => 'REF' . str_pad($transaction->id, 8, '0', STR_PAD_LEFT)]);

      
        return redirect()->route('vehicles.list')->with('success', 'Vehicle added successfully!');
    } catch (\Exception $e) {
        Log::error('Failed to add vehicle: ' . $e->getMessage());
        return back()->withErrors(['error' => 'Failed to add vehicle. Please try again.']);
    }
}

}
