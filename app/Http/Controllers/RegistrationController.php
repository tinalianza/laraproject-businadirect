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
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use Illuminate\Support\Facades\Storage;

class RegistrationController extends Controller
{
    public function showRegistrationForm()
    {
        $applicantTypes = ApplicantType::all();
        $vehicleTypes = VehicleType::all();
        return view('auth.register', compact('applicantTypes', 'vehicleTypes'));
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'name' => 'required|string|max:255|unique:registrations,name',
            'applicant_type' => 'required|string|in:BU-personnel,Non-Personnel,Student,VIP',
            'employee_id' => 'nullable|string',
            'email' => 'required|email|max:255|unique:users,email',
            'contact_no' => 'required|digits:10|unique:registrations,contact_no',
            'vehicle_type' => 'required|string|in:2-wheel,4-wheel',
            'driver_license' => 'required|string|max:20|unique:registrations,driver_license',
            'vehicle_model' => 'required|string|max:255',
            'plate_number' => 'required|string|max:10|unique:registrations,plate_number',
            'or_number' => 'required|string|max:18|unique:registrations,or_number',
            'cr_number' => 'required|string|max:9|unique:registrations,cr_number',
            'scanned_or_cr' => 'required|file|mimes:pdf,jpg,jpeg,png|max:2048',
            'scanned_license' => 'required|file|mimes:pdf,jpg,jpeg,png|max:2048',
            'scanned_id' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:2048',
            'certificate' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:2048',
            'expiration' => 'required|date',
            'total_due' => 'required|numeric',
        ]);

        $applicantType = ApplicantType::where('type', $validatedData['applicant_type'])->first();
        if (!$applicantType) {
            return back()->withErrors(['applicant_type' => 'Invalid applicant type.']);
        }

        $vehicleType = VehicleType::where('type', $validatedData['vehicle_type'])->first();
        if (!$vehicleType) {
            return back()->withErrors(['vehicle_type' => 'Invalid vehicle type.']);
        }

        // Only validate employee_id if applicant_type is not 'Non-Personnel'
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
                    // Check if the ID No already exists in the students table
                    $idNoExistsInStudent = Student::where('id_no', $validatedData['employee_id'])->exists();
                    if ($idNoExistsInStudent) {
                        return back()->withErrors(['employee_id' => 'An account with this ID No already exists in the students table.']);
                    }
                    return back()->withErrors(['employee_id' => 'Employee ID No does not exist, contact the registrar.']);
                }
            }
        }

        // Check if the name already exists
        if (Registration::where('name', $validatedData['name'])->exists()) {
            return back()->withErrors(['name' => 'A registration with this name already exists.']);
        }

        // Check if the ID No already exists
        if (isset($validatedData['employee_id']) && Registration::where('id_no', $validatedData['employee_id'])->exists()) {
            return back()->withErrors(['employee_id' => 'A registration with this ID No already exists.']);
        }

        // Check if the email already exists
    //    if (User::where('email', $validatedData['email'])->exists()) {
    //        return back()->withErrors(['email' => 'An account with this email already exists.']);
    //    }

        // Check if the driver license already exists
        if (Registration::where('driver_license', $validatedData['driver_license'])->exists()) {
            return back()->withErrors(['driver_license' => 'A registration with this driver license already exists.']);
        }

        // Check if the contact number already exists
        if (Registration::where('contact_no', $validatedData['contact_no'])->exists()) {
            return back()->withErrors(['contact_no' => 'A registration with this contact number already exists.']);
        }

        // Check if the OR number already exists
        if (Registration::where('or_number', $validatedData['or_number'])->exists()) {
            return back()->withErrors(['or_number' => 'A registration with this OR number already exists.']);
        }

        // Check if the CR number already exists
        if (Registration::where('cr_number', $validatedData['cr_number'])->exists()) {
            return back()->withErrors(['cr_number' => 'A registration with this CR number already exists.']);
        }

        try {
            // Store files
            $scannedOrCrPath = $request->file('scanned_or_cr')->store('public/files');
            $scannedLicensePath = $request->file('scanned_license')->store('public/files');
            $scannedIdPath = $request->file('scanned_id') ? $request->file('scanned_id')->store('public/files') : null;
            $certificatePath = $request->file('certificate') ? $request->file('certificate')->store('public/files') : null;

            // Create registration record
            $registration = Registration::create([
                'name' => $validatedData['name'],
                'applicant_type_id' => $applicantType->id,
                'id_no' => $validatedData['employee_id'] ?? null, // Handle nullable employee_id
                'email' => $validatedData['email'],
                'contact_no' => $validatedData['contact_no'],
                'vehicle_type_id' => $vehicleType->id,
                'driver_license' => $validatedData['driver_license'],
                'vehicle_model' => $validatedData['vehicle_model'],
                'plate_number' => $validatedData['plate_number'],
                'or_number' => $validatedData['or_number'],
                'cr_number' => $validatedData['cr_number'],
                'expiration' => $validatedData['expiration'],
                'total_due' => $validatedData['total_due'],
                'scanned_or_cr' => $scannedOrCrPath,
                'scanned_license' => $scannedLicensePath,
                'scanned_id' => $scannedIdPath,
                'certificate' => $certificatePath,
            ]);

            // Create user and assign to $user variable
            $user = User::create([
                'name' => $validatedData['name'],
                'email' => $validatedData['email'],
                'password' => Hash::make('BUsina2024'),
            ]);

            $qrData = 'Vehicle Model: ' . $validatedData['vehicle_model'] . "\n" .
            'Plate Number: ' . $validatedData['plate_number'];
            $qrCode = QrCode::format('png')->size(200)->generate($qrData);
            $qrCodePath = 'public/qrcodes/' . $registration->id . '.png';
            Storage::put($qrCodePath, $qrCode);

                    // Send email to the registered user
        $mail = new PHPMailer(true);

        // Server settings
        $mail->isSMTP();
        $mail->Host       = 'smtp.gmail.com';
        $mail->SMTPAuth   = true;
        $mail->Username   = 'ronabalangat2003@gmail.com'; // Your Gmail address
        $mail->Password   = 'dsae bzxj zikj tbxy';        // Your Gmail password
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port       = 587;

        // Recipients
        $mail->setFrom('busina@example.com', 'BUsina');
        $mail->addAddress($validatedData['email'], $validatedData['name']); // Add a recipient

        // Content
        $mail->isHTML(true); // Set to true if sending HTML email
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
        // End of sending email

            // Log in the user
            Auth::login($user);

            // Set session variable to indicate registration is completed
            session(['registration_completed' => true]);

            // Redirect to application confirmation page
            return redirect()->route('application-confirmation');

        } catch (\Exception $e) {
            Log::error('Registration failed: ' . $e->getMessage());
            return back()->withErrors(['error' => 'Failed to register. Please try again.']);
        }
    }
}
