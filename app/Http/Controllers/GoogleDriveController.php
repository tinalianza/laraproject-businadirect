<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class GoogleDriveController extends Controller
{
    public function redirectToGoogle()
    {
        $clientId = '562893401828-imhgdv7k6lebvjh5cr8s8ucau6q60e5t.apps.googleusercontent.com';
        $redirectUri = 'http://localhost:8000/callback';
        $scope = 'https://www.googleapis.com/auth/drive.file';
        $state = 'your_state_value'; 

        $authUrl = 'https://accounts.google.com/o/oauth2/v2/auth?' . http_build_query([
            'scope' => $scope,
            'access_type' => 'offline',
            'include_granted_scopes' => 'true',
            'response_type' => 'code',
            'state' => $state,
            'redirect_uri' => $redirectUri,
            'client_id' => $clientId,
        ]);

        return redirect($authUrl);
    }

    public function handleGoogleCallback(Request $request)
    {
        $code = $request->input('code');
        $clientId = 'YOUR_CLIENT_ID';
        $clientSecret = 'YOUR_CLIENT_SECRET';
        $redirectUri = 'YOUR_REDIRECT_URI';
    
        $response = \Http::post('https://oauth2.googleapis.com/token', [
            'code' => $code,
            'client_id' => $clientId,
            'client_secret' => $clientSecret,
            'redirect_uri' => $redirectUri,
            'grant_type' => 'authorization_code',
        ]);
    
        $data = $response->json();
        $accessToken = $data['access_token'];

        session(['google_access_token' => $accessToken]);
    
        return redirect()->route('home'); 
    }
    
}
