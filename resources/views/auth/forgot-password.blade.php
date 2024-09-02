@extends('layouts.app')

@section('title', 'Forgot Password - BUsina Online')

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
            background-color: #f8f9fa; 
        }
        .forgot-password-container {
            width: 480px;
            height: 290px;
            padding: 20px;
            background-image: url('{{ asset('images/loginbg.png') }}'); 
            background-size: cover; 
            background-position: center; 
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            border-radius: 8px;
            text-align: center;
        }
        .forgot-password-container h1 {
            font-size: 24px;
            color: black;
        }
        .forgot-password-container h1 .bee {
            color: #0061A6;
        }
        .forgot-password-container h1 .per {
            color: #F2752B;
        }
        .forgot-password-container input[type="email"] {
            width: 70%;
            padding: 10px;
            margin: 3px;
            border: 1px solid #ccc;
            border-radius: 10px;
            box-sizing: border-box; 

        }
        .forgot-password-container button {
            width: 40%;
            padding: 10px;
            background-color: #0061A6;
            color: white;
            border: none;
            border-radius: 10px;
            cursor: pointer;
        }
        .forgot-password-container button:hover {
            background-color: #0056b3;
        }
        .forgot-password-container a {
            display: block;
            margin-top: 10px;
            color: #007bff;
            text-decoration: none;
        }
        .forgot-password-container a:hover {
            text-decoration: underline;
        }

        .text{
            font-size: 11px;
            color:#0056b3;
            
        }
    </style>

    <div class="forgot-password-container">
        <h1>My <span class="bee">Bee</span><span class="per">per</span> Account</h1>
        
         <div class="text">  {{-- mb-4 text-sm text-gray-600 dark:text-gray-400 --}}
            {{ __('Forgot your password? No problem. Just let us know your email address and we will email you a password reset link that will allow you to choose a new one.') }}
        </div>

        @if (session('status'))
            <div class="mb-4 font-medium text-sm text-green-600 dark:text-green-400">
                {{ session('status') }}
            </div>
        @endif

        <x-validation-errors class="mb-4" />

        <form method="POST" action="{{ route('password.email') }}">
            @csrf

            <div>
                <x-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')" required autofocus autocomplete="username" />
            </div>

            <div class="flex items-center justify-end mt-4">
                <x-button>
                    {{ __('Email Password Reset Link') }}
                </x-button>
            </div>
        </form>
    </div>

    @extends('layouts.footer')
@endsection
