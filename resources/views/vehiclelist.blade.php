@extends('layouts.app')

@section('title', 'BUsina Online')

@section('content')

<style>
    h1 {
        font-family: 'Poppins', sans-serif;
        font-size: 18px; 
        color: black;
        margin: 0;
        text-align: center; 
    }

    .bee {
        color: #0061A6;
    }

    .per {
        color: #F2752B;
    }

    html, body {
        height: 100%;
        margin: 0;
        font-family: 'Poppins', sans-serif;
        font-size: 11px; 
        background-color: #ecf0f1;
        overflow-x: hidden;
        background-color: rgba(236, 240, 241, 0.5);
        
    }

    .container {
        display: flex;
        align-items: center; 
        justify-content: center; 
        height: 100vh; 
        padding: 10px;
        gap: 20px;
        max-width: 1000rem; 
        margin: auto; 
        flex-direction: column;
    }
    
    .text-muted{
        color: green;
        font-weight: 100;
        
    }

    .main-content {
        display: flex;
        flex-direction: row;
        justify-content: center; 
        width: 100%;
        max-width: 1200px; 
        gap: 20px; 
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

    .table-container {
    background-image: url('/images/torch.png');
    background-size: cover; /* Ensures the background image covers the entire container */
    background-position: center; /* Centers the background image */
    border: none; /* Make the border invisible */
    border-radius: 15px;
    padding: 20px;
    box-shadow: 0 4px 20px rgba(0, 0, 0, 0.2), /* Creates a 3D shadow effect */
                inset 0 2px 10px rgba(255, 255, 255, 0.3); /* Adds an inset shadow for depth */
    height: 400px;
    max-width: 500px; 
    display: flex;
    flex-direction: column;
    align-items: center;
    text-align: center;
    flex: 1; /* Ensures both containers take equal space */
    margin: -50px 0 0 0; /* Moves the container upward by 50 pixels */
}

    .qr {
        background-color: rgba(236, 240, 241, 0.5);
        border: 2px solid #2c3e50; 
        border-radius: 15px;
        padding: 20px;
        box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        height: 400px;
        max-width: 300px; 
        display: flex;
        flex-direction: column;
        align-items: center;
        text-align: center;
        flex: 1; /* Ensures both containers take equal space */
        margin: 0; /* Reset margin for centering */
        margin: -50px 0 0 0;
    }

    .table-container {
        margin-right: 10px; /* Add space between the two containers */
    }

    .qr-code img {
        max-width: 70%;
        max-height: 100%; /* Ensures it fits the height without distortion */
        object-fit: contain; /* Scales while maintaining aspect ratio */
        padding: 2px;
    }

    .qr-code .unavailable {
        font-size: 14px;
        color: #e74c3c;
        margin-bottom: 20px;
    }


    .qr-note {
        margin-top: 10px; /* Adds space between the QR code and the note */
        font-size: 14px;
        color: #555; /* Slightly muted color for the note */
    }
    .table {
        width: 100%;
        border-collapse: collapse;
        margin-top: 20px;
    }

    .page-title {
        font-size: 24px;
        margin-bottom: 20px;
    }

    .search-input, .filter-select {
        padding: 8px;
        border-radius: 4px;
        border: 1px solid #ccc;
    }

    .search-input {
        width: 300px;
    }

    .filter-select {
        width: 120px;
    }

    .table th, .table td {
        padding: 8px; 
        text-align: left;
        background-color: rgba(255, 255, 255, 0.3); 
    }

    .table th {
        background-color: #f4f4f4;
    }

    .table tr:hover {
    background-color: #ffffff !important; 
    }


    .pagination {
        margin-top: 20px;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .pagination div {
        font-size: 14px;
    }

    .renew-btn {
        background-color: #e77743;
        color: white;
        border: none;
        border-radius: 4px;
        padding: 5px 10px;
        cursor: pointer;
    }

    .add-vehicle-btn {
        background-color: #054470;
        color: white;
        border: none;
        border-radius: 4px;
        padding: 10px 20px;
        cursor: pointer;
        margin-top: 20px;
        display: block;
    }
    
</style>

<div class="sidebar" id="sidebar">
    <ul class="sidebar-menu">
        <li><a href="{{ route('dashboard') }}" data-tooltip="Home"><img src="{{ asset('images/home_btn.png') }}" alt="Home Icon"></a></li>
        <li><a href="{{ route('vehicles.list') }}" data-tooltip="Registered Vehicles"><img src="{{ asset('images/vehicle_btn.png') }}" alt="Vehicles Icon"></a></li>
        <li><a href="{{ route('edit.page') }}" data-tooltip="Edit Profile"><img src="{{ asset('images/edit_btn.png') }}" alt="Edit Icon"></a></li>
    </ul>
    <button class="toggle-btn" id="toggle-btn">☰</button>
</div>

<div class="container">
    <div class="main-content">
        <div class="table-container">
            <div class="heading">
                <h1>My <span class="bee">Bee</span><span class="per">per</span> Vehicles</h1>
            </div>

            <table class="table">
                <thead>
                    <tr>
                        <th>Model/Color</th>
                        <th>Plate Number</th>
                        <th>Expiry Date</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($vehicles as $vehicle)
                    @php

                    $latestTransaction = $vehicle->transactions()->latest()->first(); 
                    $stickerExpiry = $latestTransaction ? $latestTransaction->sticker_expiry : null; 
                    $isNearExpiration = $stickerExpiry && now()->diffInDays($stickerExpiry) <= 30; 
                @endphp
                        <tr>
                            <td>{{ $vehicle->model_color }}</td>
                            <td>{{ $vehicle->plate_no }}</td>
                            <td>{{ $vehicle->expiry_date }}</td>
                            <td>
                                <form id="renew-form-{{ $vehicle->id }}" method="get" action="{{ route('vehicle.renew.form', ['vehicle_id' => $vehicle->id]) }}">
                                    @csrf
                                    @if ($stickerExpiry <= now() || $isNearExpiration)
                                        <button type="submit" class="renew-btn">Renew</button>
                                    @else
                                        <span class="text-muted">Still Valid</span>
                                    @endif
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4">No vehicles found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>

            <a href="{{ route('vehicle.addvehicle.form', ['vehicle_id' => $vehicle->id]) }}" class="add-vehicle-btn">
                Add Vehicle
            </a>
        </div>

        <div class="qr">
            <div class="qr-code">
                @if(isset($vehicleOwner->qr_code))
                    <img src="data:image/png;base64,{{ $vehicleOwner->qr_code }}" alt="QR Code">
                @else
                    <p class="unavailable">QR Code Unavailable</p>
                @endif
            </div>
            <p class="qr-note">This QR code will be scanned at the gate for verification.</p>
        </div>        
    </div>
</div>

<script>
    document.getElementById('toggle-btn').addEventListener('click', function () {
        document.getElementById('sidebar').classList.toggle('hidden');
    });


</script>
@endsection