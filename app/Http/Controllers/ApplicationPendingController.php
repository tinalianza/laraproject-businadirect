<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ApplicationPendingController extends Controller
{
    public function doneApplication(Request $request)
    {
    
        return view('auth.application-pending');
    }
}
