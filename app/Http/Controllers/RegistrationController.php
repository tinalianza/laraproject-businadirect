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
use Endroid\QrCode\ErrorCorrectionLevel;
use Endroid\QrCode\Encoding\Encoding;
use App\Models\VehicleOwner;
use App\Models\Vehicle;
use Carbon\Carbon;
use App\Models\Transaction;


class RegistrationController extends Controller
{
    public function showRegistrationForm()
    {
        $applicantTypes = ApplicantType::all();

        $vehicleTypes = VehicleType::all();
        return view('auth.register', compact('applicantTypes', 'vehicleTypes'));
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
            'employee_id' => 'nullable|string',
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
    
        $nameParts = explode(',', $validatedData['name']);
        if (count($nameParts) != 3) {
            return back()->withErrors(['name' => 'Name must be in the format "Lastname, First Name, Middle Name".']);
        }
    
        $lname = trim($nameParts[0]);
        $fname = trim($nameParts[1]);
        $mname = trim($nameParts[2]);
    
        $applicantType = ApplicantType::where('type', $validatedData['applicant_type'])->first();
        if (!$applicantType) {
            return back()->withErrors(['applicant_type' => 'Invalid applicant type.']);
        }
    
        $vehicleType = VehicleType::where('vehicle_type', $validatedData['vehicle_type'])->first();
        if (!$vehicleType) {
            return back()->withErrors(['vehicle_type' => 'Invalid vehicle type.']);
        }
    
        if ($applicantType->type != 'Non-Personnel' && isset($validatedData['employee_id'])) {
            $idNoExists = false;
    
            if ($applicantType->type == 'Student') {
                $idNoExists = Student::where('id_no', $validatedData['employee_id'])->exists();
                if (!$idNoExists) {
                    return back()->withErrors(['employee_id' => 'Student ID No does not exist, contact the registrar.']);
                }
            } elseif (in_array($applicantType->type, ['BU-personnel', 'VIP'])) {
                $idNoExists = Employee::where('id_no', $validatedData['employee_id'])->exists();
                if (!$idNoExists) {
                    $idNoExistsInStudent = Student::where('id_no', $validatedData['employee_id'])->exists();
                    if ($idNoExistsInStudent) {
                        return back()->withErrors(['employee_id' => 'An account with this ID No already exists in the students table.']);
                    }
                    return back()->withErrors(['employee_id' => 'Employee ID No does not exist, contact the registrar.']);
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
                'contact_no' => $validatedData['contact_no'],
                'applicant_type_id' => $applicantType->id,
                'emp_id' => $validatedData['employee_id'] ?? null,
                'driver_license_no' => $validatedData['driver_license'],
                'qr_code' => null,
            ]);
    
            $vehicle = Vehicle::create([
                'vehicle_owner_id' => $vehicleOwner->id,
                'model_color' => $validatedData['vehicle_model'],
                'plate_no' => $validatedData['plate_number'],
                'or_no' => $validatedData['or_number'],
                'cr_no' => $validatedData['cr_number'],
                'expiry_date' => $validatedData['expiration'],
                'copy_or_cr' => Hash::make(Storage::get($scannedOrCrPath)),
                'copy_driver_license' => Hash::make(Storage::get($scannedLicensePath)),
                'copy_cor' => $scannedIdPath ? Hash::make(Storage::get($scannedIdPath)) : null,
                'copy_school_id' => $certificatePath ? Hash::make(Storage::get($certificatePath)) : null,
                'vehicle_type_id' => $vehicleType->id,
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

            $qrData = 'Vehicle Model: ' . $validatedData['vehicle_model'] . "\nPlate Number: " . $validatedData['plate_number'];
            $qrCode = new QrCode($qrData);
            $writer = new PngWriter();
            $result = $writer->write($qrCode);

            $qrCodePath = 'public/qrcodes/' . $vehicleOwner->id . '.png';
            Storage::put($qrCodePath, $result->getString());
           
            $qrCodeUrl = Storage::url('qrcodes/' . $vehicleOwner->id . '.png');

            $vehicleOwner->update(['qr_code' => str_replace('public/', '', $qrCodePath)]);

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
}
