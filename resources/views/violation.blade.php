@extends('layouts.app')

@section('title', 'BUsina Online')

@section('content')

<style>
    html, body {
        height: 100%;
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
        height: 50%;
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
        background-color: #D27100;
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


    .sidebar {
    width: 50px;
    background-color: #011121;
    height: 50%;
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
    position: relative; 

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
    margin-left: 200px;
    padding: 20px;
    width: calc(100% - 200px);
    background-color: #ecf0f1;
    transition: margin-left 0.3s ease;
}

.main-content.expanded {
    margin-left: 0;
    width: 100%;
}

.toggle-btn {
    position: fixed;
    top: 125px;
    left: 10px;
    background-color: #2c3e50;
    color: white;
    border: none;
    padding: 10px;
    cursor: pointer;
    z-index: 1000;
}


</style>
<div class="sidebar" id="sidebar">
    <ul class="sidebar-menu">
        <li><a href="{{ route('dashboard') }}"data-tooltip="Home"><img src="{{ asset('images/home_btn.png') }}" alt="Home Icon"></a></li>
        <li><a href="{{ route('vehicles.list') }}" data-tooltip="Registered Vehicles"><img src="{{ asset('images/vehicle_btn.png') }}" alt="Vehicles Icon"></a></li>
        <li><a href="{{ route('notifications.list') }}" data-tooltip="Notifications"><img src="{{ asset('images/notification_btn.png') }}" alt="Notifications Icon"></a></li>
        <li><a href="{{ route('edit.page') }}" data-tooltip="Edit Profile"><img src="{{ asset('images/edit_btn.png') }}" alt="Edit Icon"></a></li>
        <li><a href="{{ route('violation.page') }}" data-tooltip="Violations"><img src="{{ asset('images/alert_btn.png') }}" alt="Violation Icon"></a></li>
    </ul>
</div>
<div class="main-content" id="main-content">
    <button class="toggle-btn" id="toggle-btn">☰</button>

</div>
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

        <h5 class="card-title">Your QR Code</h5>
        {{-- @if (Auth::user()->registration)
            <img src="{{ Storage::url('public/qrcodes/' . Auth::user()->registration->id . '.png') }}" alt="QR Code">
        @else
            <p>No QR Code available. Please register.</p>
            @endif --}}
</div>

<script>
    document.getElementById('toggle-btn').addEventListener('click', function () {
    var sidebar = document.getElementById('sidebar');
    var mainContent = document.getElementById('main-content');
    
    sidebar.classList.toggle('hidden');
    mainContent.classList.toggle('expanded');
});
</script>

@endsection
