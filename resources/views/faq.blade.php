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
        border: 2px solid #000000;
    }
    </style>
@endsection
