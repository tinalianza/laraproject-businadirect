@extends('layouts.app')  

@section('title', 'Forgot Password - BUsina Online')

@section('content')
<head>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    @vite(['resources/css/email_style.css'])
</head>
<body>
    <div class='email-container'>
        <div class="headmail">
            <img src="{{ Vite::asset('resources/images/lockreload.png') }}" alt="">
            <h3>Please reset your password</h3>
        </div>
        <div class="semi-mail">
            <p>We have received a request to reset your password. If you did not initiate this request, please disregard this email.</p>
            <p>Your reset code is:</p>
            <div class='code-container'>
                <h2>{{ $resetCode }}</h2> <!-- Display the reset code from the controller -->
            </div>
            <p>Please use this code to reset your password. The code will expire in 10 minutes.</p>
            <p>If you have any questions or need further assistance, please don't hesitate to contact us at <a href='mailto:busina@gmail.com'>busina@gmail.com</a>.</p>
            <p>Best regards,<br><span>Bicol University BUsina</span></p>
        </div>
        <div class="mail-footer">
            <div class="mail-footer_up">
                <p><span>Contact</span></p>
                <p>busina@gmail.com</p>
                <p>Legazpi City, Albay, Philippines 13°08′39″N 123°43′26″E</p>
            </div>
            <div class="mail-footer_down">
                <p>Company © All Rights Reserved</p>
            </div>
        </div>
    </div>
</body>
@endsection
