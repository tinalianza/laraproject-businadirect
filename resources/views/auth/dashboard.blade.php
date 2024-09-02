@extends('layouts.app')

@section('title', 'BUsina Online')

@section('content')

<style>
    html, body {
        height: 100%;
        background-color: #ffffff;
        margin: 0;
        position: relative; 
        overflow: hidden;
    }

    .title-banner {
        color: #0E5486;
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        margin: 0;
    }

    .white-bg {
        background-color: rgba(255, 255, 255, 0.3);
        padding: 20px;
        border-radius: 10px;
        max-width: 600px;
        margin: 150px auto;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
        text-align: center;
    }

    .logout-button {
        display: block;
        width: 150px;
        margin: 20px auto;
        padding: 10px;
        text-align: center;
        background-color: #001cd2;
        color: white;
        border: none;
        border-radius: 5px;
        cursor: pointer;
        font-size: 16px;
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    }

    .logout-button:hover {
        background-color: #b25900; 
    }
</style>

<div class="bg-img"></div>
<div class="white-bg">
    <div class="title-banner">
        @if(Auth::check())
            @php
                $fullName = Auth::user()->name;
                $nameParts = explode(',', $fullName); 
                $formattedName = trim($nameParts[1] ?? '');
            @endphp
            <h1>Welcome, {{ $formattedName }}!</h1>
        @endif
    </div>

    @if(Auth::check())
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" class="logout-button">Log Out</button>
        </form>
    @endif
</div>

@endsection
