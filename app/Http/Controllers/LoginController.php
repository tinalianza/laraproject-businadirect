<?php
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    public function authenticate(Request $request)
    {
        $credentials = $request->only('email', 'password');

        if (Auth::attempt($credentials)) {
            // Authentication passed...
            return redirect()->intended('home'); // Redirect to dashboard or home
        }

        return back()->withErrors(['email' => 'Invalid credentials']); // Handle authentication failure
    }
}
