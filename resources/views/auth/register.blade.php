@extends('layouts.app')

@section('title', 'Registration - BUsina Online')

@section('content')

<head>
    <!-- Replace the <style> tag in your Blade template with this link -->
    <link href="{{ asset('css/register.css') }}" rel="stylesheet">
</head>

<div style="padding: 20px; max-width: 1000px; margin: auto;">
    <div class="instruction">
        <h6><span class="please">Please fill out the following information.</span> <span class="ensure">Ensure all information is correct before submitting your application, as it cannot be changed once submitted.</span></h6>
    </div>

    <form method="POST" action="{{ route('register.submit') }}" enctype="multipart/form-data">
        @csrf
        <div class="form-group-checkbox">
            <label for="allow-sticker">
                <input type="checkbox" id="allow-sticker" name="allow_sticker">
                I’m allowing the BU Motorpool Section to attach the issued sticker to my vehicle.
            </label>
        </div>

        <div class="registration-form">

            <div class="details">        
                <div class="form-group">
                    <label for="name">Applicant's Name:</label>
                    <input type="text" id="name" name="name" placeholder="Surname, First Name, Middle Name" required>
                </div>

                <div class="form-group">
                    <label for="applicant-type">Applicant Type:</label>
                    <select id="applicant-type" name="applicant_type" required>
                        <option value="" disabled selected>Select Applicant Type</option>
                        <option value="bu-personnel">BU Personnel</option>
                        <option value="non-personnel">Non-Personnel</option>
                        <option value="student">Student</option>
                        <option value="vip">VIP</option>
                    </select>
                </div>

                <div class="form-group">
                    <label for="employee-id">Employee ID:</label>
                    <input type="text" id="employee-id" name="employee_id" placeholder="Enter Employee ID if applicable">
                </div>

                <div class="form-group">
                    <label for="email">Email Address:</label>
                    <input type="email" id="email" name="email" placeholder="Enter email address" required>
                </div>

                <div class="form-group">
                    <label for="contact-no">Contact No.:</label>
                    <input type="text" id="contact-no" name="contact_no" placeholder="Enter Contact Number" required>
                </div>
            </div>

            <div class="vehicle-details">
                <div class="form-group">
                    <label for="vehicle-type">Vehicle Type:</label>
                    <select id="vehicle-type" name="vehicle_type" required>
                        <option value="" disabled selected>Select Vehicle Type</option>
                        <option value="2-wheel">2-Wheel</option>
                        <option value="4-wheel">4-Wheel</option>
                    </select>
                </div>

                <div class="form-group">
                    <label for="driver-license">Driver's License Number:</label>
                    <input type="text" id="driver-license" name="driver_license" placeholder="Driver's License Number" required>
                </div>

                <div class="form-group">
                    <label for="vehicle-model">Vehicle Model/Color:</label>
                    <input type="text" id="vehicle-model" name="vehicle_model" placeholder="Vehicle Model/Color" required>
                </div>

                <div class="form-group">
                    <label for="plate-number">Plate Number:</label>
                    <input type="text" id="plate-number" name="plate_number" placeholder="Plate Number" required>
                </div>

                <div class="form-group">
                    <label for="or-cr-number">OR/CR Number:</label>
                    <input type="text" id="or-cr-number" name="or_cr_number" placeholder="OR/CR Number" required>
                </div>

                <div class="form-group">
                    <label for="expiration">Expiration:</label>
                    <input type="date" id="expiration" name="expiration" required>
                </div>

                <div class="form-group">
                    <label for="scanned-or-cr">Scanned copy of Vehicle OR/CR:</label>
                    <input type="file" id="scanned-or-cr" name="scanned_or_cr" accept="image/*" required>
                </div>

                <div class="form-group">
                    <label for="scanned-license">Scanned copy of Driver's License:</label>
                    <input type="file" id="scanned-license" name="scanned_license" accept="image/*" required>
                </div>

                <div class="form-group">
                    <label for="certificate">Certificate of Registration:</label>
                    <input type="file" id="certificate" name="certificate" accept="image/*" required>
                </div>

                <div class="form-group">
                    <label for="scanned-id">Scanned copy of School ID:</label>
                    <input type="file" id="scanned-id" name="scanned_id" accept="image/*" required>
                </div>
            </div>

            <div class="form-group-total-due">
                <label for="total-due">Total Due:</label>
                <input type="text" id="total-due" name="total_due" value="000.00" readonly>
            </div> 

            <button type="submit">Submit Application</button>
        </div>
    </form>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const allowStickerCheckbox = document.getElementById('allow-sticker');
        const formFieldsToToggle = document.querySelectorAll('.registration-form input, .registration-form select');
        const vehicleTypeSelect = document.getElementById('vehicle-type');
        const totalDueInput = document.getElementById('total-due');

        // Function to toggle form field disabled state based on checkbox
        function toggleFormFields() {
            const isChecked = allowStickerCheckbox.checked;
            formFieldsToToggle.forEach(field => {
                field.disabled = !isChecked;
            });
        }

        // Function to calculate total due based on vehicle type
        function calculateTotalDue() {
            const selectedVehicleType = vehicleTypeSelect.value;
            let totalDue = 0;

            if (selectedVehicleType === '2-wheel') {
                totalDue = 250;
            } else if (selectedVehicleType === '4-wheel') {
                totalDue = 500;
            }

            totalDueInput.value = totalDue.toFixed(2);
        }

        // Add event listeners
        allowStickerCheckbox.addEventListener('change', toggleFormFields);
        vehicleTypeSelect.addEventListener('change', calculateTotalDue);

        // Toggle initial state on page load
        toggleFormFields();
        calculateTotalDue();
    });
</script>

@endsection
