<?php 

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use App\Models\VehicleOwner;

class ProfileController extends Controller
{
    // Display the edit profile form.
    public function showEditForm()
    {
        $user = Auth::user(); // Get the authenticated user

        // Fetch the vehicle owner related to the authenticated user
        $vehicle_owner = VehicleOwner::where('id', $user->vehicle_owner_id)->first();

        // Check if the vehicle owner record exists
        if (!$vehicle_owner) {
            return redirect()->route('dashboard')->with('error', 'Vehicle owner record not found.');
        }

        // Pass user and vehicle owner data to the view
        return view('editprofile', compact('user', 'vehicle_owner'));
    }

    // Handle the profile update request.
    public function updateProfile(Request $request)
    {
        // Validate the form input
        $validatedData = $request->validate([
            'contact_no' => [
                'required', 'regex:/^9\d{9}$/', // Match contact number format
                Rule::unique('vehicle_owner')->ignore(Auth::user()->vehicle_owner_id),
            ],
            'email' => [
                'required', 'email', 'max:255',
                Rule::unique('users')->ignore(Auth::id()),
            ],
            'password' => 'nullable|min:8|confirmed|regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&#.])[A-Za-z\d@$!%*?&#.]{8,}$/',

        ]);

        $user = Auth::user(); // Get the authenticated user

        // Prepare data for updating user
        $userData = [];
        
        // Update email if it's different from the current email
        if ($validatedData['email'] !== $user->email) {
            $userData['email'] = $validatedData['email'];
        }

        // Update password if a new one is provided
        if (!empty($validatedData['password'])) {
            $userData['password'] = Hash::make($validatedData['password']);
        }

        // Update user data if there's any change
        if (!empty($userData)) {
            $user->update($userData);
        }

        // Update vehicle owner data
        $vehicle_owner = VehicleOwner::find($user->vehicle_owner_id);
        if ($vehicle_owner) {
            // Update contact number directly
            $vehicle_owner->update([
                'contact_no' => $validatedData['contact_no'],
            ]);
        }

        return redirect()->route('dashboard')->with('success', 'Profile updated successfully!');
    }
}
