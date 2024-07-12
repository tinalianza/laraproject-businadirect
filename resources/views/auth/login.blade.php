@extends('layouts.app')

@section('title', 'Login - BUsina Online')

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
        .login-container {
            width: 400px;
            padding: 20px;
            background-image: url('{{ asset('images/loginbg.png') }}'); /* Background image */
            background-size: cover; /* Cover the entire container */
            background-position: center; /* Center the background image */
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            border-radius: 8px;
            text-align: center;
        }
        .login-container h1 {
            font-size: 24px;
            color: black;
        }
        .login-container h1 .bee {
            color: #0061A6;
        }
        .login-container h1 .per {
            color: #F2752B;
        }
        .login-container input[type="email"],
        .login-container input[type="password"] {
            width: 90%;
            padding: 10px;
            margin: 3px;
            border: 1px solid #ccc;
            border-radius: 10px;
            box-sizing: border-box; /* Ensure padding is included in width */
            left:3%;
        }
        .login-container button {
            width: 40%;
            padding: 10px;
            background-color: #0061A6;
            color: white;
            border: none;
            border-radius: 10px;
            cursor: pointer;
        }
        .login-container button:hover {
            background-color: #0056b3;
        }
        .login-container a {
            display: block;
            margin-top: 10px;
            color: #007bff;
            text-decoration: none;
        }
        .login-container a:hover {
            text-decoration: underline;
        }
        .remember-me{
            left:10%;
        }

    </style>

    <div class="login-container">
        <h1>My <span class="bee">Bee</span><span class="per">per</span> Account</h1>
        
        <x-validation-errors class="mb-4" />
        
        @if (session('status'))
            <div class="mb-4 font-medium text-sm text-green-600">
                {{ session('status') }}
            </div>
        @endif

        <form method="POST" action="{{ route('login') }}">
            @csrf

            <div>
                <x-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')" required autofocus autocomplete="username" placeholder="Enter Email Address" required/>
            </div>

            <div class="mt-4">
                <x-input id="password" class="block mt-1 w-full" type="password" name="password" required autocomplete="current-password" placeholder="Enter Password" required />
            </div>

            <div class="remember-me">
                <label for="remember_me" class="flex items-center">
                    <x-checkbox id="remember_me" name="remember" />
                    <span class="ml-2 text-sm text-gray-600">{{ __('Remember me') }}</span>
                </label>
            </div>

            <div class="flex items-center justify-end mt-4">
                @if (Route::has('password.request'))
                    <a class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500" href="{{ route('password.request') }}">
                        {{ __('Forgot your password?') }}
                    </a>
                @endif

                <button class="ml-4 btn btn-primary">
                    {{ __('Log in') }}
                </button>
            </div>
        </form>
        <a href="{{ route('register') }}">Apply Now</a>
    </div>
@endsection
