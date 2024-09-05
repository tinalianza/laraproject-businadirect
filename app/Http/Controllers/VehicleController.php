<?php
namespace App\Http\Controllers;

use App\Models\Vehicle;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class VehicleController extends Controller 
{
    public function index()
    {
        // Fetch the logged-in user's vehicle owner record
        $vehicleOwner = Auth::user()->vehicleOwner;

        // Retrieve the vehicles owned by this vehicle owner
        $vehicles = $vehicleOwner ? $vehicleOwner->vehicles : [];

        return view('vehiclelist', ['vehicles' => $vehicles]);

    }

}
