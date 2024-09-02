@extends('layouts.app')

@section('head')
    @parent
    <style>

        .sidebar {
            width: 100px;
            background-color: #2c3e50;
            height: calc(100% - 73px);
            position: fixed;
            top: 73px; 
            left: 0;
            display: flex;
            flex-direction: column;
            align-items: center;
            padding-top: 20px;
            color: white;
            transition: transform 0.3s ease;
            transform: translateX(0);
            z-index: 999; 
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

        .sidebar-menu li a img {
            width: 20px;
            margin-right: 10px;
        }

        .main-content {
            margin-left: 100px;
            padding: 20px;
            width: calc(100% - 100px);
            background-color: #ecf0f1;
            transition: margin-left 0.3s ease;
        }

        .main-content.expanded {
            margin-left: 0;
            width: 100%;
        }

        .toggle-btn {
            position: fixed;
            top: 85px; 
            left: 10px;
            background-color: #2c3e50;
            color: white;
            border: none;
            padding: 10px;
            cursor: pointer;
            z-index: 1000;
        }
    </style>
@endsection

@section('content')
    <div class="sidebar" id="sidebar">
        <ul class="sidebar-menu">
            <li><a href="{{ route('vehicles.list') }}"><img src="{{ asset('images/vehicle_btn.png') }}" alt="Vehicles Icon"></a></li>
            <li><a href="{{ route('notifications.list') }}"><img src="{{ asset('images/notification_btn.png') }}" alt="Notifications Icon"></a></li>
            <li><a href="{{ route('edit.page') }}"><img src="{{ asset('images/edit_btn.png') }}" alt="Edit Icon"></a></li>
            <li><a href="{{ route('violation.page') }}"><img src="{{ asset('images/violation_btn.png') }}" alt="Violation Icon"></a></li>
        </ul>
    </div>

    <div class="main-content" id="main-content">
        <button class="toggle-btn" id="toggle-btn">☰</button>
        @yield('page-content')
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
