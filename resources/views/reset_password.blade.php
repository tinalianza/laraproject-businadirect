@extends('layouts.app')

@section('content')

<head>
    <meta charset="utf-8">
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    <title>Busina Security - Login Page</title>
    <link rel="shortcut icon" href="{{ asset('favicon.png') }}" type="image/x-icon">
    <!-- Meta tags and CSS links -->
    <!-- Google Fonts -->
    <link href="https://fonts.gstatic.com" rel="preconnect">
    <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,300i,400,400i,600,600i,700,700i|Nunito:300,300i,400,400i,600,600i,700,700i|Poppins:300,300i,400,400i,500,500i,600,600i,700,700i" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
    @vite(['resources/css/login.css'])
</head>
<style>
.cover {
    background-image: url('/images/torch.png');
    width: 50%;
    border-radius: 15px 0 0 15px;
    padding: 0 20px;
    background-size: cover; 
    background-position: center; 
}
</style>
<body>
    <div class="semi-body">
     <div class="container">
        <div class="cover">
            <div class="login-name">
                <h3>Welcome back, <span>Beeper!</span> </h3>
                <div class="login-tagline">Drive safely, stay updated, and keep your wheels turning smoothly.</div>
            </div>   
        </div>

        <div class="forms">
            <div class="login-title">
                <h2>PASSWORD RESET</h2>
            </div>
        @if (session('error'))
            <div class="alert alert-danger">{{ session('error') }}</div>
        @endif
        <form method="POST" action="{{ route('password.verify') }}">
            @csrf
            <div class="form-group">
                <label for="email">Email Address</label>
                <input type="email" name="email" class="form-control" required 
                       pattern="[a-z0-9._%+-]+@[a-z0-9.-]+\.[a-z]{2,}$" 
                       title="Please enter a valid email address.">
            </div>
            
            <div class="form-group">
                <label for="reset_token">Reset Code</label>
                <input type="text" name="reset_token" class="form-control" required 
                       pattern="^\d{6}$" 
                       title="Invalid Code.">
            </div>
            
            <div class="form-group">
                <label for="password">New Password (leave blank to keep current)</label>
                <div style="position: relative;">
                    <input type="password" id="password" name="password" 
                        pattern="^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&#.])[A-Za-z\d@$!%*?&#.]{8,}$" 
                        title="Password must be at least 8 characters long, contain at least one uppercase letter, one lowercase letter, one number, and one special character."
                        class="form-control">
                    <span class="toggle-password" onclick="togglePasswordVisibility('password')" 
                        style="position: absolute; right: 10px; top: 50%; transform: translateY(-50%); cursor: pointer;">
                        <i class="fa fa-eye-slash eye-icon" aria-hidden="true" onclick="togglePassword()"></i>
                    </span>
                </div>
            </div>
       
            <div class="form-group">
                <label for="password_confirmation">Confirm Password</label>
                <div style="position: relative;">
                    <input type="password" id="password_confirmation" name="password_confirmation" 
                        pattern="^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&#.])[A-Za-z\d@$!%*?&#.]{8,}$" 
                        title="Password must be at least 8 characters long, contain at least one uppercase letter, one lowercase letter, one number, and one special character."
                        class="form-control">
                    <span class="toggle-password" onclick="togglePasswordVisibility('password_confirmation')" 
                        style="position: absolute; right: 10px; top: 50%; transform: translateY(-50%); cursor: pointer;">
                        <i class="fa fa-eye-slash eye-icon" aria-hidden="true" onclick="togglePassword()"></i>
                    </span>
                </div>
                <div id="password-error" class="error" style="display: none;">Passwords do not match.</div>
            </div>
            <button type="submit" class="btn btn-primary">Reset Password</button>
        </form>
        <div class="back-login">
            <a href="{{ route('login') }}"><i class="bi bi-chevron-left"></i> Back to login</a>
        </div>
    </div>  
</div>
    </div>

    <script src="{{ Vite::asset('resources/js/disableForm.js') }}"></script>
    <script src="{{ Vite::asset('resources/js/login_toggle_password.js') }}"></script>
    <script src="{{ Vite::asset('resources/js/hide_error_message.js') }}"></script>
    <script>
      
        const passwordField = document.getElementById('password');
        const confirmPasswordField = document.getElementById('password_confirmation');
        const passwordError = document.getElementById('password-error');

        confirmPasswordField.addEventListener('input', function() {
            if (passwordField.value !== confirmPasswordField.value) {
                passwordError.style.display = 'block';
            } else {
                passwordError.style.display = 'none';
            }
        });
    </script>

    @endsection
