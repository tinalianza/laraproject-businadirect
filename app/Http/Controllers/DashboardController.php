<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Vehicle;
use App\Models\VehicleOwner; 

class DashboardController extends Controller
{
    public function index()
    {
        $vehicleOwner = VehicleOwner::where('id', auth()->id())->first();

        return view('dashboard', compact('vehicleOwner'));
    }
}
