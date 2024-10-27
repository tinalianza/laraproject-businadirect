<?php 

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

class ForgotPasswordController extends Controller
{

    public function showForm()
    {
        return view('forgot_pass');
    }

    public function sendResetCode(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'email' => 'required|email|exists:users,email',
        ]);
    
        if ($validator->fails()) {
            return redirect()->route('password.request')
                ->withErrors($validator)
                ->withInput();
        }
    

        $user = DB::table('users')->where('email', $request->email)->first();
    

        if (!$user) {
            return redirect()->route('password.request')
                ->with('error', 'Email address not found.')  
                ->withInput();
        }
    
      
        $resetToken = rand(100000, 999999);
    
        
        DB::table('password_resets')->updateOrInsert(
            ['users_id' => $user->id],  
            [
                'reset_token' => $resetToken,  
                'expiration' => now()->addMinutes(10),
                'used_reset_token' => 0,
            ]
        );
    

        $mail = new PHPMailer(true);
        try {
            $mail->isSMTP();
            $mail->Host = 'smtp.gmail.com';
            $mail->SMTPAuth = true;
            $mail->Username = env('MAIL_USERNAME'); 
            $mail->Password = env('MAIL_PASSWORD'); 
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
            $mail->Port = 587;
    
            $mail->setFrom('ronabalangat2003@gmail.com', 'YourApp');
            $mail->addAddress($user->email);
    
            $mail->isHTML(true);
            $mail->Subject = 'Password Reset Token';
            $mail->Body = "
            <html>
            <body>
                <h3>Password Reset Request</h3>
                <p>Hello,</p>
                <p>You requested to reset your password. Use the following code to reset your password:</p>
                <h2>{$resetToken}</h2>
                <p>If you did not request this, please ignore this email.</p>
            </body>
            </html>";
    
            $mail->send();
        } catch (Exception $e) {
            \Log::error("Failed to send reset token: {$mail->ErrorInfo}");
            return redirect()->route('password.request')
                ->with('error', 'Failed to send reset token. Please try again later.');
        }
    
        return redirect()->route('password.reset.form')->with('success', 'Reset token sent to your email.');
    }
    

    public function verifyResetCode(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email|exists:users,email',
            'reset_token' => 'required|numeric', 
            'password' => 'required|min:6|confirmed',
        ]);
    
        if ($validator->fails()) {
            return redirect()->route('password.reset.form')
                ->withErrors($validator)
                ->withInput();
        }
    

        $resetRecord = DB::table('password_resets')
            ->where('reset_token', $request->reset_token) 
            ->where('expiration', '>', now())
            ->where('used_reset_token', 0)
            ->where('users_id', DB::table('users')->where('email', $request->email)->first()->id)
            ->first();
    
        if (!$resetRecord) {
            return redirect()->route('password.reset.form')
                ->with('error', 'Invalid or expired reset token.');
        }
    

        DB::table('users')->where('email', $request->email)
            ->update(['password' => Hash::make($request->password)]);
    

        DB::table('password_resets')->where('reset_token', $request->reset_token)
            ->update(['used_reset_token' => 1]);
    
        return redirect()->route('login')->with('success', 'Password reset successful.');
    }
    

    public function showResetForm()
    {
        return view('reset_password');
    }
}
