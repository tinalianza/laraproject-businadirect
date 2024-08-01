<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\PasswordResetController as BasePasswordResetController;
use Illuminate\Http\Request;

class PasswordResetController extends BasePasswordResetController
{
    public function showResetForm(Request $request, $token = null)
    {
        return view('auth.passwords.reset')->with([
            'token' => $token,
            'email' => $request->email
        ]);
    }
}
