<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;
use Carbon\Carbon;

class LoginController extends Controller
{
    public function showLoginForm()
    {

        if (Auth::check()) {
            return redirect()->route('dashboard');
        }
    
        $lockoutTime = session('lockout_time');
        $remainingSeconds = $lockoutTime && Carbon::now()->lessThan(Carbon::parse($lockoutTime)) 
            ? Carbon::now()->diffInSeconds(Carbon::parse($lockoutTime)) 
            : 0;
    
        return view('auth.login', ['remainingSeconds' => $remainingSeconds]);
    }
    
    public function login(Request $request)
    {

        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);


        $lockoutTime = session('lockout_time');
        if ($lockoutTime && Carbon::now()->lessThan(Carbon::parse($lockoutTime))) {
            $remainingSeconds = Carbon::now()->diffInSeconds(Carbon::parse($lockoutTime));
            throw ValidationException::withMessages([
                'email' => "Too many login attempts. Please try again in $remainingSeconds seconds."
            ]);
        }


        $credentials = $request->only('email', 'password');
        
        if (Auth::attempt($credentials)) {

            $user = Auth::user();
            $vehicleOwner = $user->vehicleOwner;

            if ($vehicleOwner) {

                $sessionData = [
                    'id' => $vehicleOwner->id,
                    'fname' => $vehicleOwner->fname,
                    'lname' => $vehicleOwner->lname,
                    'mname' => $vehicleOwner->mname ?? '',
                    'contact_no' => $vehicleOwner->contact_no ?? '',
                    'email' => $user->email,
                ];

                $request->session()->put('user', $sessionData);

                $request->session()->forget('login_attempts');
                $request->session()->forget('lockout_time');

                return redirect()->route('dashboard');
            }
        }

        $attempts = $request->session()->get('login_attempts', 0) + 1;
        $request->session()->put('login_attempts', $attempts);

        if ($attempts >= 10) {  
            $lockoutTime = Carbon::now()->addSeconds(30);
            $request->session()->put('lockout_time', $lockoutTime);
            throw ValidationException::withMessages([
                'email' => 'Too many login attempts. Please try again in 30 seconds.'
            ]);
        }

        throw ValidationException::withMessages([
            'email' => 'Invalid credentials.'
        ]);
    }

    public function logout(Request $request)
    {
        Auth::logout(); 

        $request->session()->invalidate(); 

        $request->session()->regenerateToken(); 

        return redirect('/'); 
    }
}
