@extends('layouts.app')
@extends('layouts.footer')
@section('title', 'BUsina Online')

@section('content')
<style>
    /* Include the CSS styles here or link to your external stylesheet */
    body, html {
        margin: 0;
        padding: 0;
        width: 100%;
        height: 100%;
        font-family: 'Poppins', sans-serif;
    }

    .top {
        display: flex;
        justify-content: center;
        position: relative;
    }

    .top img {
        width: 100%;
        height: 450px;
        object-fit: cover;
    }

    .overlay-text {
        position: absolute;
        top: 50%;
        left: 20%;
        transform: translate(-50%, -50%);
        text-align: left;
        color: white;
    }

    .overlay-text h1 {
        font-size: 30px;
        margin: 0;
    }

    .overlay-text .learn-more {
        display: inline-block;
        margin-top: 20px;
        padding: 10px 20px;
        background: #F07F29;
        color: white;
        text-decoration: none;
        border-radius: 10px;
    }

    .slider-container {
        padding: 20px;
        background: #f5f5f5;
    }

    .steps {
        display: flex;
        justify-content: space-around;
        border: hidden;
        padding: 25px;
        background: linear-gradient(to right, rgba(14, 84, 134, 0.7), rgba(240, 127, 41, 0.7));
        position: absolute;
        top: 400px;
        left: 13%;

    }

    .step {
        text-align: center;
        margin: 5px;
        display: flex;
        align-items: center;
    }

    .step img {
        width: 50px;
        height: auto;
    }

    .step p {
        color: white;
        margin: 2px 0;
    }

    .step div {
        display: flex;
        flex-direction: column;
        margin-left: 10px; /* Adjust this value as needed */
    }

    .card-container {
        background: #f5f5f5;
        padding: 20px;
    }

    .card-container img {
        margin: 0 10px;
    }

    marquee {
        margin-top: 10px;
    }

    marquee img {
        width: 300px;
        height: auto;

    }

    marquee font {
        font-family: 'Poppins', sans-serif;
        color: #f3971b;
        font-size: 20px;
    }
</style>

<div class="top">
    <img src="{{ asset('images/bu-bg.png') }}" alt="Bicol University">
    <div class="overlay-text">
        <h1>Welcome, Beepers!</h1>
        <a href="#" class="learn-more">Learn more</a>
    </div>
</div>

<div class="slider-container">
    <div class="steps">
        <div class="step">
            <img src="{{ asset('images/step1.png') }}" alt="Step 1">
            <div>
                <p>Step 1</p>
                <p>Register/Login</p>
            </div>
        </div>
        <div class="step">
            <img src="{{ asset('images/step2.png') }}" alt="Step 2">
            <div>
                <p>Step 2</p>
                <p>Fill out the Application Form</p>
            </div>
        </div>
        <div class="step">
            <img src="{{ asset('images/step3.png') }}" alt="Step 3">
            <div>
                <p>Step 3</p>
                <p>Upload Documents</p>
            </div>
        </div>
        <div class="step">
            <img src="{{ asset('images/step4.png') }}" alt="Step 4">
            <div>
                <p>Step 4</p>
                <p>Make Payment</p>
            </div>
        </div>
        <div class="step">
            <img src="{{ asset('images/step5.png') }}" alt="Step 5">
            <div>
                <p>Step 5</p>
                <p>Receive Confirmation</p>
            </div>
        </div>
    </div>
</div>

<div class="card-container">
    <marquee behavior="scroll" direction="left" scrollamount="25"> 
        <img src="{{ asset('images/busina card guest.png') }}" alt="Guest Card">
        <img src="{{ asset('images/busina card staff.png') }}" alt="Staff Card">
        <img src="{{ asset('images/busina card student.png') }}" alt="Student Card">
        <img src="{{ asset('images/busina card vip.png') }}" alt="VIP Card">  
    </marquee>
</div>

<marquee behavior="scroll" direction="right" scrollamount="20">
    <font face="poppins" color="#f3971b" size="3">
        GET YOUR BUSINA CARD NOW! GET YOUR BUSINA CARD NOW! GET YOUR BUSINA CARD NOW!
    </font>
</marquee>
@endsection
