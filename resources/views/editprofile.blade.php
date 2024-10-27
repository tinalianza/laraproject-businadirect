@extends('layouts.app')

@section('title', 'BUsina Online')

@section('content')
<head>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    
</head>
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

.profile-container {
    background-color: rgba(236, 240, 241, 0.5);
    border: 2px solid #f67d1a; 
    border-radius: 15px;
    padding: 20px;
    box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
    height: 400px;
    max-width: 500px; 
    display: flex;
    align-items: center; 
    justify-content: center; 
    padding: 10px;
    gap: 20px;
    margin: auto; 
    flex-direction: column;
    margin-top: 10%;
}

.profile-container h2 {
    color: #054470; /* Dark blue for the title */
}

.form-group {
    margin-bottom: 15px;
}

.form-group label {
    display: block;
    margin-bottom: 5px;
    color: #34495e; /* Darker blue for labels */
}

.form-group input {
    width: 100%;
    padding: 10px;
    border: 1px solid #e0e0e0;
    border-radius: 5px;
}

.form-group input:focus {
    border-color: #e77743; /* Orange on focus */
}

.form-group button {
    background-color: #e77743; /* Orange */
    color: white;
    border: none;
    padding: 10px 15px;
    border-radius: 5px;
    cursor: pointer;
    transition: background-color 0.3s;
}

.form-group button:hover {
    background-color: #d76a3e; /* Darker orange on hover */
}

.error {
    color: red;
    font-size: 0.9em;
    margin-top: 5px;
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

<div class="profile-container">
    <h2>Edit Profile</h2>
    <form action="{{ route('profile.update') }}" method="POST" onsubmit="return validatePasswords();">
        @csrf
        @method('PUT') <!-- Use PUT for updating resources -->
        
        <div class="form-group">
            <label for="name">Vehicle Owner Name</label>
            <input type="text" id="name" name="name" 
                   value="{{ old('name', $vehicle_owner->lname . ', ' . $vehicle_owner->fname . ' ' . $vehicle_owner->mname) }}" readonly>
        </div>
        
        <div class="form-group">
            <label for="email">Email</label>
            <input type="email" id="email" name="email" 
                   value="{{ old('email', $user->email) }}" required pattern="[a-z0-9._%+-]+@[a-z0-9.-]+\.[a-z]{2,}$" title="Enter a valid email address.">
        </div>
        
        <div class="form-group">
            <label for="contact-no">Contact No.</label>
            <input type="text" id="contact-no" name="contact_no" 
                   value="{{ old('contact_no', $vehicle_owner->contact_no) }}" required 
                   pattern="^9\d{9}$" title="Contact number must be 10 digits and start with 9.">
        </div>

        <div class="form-group">
            <label for="password">New Password (leave blank to keep current)</label>
            <div style="position: relative;">
                <input type="password" id="password" name="password" 
                        pattern="^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&#.])[A-Za-z\d@$!%*?&#.]{8,}$" 
                       title="Password must be at least 8 characters long, contain at least one uppercase letter, one lowercase letter, one number, and one special character.">
                <span class="toggle-password" onclick="togglePasswordVisibility('password')" style="position: absolute; right: 10px; top: 50%; transform: translateY(-50%); cursor: pointer;">
                    <i class="fas fa-eye"></i>
                </span>
            </div>
        </div>
        
        <div class="form-group">
            <label for="password_confirmation">Confirm Password</label>
            <div style="position: relative;">
                <input type="password" id="password_confirmation" name="password_confirmation" 
                        pattern="^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&#.])[A-Za-z\d@$!%*?&#.]{8,}$" 
                       title="Password must be at least 8 characters long, contain at least one uppercase letter, one lowercase letter, one number, and one special character.">
                <span class="toggle-password" onclick="togglePasswordVisibility('password_confirmation')" style="position: absolute; right: 10px; top: 50%; transform: translateY(-50%); cursor: pointer;">
                    <i class="fas fa-eye"></i>
                </span>
            </div>
            <div id="password-error" class="error" style="display: none;">Passwords do not match.</div>
        </div>
        
        
        <div class="form-group">
            <button type="submit">Update Profile</button>
        </div>
    </form>
</div>

<script>
    function togglePasswordVisibility(fieldId) {
        const field = document.getElementById(fieldId);
        const icon = field.nextElementSibling.querySelector('i');
        if (field.type === "password") {
            field.type = "text";
            icon.classList.remove('fa-eye');
            icon.classList.add('fa-eye-slash');
        } else {
            field.type = "password";
            icon.classList.remove('fa-eye-slash');
            icon.classList.add('fa-eye');
        }
    }
    function validatePasswords() {
        const password = document.getElementById('password').value;
        const passwordConfirmation = document.getElementById('password_confirmation').value;
        const errorMessage = document.getElementById('password-error');

        if (password !== passwordConfirmation) {
            errorMessage.style.display = 'block'; // Show error message
            return false; // Prevent form submission
        } else {
            errorMessage.style.display = 'none'; // Hide error message
            return true; // Allow form submission
        }
    }
</script>

@endsection
