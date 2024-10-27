@extends('layouts.app')
@section('title', 'BUsina Online')

@section('content')
<!DOCTYPE html>
<html lang="en">
<head>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body, html {
            margin: 0;
            font-family: 'Poppins', sans-serif;
            background-color: #f5f5f9;
            height: auto;
        }

        .top {
            position: relative;
            overflow: hidden;
        }

        .top img {
            width: 100%;
            height: auto;
            max-height: 450px;
            object-fit: cover;
        }

        .overlay-text {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            text-align: center;
            color: white;
        }

        .overlay-text h1 {
            font-size: 2.5rem;
            margin: 0;
            text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.7);
        }

        .learn-more {
            display: inline-block;
            margin-top: 20px;
            padding: 10px 20px;
            background: #FF8C00; 
            color: white;
            text-decoration: none;
            border-radius: 10px;
            transition: background 0.3s;
        }

        .learn-more:hover {
            background: #F39C12; 
        }

        .slider-container { 
            background: linear-gradient(to right, rgba(255, 111, 0, 0.5), rgba(255, 179, 0, 0.5), rgba(0, 114, 184, 0.5));
            box-shadow: 0px 2px 5px rgba(0, 0, 0, 0.2); 
            padding: 25px 0; 
        }

        .steps {
            display: flex;
            justify-content: space-around;
            flex-wrap: wrap;
            padding: 25px;
            color: white;
        }

        .step {
            text-align: center;
            flex: 1 1 150px; 
            margin: 5px;
        }

        .step img {
            width: 70px;
            height: auto;
        }

        .card-container {
            padding: 20px;
            display: flex;
            justify-content: center;
            flex-wrap: wrap;
            gap: 20px; 
        }

        .card {
            width: 18rem;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
            border: none; 
            border-radius: 10px; 
            overflow: hidden; 
            transition: transform 0.3s;
        }

        .card:hover {
            transform: translateY(-5px);
        }

        .card img {
            width: 100%;
            height: 150px;
            object-fit: cover;
        }

        .card-body {
            background-color: #fff;
            padding: 15px; 
        }

        .card-title {
            color: #005B96; 
        }

        marquee {
            margin-top: 10px;
            font-family: 'Poppins', sans-serif;
            color: #ffffff;
            font-weight: bold;
            font-size: 1.5rem;
            background-color: #005B96;
            padding: 10px 0;
            white-space: nowrap;
            text-shadow: 1px 1px 2px rgba(0, 0, 0, 0.3);
        }

        @media (max-width: 768px) {
            .overlay-text h1 {
                font-size: 1.8rem;
            }

            .steps {
                flex-direction: column;
                align-items: center;
            }

            .step img {
                width: 50px;
            }

            .card {
                width: 100%; 
                max-width: 300px; 
            }
        }
    </style>
</head>

<body>
    <div class="top">
        <img src="{{ asset('images/bu-orange.png') }}" alt="Bicol University">
        <div class="overlay-text">
            <h1>Welcome, Beepers!</h1>
            <a href="https://bicol-u.edu.ph/" class="learn-more">Learn More</a>
        </div>
    </div>

    <div class="slider-container">
        <div class="steps">
            <div class="step">
                <img src="{{ asset('images/step1.png') }}" alt="Step 1">
                <p>Step 1</p>
                <p>Register/Login</p>
            </div>
            <div class="step">
                <img src="{{ asset('images/step2.png') }}" alt="Step 2">
                <p>Step 2</p>
                <p>Fill out the Application Form</p>
            </div>
            <div class="step">
                <img src="{{ asset('images/step3.png') }}" alt="Step 3">
                <p>Step 3</p>
                <p>Upload Documents</p>
            </div>
            <div class="step">
                <img src="{{ asset('images/step4.png') }}" alt="Step 4">
                <p>Step 4</p>
                <p>Make Payment</p>
            </div>
            <div class="step">
                <img src="{{ asset('images/step5.png') }}" alt="Step 5">
                <p>Step 5</p>
                <p>Receive Confirmation</p>
            </div>
        </div>
    </div>

    <marquee behavior="scroll" direction="left" scrollamount="20">
        GET YOUR BUSINA CARD NOW! GET YOUR BUSINA CARD NOW! GET YOUR BUSINA CARD NOW!
    </marquee>

    <div class="card-container">
        <div class="card">
            <img src="{{ asset('images/busina card guest.png') }}" alt="Guest Card">
            <div class="card-body">
                <h5 class="card-title">Guest Card</h5>
                <p class="card-text">Special access for guests.</p>
            </div>
        </div>
        <div class="card">
            <img src="{{ asset('images/busina card staff.png') }}" alt="Staff Card">
            <div class="card-body">
                <h5 class="card-title">Staff Card</h5>
                <p class="card-text">Access for staff members.</p>
            </div>
        </div>
        <div class="card">
            <img src="{{ asset('images/busina card student.png') }}" alt="Student Card">
            <div class="card-body">
                <h5 class="card-title">Student Card</h5>
                <p class="card-text">For registered students.</p>
            </div>
        </div>
        <div class="card">
            <img src="{{ asset('images/busina card vip.png') }}" alt="VIP Card">
            <div class="card-body">
                <h5 class="card-title">VIP Card</h5>
                <p class="card-text">Exclusive access for VIPs.</p>
            </div>
        </div>
    </div>


{{-- 
    @include('layouts.footer') --}}
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
@endsection
