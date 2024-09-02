<?php
namespace App\Http\Controllers;

use App\Models\Transaction;
use App\Models\VehicleOwner; 
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;

class VehicleController extends Controller 
{
    public function index(Request $request)
    {
        $perPage = 10;

        $data = Transaction::with(['claimingStatus', 'vehicle'])->paginate($perPage);

        return view('vehiclelist', ['data' => $data]);
    }

    // public function showDetails($id)
    // {
    //     // Load vehicleOwner with related vehicle, applicantType, and transactions
    //     $item = VehicleOwner::with([
    //         'applicantType',  // Adjusted naming to match conventions
    //         'vehicle.vehicleType', // Adjusted naming to match conventions
    //         'vehicle.transaction.claimingStatus'  // Adjusted naming to match conventions
    //     ])->findOrFail($id);

    //     return view('reg_details', compact('item'));
    // }
    public function showVehicles()
        {
            $user = auth()->user();

            $data = Vehicle::where('vehicle_owner_id', $user->id)->paginate(10);

            return view('vehiclelist', compact('data'));
        }

}

