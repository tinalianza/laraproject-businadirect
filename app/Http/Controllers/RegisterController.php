<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class RegisterController extends Controller
{
    public function register(Request $request)
    {
        $request->validate([
            'email' => 'required|email|unique:users',
        ]);

        $user = User::create([
            'email' => $request->email,
            'password' => Hash::make('BUsina2024'), // Set default password
        ]);

        // Additional logic to handle storing other user details if needed

        return redirect()->route('.auth'); // Redirect to home page after registration
    }
}
