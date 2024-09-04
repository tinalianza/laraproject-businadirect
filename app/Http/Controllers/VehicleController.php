<?php
namespace App\Http\Controllers;

use App\Models\Vehicle;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class VehicleController extends Controller 
{
    public function index(Request $request)
    {
        $user = Auth::user();

        $data = $user->vehicles()->with('transactions.claimingStatus')->paginate(10);

        return view('vehiclelist', compact('data'));
    }
}
