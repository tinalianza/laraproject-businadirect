@extends('layouts.app')

@section('title', 'BUsina Online')

@section('content')

<style>
html, body {
    height: 100%;
    margin: 0;
    font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    background-color: #ecf0f1;
    overflow-x: hidden;
}

.container {
    display: flex;
    height: 100vh;
}

.sidebar {
    width: 50px;
    background-color: #054470;
    height: 35%;
    position: fixed;
    top: 30%;
    left: 0;
    display: flex;
    flex-direction: column;
    align-items: center;
    padding-top: 20px;
    color: white;
    transition: transform 0.3s ease;
    transform: translateY(-50%);
    transform: translateX(0);
    border-radius: 10px;
    margin-left: 10px;
}

.sidebar.hidden {
    transform: translateX(-100%);
}

.sidebar .logo img {
    width: 40%;
}

.sidebar-menu {
    list-style: none;
    padding: 0;
    margin: 20px 0 0 0;
    width: 100%;
}

.sidebar-menu li {
    width: 100%;
    text-align: center;
    margin: 10px 0;
    position: relative;
}

.sidebar-menu li a {
    text-decoration: none;
    color: white;
    display: flex;
    align-items: center;
    justify-content: center;
    padding: 10px;
    transition: background-color 0.3s;
}

.sidebar-menu li a:hover {
    background-color: #34495e;
}

.sidebar-menu li a.active::after,
.sidebar-menu li a:hover::after {
    content: attr(data-tooltip);
    position: absolute;
    left: 100%;
    top: 50%;
    font-size: 8px;
    transform: translateY(-50%);
    background-color: #34495e;
    color: white;
    padding: 5px 10px;
    border-radius: 5px;
    white-space: nowrap;
    z-index: 1000;
    opacity: 1;
    visibility: visible;
    transition: opacity 0.3s ease, visibility 0.3s ease;
}

.sidebar-menu li a img {
    width: 30px;
    height: 30px;
}

.main-content {
    /* margin-left: 10px; Reduced margin */
    padding: 8px; /* Reduced padding */
    flex-grow: 1;
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    border: 1px solid #0E5486; /* Reduced border width */
    border-radius: 8px; /* Slightly rounded corners */
    background-color: #ffffff; /* Fallback background color */
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1); /* Subtle shadow to match the smaller border */
    /* background-image: url('{{ asset('images/loginbg.png') }}'); Background image */
    /* background-size: contain; /* Scale the background image to fit the container while preserving aspect ratio */
    /* background-repeat: repeat; Repeat the background image in a tile pattern */
    /* background-position: center; Center the background image */ */
}

.user-initials-circle {
    width: 100px;
    height: 100px;
    background-color: #0E5486;
    color: white;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 36px;
    margin-bottom: 20px;
}

.datetime {
    text-align: center;
    color: #0E5486;
}

.datetime p {
    font-size: 24px;
    font-weight: bold;
    background-color: #e74c3c;
    color: white;
    padding: 15px;
    border-radius: 8px;
    display: inline-block;
    margin-bottom: 10px;
}

.toggle-btn {
    position: absolute;
    top: 10%;
    left: 100%;
    transform: translateX(0px) translateY(-50%);
    background-color: #e77743;
    color: white;
    border-radius: 3px;
    border: none;
    padding: 10px;
    cursor: pointer;
    z-index: 1000;
}

@media (max-width: 768px) {
    .sidebar {
        width: 200px;
    }

    .main-content {
        margin-left: 200px;
    }
}

</style>

<div class="container">
    <div class="sidebar" id="sidebar">
        <ul class="sidebar-menu">
            <li><a href="{{ route('dashboard') }}" data-tooltip="Home"><img src="{{ asset('images/home_btn.png') }}" alt="Home Icon"></a></li>
            <li><a href="{{ route('vehicles.list') }}" data-tooltip="Registered Vehicles"><img src="{{ asset('images/vehicle_btn.png') }}" alt="Vehicles Icon"></a></li>
            <li><a href="{{ route('edit.page') }}" data-tooltip="Edit Profile"><img src="{{ asset('images/edit_btn.png') }}" alt="Edit Icon"></a></li>
        </ul>
        <button class="toggle-btn" id="toggle-btn">☰</button>
    </div>

    <div class="main-content">
        <div class="user-initials-circle">
            @if(Auth::check())
                @php
                    $fullName = Auth::user()->name;
                    $nameParts = explode(' ', $fullName);
                    $initials = strtoupper(substr($nameParts[0], 0, 1) . substr(end($nameParts), 0, 1));
                @endphp
                {{ $initials }}
            @endif
        </div>

        <div class="datetime">
            <p id="current-date"></p>
            <p id="current-time"></p>
        </div>

        <div class="datetime">
            <p id="current-date"></p>
            <p id="current-time"></p>
        </div>

        <button class="logout-button" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">Logout</button>
        <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
            @csrf
        </form>
    </div>
</div>

<script>
    document.getElementById('toggle-btn').addEventListener('click', function () {
        var sidebar = document.getElementById('sidebar');
        sidebar.classList.toggle('hidden');
    });

    function updateDateTime() {
        var now = new Date();
        var date = now.toLocaleDateString();
        var time = now.toLocaleTimeString();
        document.getElementById('current-date').textContent = date;
        document.getElementById('current-time').textContent = time;
    }

    setInterval(updateDateTime, 1000);
    updateDateTime();
</script>

@endsection
