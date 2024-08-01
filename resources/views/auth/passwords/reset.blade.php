@extends('layouts.app')

@section('title', 'Reset Password - BUsina Online')

@section('content')
    <style>
        body, html {
            margin: 0;
            padding: 0;
            width: 100%;
            height: 100%;
            font-family: 'Poppins', sans-serif;
            display: flex;
            justify-content: center;
            align-items: center;
            background-color: #f8f9fa; /* Light background color */
        }
        .reset-password-container {
            width: 480px;
            height: 320px;
            padding: 20px;
            background-image: url('{{ asset('images/loginbg.png') }}'); /* Background image */
            background-size: cover; /* Cover the entire container */
            background-position: center; /* Center the background image */
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            border-radius: 8px;
            text-align: center;
        }
        .reset-password-container h1 {
            font-size: 24px;
            color: black;
        }
        .reset-password-container h1 .bee {
            color: #0061A6;
        }
        .reset-password-container h1 .per {
            color: #F2752B;
        }
        .reset-password-container input[type="password"] {
            width: 70%;
            padding: 10px;
            margin: 3px;
            border: 1px solid #ccc;
            border-radius: 10px;
            box-sizing: border-box; /* Ensure padding is included in width */
        }
        .reset-password-container button {
            width: 40%;
            padding: 10px;
            background-color: #0061A6;
            color: white;
            border: none;
            border-radius: 10px;
            cursor: pointer;
        }
        .reset-password-container button:hover {
            background-color: #0056b3;
        }
        .reset-password-container a {
            display: block;
            margin-top: 10px;
            color: #007bff;
            text-decoration: none;
        }
        .reset-password-container a:hover {
            text-decoration: underline;
        }
        .text {
            font-size: 11px;
            color: #0056b3;
        }
    </style>

    <div class="reset-password-container">
        <h1>Reset Your Password</h1>
        
        <div class="text">
            {{ __('Enter your current password and new password below.') }}
        </div>

        @if (session('status'))
            <div class="mb-4 font-medium text-sm text-green-600 dark:text-green-400">
                {{ session('status') }}
            </div>
        @endif

        <x-validation-errors class="mb-4" />

        <form method="POST" action="{{ route('password.update') }}">
            @csrf

            <input type="hidden" name="token" value="{{ $token }}">

            <div>
                <x-input id="current_password" class="block mt-1 w-full" type="password" name="current_password" required autofocus placeholder="Current Password" />
            </div>

            <div>
                <x-input id="password" class="block mt-1 w-full" type="password" name="password" required placeholder="New Password" />
            </div>

            <div>
                <x-input id="password_confirmation" class="block mt-1 w-full" type="password" name="password_confirmation" required placeholder="Confirm New Password" />
            </div>

            <div class="flex items-center justify-end mt-4">
                <x-button>
                    {{ __('Reset Password') }}
                </x-button>
            </div>
        </form>
    </div>

    @extends('layouts.footer')
@endsection
