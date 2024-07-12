@extends('layouts.app')

@section('title', 'BUsina Online')

@section('content')
<div class="slider-container">
    <div class="overlay-text">
        <h1>Welcome, Beepers!</h1>
        <a href="#" class="learn-more">Learn more</a>
    </div>
    <div class="steps">
        <div class="step">
            <img src="{{ asset('public/images/step1.png') }}" alt="Step 1">
            <p>Register/Login</p>
        </div>
        <div class="step">
            <img src="{{ asset('public/images/step2.png') }}" alt="Step 2">
            <p>Fill out the Application Form</p>
        </div>
        <div class="step">
            <img src="{{ asset('public/images/step3.png') }}" alt="Step 3">
            <p>Upload Documents</p>
        </div>
        <div class="step">
            <img src="{{ asset('public/images/step4.png') }}" alt="Step 4">
            <p>Make Payment</p>
        </div>
        <div class="step">
            <img src="{{ asset('public/images/step5.png') }}" alt="Step 5">
            <p>Receive Confirmation</p>
        </div>
    </div>
</div>
<div class="card-container">
    <img src="{{ asset('images/card1.png') }}" alt="Card 1">
    <img src="{{ asset('images/card2.png') }}" alt="Card 2">
    <img src="{{ asset('images/card3.png') }}" alt="Card 3">
    <!-- Add more cards as needed -->
</div>

<style>
    .steps {
        border: 2px solid #ccc; /* Adds a solid border around the steps */
        padding: 20px; /* Adds space inside the border */
        margin: 20px 0; /* Adds space outside the border */
        border-radius: 10px; /* Optional: Adds rounded corners to the border */
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1); /* Optional: Adds a subtle shadow for better visualization */
    }
    .step {
        text-align: center;
        margin-bottom: 20px;
    }
    .step img {
        max-width: 100%;
        height: auto;
    }
    .slider-container {
        position: relative;
        text-align: center;
        color: white;
    }
    .overlay-text {
        position: absolute;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
    }
    .learn-more {
        display: inline-block;
        padding: 10px 20px;
        background-color: #007bff;
        color: white;
        text-decoration: none;
        border-radius: 5px;
        margin-top: 20px;
    }
    .learn-more:hover {
        background-color: #0056b3;
    }
    .card-container {
        display: flex;
        justify-content: space-around;
        margin-top: 20px;
    }
    .card-container img {
        max-width: 30%;
        height: auto;
        border-radius: 10px;
    }
</style>
@endsection
