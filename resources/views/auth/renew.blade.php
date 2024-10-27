@extends('layouts.app')

@section('title', 'Registration - BUsina Online')

@section('content')

<head>
    <link href="{{ asset('css/register.css') }}" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>

<div style="padding: 20px; max-width: 1000px; margin: auto;">
    <div class="instruction">
        <h6><span class="please">Please fill out the following information.</span> <span class="ensure">Ensure all information is correct before submitting your application, as it cannot be changed once submitted.</span></h6>
    </div>

    <form id="registration-form" method="POST" action="{{ route('renew.submit') }}" enctype="multipart/form-data"> 
        @csrf
        <div class="form-group-checkbox">
            <label for="allow-sticker">
                <input type="checkbox" id="allow-sticker" name="allow_sticker">
                I’m allowing the BU Motorpool Section to access my personal details and attach the issued sticker to my vehicle.
            </label>
        </div>

        <div id="timer-display"></div>

        @if ($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="registration-form">
            <div class="details">
                <div class="form-group">
                    <label for="name">Applicant's Name:</label>
                    <input type="text" id="name" name="name" value="{{ old('name', $vehicle_owner->lname . ', ' . $vehicle_owner->fname . ' ' . $vehicle_owner->mname) }}" readonly>
                </div>

                <div class="form-group">
                    <label for="applicant-type">Applicant Type:</label>
                    <select id="applicant-type" name="applicant_type" required>
                        <option value="" disabled selected>Select Applicant Type</option>
                        <option value="BU-personnel">BU Personnel</option>
                        <option value="Non-Personnel">Non-Personnel</option>
                        <option value="Student">Student</option>
                    </select>
                    @error('applicant_type')
                        <p class="text-danger">{{ $message }}</p>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="id-no">ID Number:</label>
                    <input type="text" id="id-no" name="id_no" value="{{ old('id_no', $vehicle_owner->id_no ?? 'N/A') }}" readonly>
                </div>                

                <div class="form-group">
                    <label for="email">Email Address:</label>
                    <input type="email" id="email" name="email" value="{{ old('email', $email) }}">
                </div>    

                <div class="form-group">
                    <label for="contact-no">Contact No.:</label>
                    <input type="text" id="contact-no" name="contact_no" value="{{ old('contact_no', $vehicle_owner->contact_no) }}" >
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
                    @error('vehicle_type')
                        <p class="text-danger">{{ $message }}</p>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="driver-license">Driver's License Number:</label>
                    <input type="text" id="driver-license" name="driver_license_no" value="{{ old('driver_license', $vehicle->vehicleOwner->driver_license_no) }}" readonly>
                </div>

                <div class="form-group">
                    <label for="vehicle-model">Vehicle Model/Color:</label>
                    <input type="text" id="vehicle-model" name="vehicle_model" value="{{ old('vehicle_model', $vehicle->model_color) }}"readonly>
                </div>

                <div class="form-group">
                    <label for="plate-number">Plate Number:</label>
                    <input type="text" id="plate-number" name="plate_no" value="{{ old('plate_number', $vehicle->plate_no) }}"readonly>
                </div>

                <div class="form-group">
                    <label for="or-number">OR Number:</label>
                    <input type="text" id="or-number" name="or_no" value="{{ old('or_number', $vehicle->or_no) }}">
                </div>

                <div class="form-group">
                    <label for="cr-number">CR Number:</label>
                    <input type="text" id="cr-number" name="cr_no" value="{{ old('cr_number', $vehicle->cr_no) }}">
                </div>

                <div class="form-group">
                    <label for="expiration">Expiration:</label>
                    <input type="date" id="expiration" name="expiration" required>
                    <small>The expiration date must be at least 30 days from the date of application</small>
                    @error('expiration')
                        <p class="text-danger">{{ $message }}</p>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="scanned-or-cr">Scanned copy of Vehicle OR/CR:</label>
                    <input type="file" id="scanned-or-cr" name="scanned_or_cr" accept="image/jpeg, image/png" required>
                    <small>Accepted formats: JPG, PNG (Max size: 3MB)</small>
                    @error('scanned_or_cr')
                        <p class="text-danger">{{ $message }}</p>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="scanned-license">Scanned copy of Driver's License:</label>
                    <input type="file" id="scanned-license" name="scanned_license" accept="image/jpeg, image/png" required>
                    <small>Accepted formats: JPG, PNG (Max size: 3MB)</small>
                    @error('scanned_license')
                        <p class="text-danger">{{ $message }}</p>
                    @enderror
                </div>

                <div class="form-group student-only" style="display: none;">
                    <label for="scanned-id">Scanned copy of School ID:</label>
                    <input type="file" id="scanned-id" name="scanned_id" accept="image/jpeg, image/png">
                    <small>Accepted formats: JPG, PNG (Max size: 3MB)</small>
                    @error('scanned_id')
                        <p class="text-danger">{{ $message }}</p>
                    @enderror
                </div>

                <div class="form-group student-only" style="display: none;">
                    <label for="scanned-id">Scanned copy of Latest COR:</label>
                    <input type="file" id="certificate" name="certificate" accept="image/jpeg, image/png">
                    <small>Accepted formats: JPG, PNG (Max size: 3MB)</small>
                    @error('certificate')
                        <p class="text-danger">{{ $message }}</p>
                    @enderror
                </div>

                <div class="form-group-total-due">
                    <label for="total_due">Total Due:</label>
                    <input type="text" id="total_due" name="total_due" value="000.00" readonly>
                </div>
            </div>

            <div class="form-group">
                <button type="submit" class="btn btn-primary">Register</button>
            </div>
        </div>
    </form>

    <script src="{{ asset('js/register.js') }}"></script>
</div>

<script>
    $(document).ready(function() {
    $('#applicant-type').change(function() {
        var type = $(this).val();
        if (type === 'Student') {
            $('.student-only').show();
        } else {
            $('.student-only').hide();
        }
    });
});
   document.addEventListener('DOMContentLoaded', function() {
    const allowStickerCheckbox = document.getElementById('allow-sticker');
    const formFieldsToToggle = document.querySelectorAll('.registration-form input, .registration-form select');
    const vehicleTypeSelect = document.getElementById('vehicle-type');
    const totalDueInput = document.getElementById('total_due');
    const applicantTypeSelect = document.getElementById('applicant-type');
    const studentOnlyFields = document.querySelectorAll('.student-only');
    const employeeIdInput = document.getElementById('employee-id');
    const maxSizeMB = 3;
    const maxSizeBytes = maxSizeMB * 1024 * 1024;
    const timerDisplay = document.getElementById('timer-display');
    const timerDuration = 30 * 60 * 1000;
    let timer;

    function startTimer() {
        const endTime = Date.now() + timerDuration;

        function updateTimer() {
            const remainingTime = endTime - Date.now();

            if (remainingTime <= 0) {
                clearInterval(timer);
                resetForm();
                return;
            }

            const minutes = Math.floor(remainingTime / (60 * 1000));
            const seconds = Math.floor((remainingTime % (60 * 1000)) / 1000);

            timerDisplay.textContent = `Time left to complete your application: ${minutes}:${seconds < 10 ? '0' : ''}${seconds}s. Please be advised that this timer is in place for security purposes to ensure that your session remains active and to protect your personal data. The timer will restart if it expires to provide you with additional time if needed.`;
        }

        updateTimer();
        timer = setInterval(updateTimer, 1000);
    }

    function resetForm() {
        formFieldsToToggle.forEach(field => {
            field.disabled = true;
        });
        document.getElementById('registration-form').reset();
        totalDueInput.value = '000.00';
        timerDisplay.textContent = 'Your session has expired due to inactivity. Please start the registration process again.';
    }

    function toggleFormFields() {
        const isChecked = allowStickerCheckbox.checked;
        formFieldsToToggle.forEach(field => {
            field.disabled = !isChecked;
        });
    }

    function calculateTotalDue() {
    const selectedApplicantType = applicantTypeSelect.value;
    const selectedVehicleType = vehicleTypeSelect.value;
    let totalDue = 0;

    // Calculate total due based on applicant and vehicle type
    if (selectedApplicantType === 'BU-personnel') {
        totalDue = selectedVehicleType === '4-wheel' ? 350 : 150;
    } else if (selectedApplicantType === 'Non-Personnel') {
        totalDue = selectedVehicleType === '4-wheel' ? 500 : 250;
    } else if (selectedApplicantType === 'Student') {
        totalDue = selectedVehicleType === '4-wheel' ? 500 : 250;
    }

    // Update the total due input field
    totalDueInput.value = totalDue.toFixed(2);
}


    function validateFileSize(input) {
        const file = input.files[0];
        if (file && file.size > maxSizeBytes) {
            alert(`File size must be less than ${maxSizeMB} MB.`);
            input.value = '';
        }
    }

    function toggleStudentFields() {
        const isStudent = applicantTypeSelect.value === 'Student';
        studentOnlyFields.forEach(field => {
            field.style.display = isStudent ? 'block' : 'none';
        });
    }

    function toggleEmployeeIdField() {
        const isNonPersonnel = applicantTypeSelect.value === 'Non-Personnel';
        employeeIdInput.disabled = isNonPersonnel;
        if (isNonPersonnel) {
            employeeIdInput.value = '';
        }
    }

    function validateExpirationDate() {
        const expirationInput = document.getElementById('expiration');
        const today = new Date();
        const minDate = new Date(today.getFullYear(), today.getMonth(), today.getDate() + 30);
        const minDateString = minDate.toISOString().split('T')[0];

        expirationInput.setAttribute('min', minDateString);
    }

    allowStickerCheckbox.addEventListener('change', toggleFormFields);
    vehicleTypeSelect.addEventListener('change', calculateTotalDue);
    applicantTypeSelect.addEventListener('change', function() {
        toggleStudentFields();
        toggleEmployeeIdField(); 
    });

    document.querySelectorAll('input[type="file"]').forEach(input => {
        input.addEventListener('change', function () {
            validateFileSize(this);
        });
    });

    validateExpirationDate();

    toggleFormFields();
    calculateTotalDue();
    toggleStudentFields();
    toggleEmployeeIdField();

    const form = document.getElementById('registration-form');
    form.addEventListener('submit', function(event) {
        const formData = {
            name: document.getElementById('name').value,
            applicant_type: document.getElementById('applicant-type').value,
            contact_no: document.getElementById('contact-no').value,
            email: document.getElementById('email').value,
            application_date: new Date().toLocaleDateString(),
            vehicle_model: document.getElementById('vehicle-model').value,
            plate_number: document.getElementById('plate-number').value,
            total_due: document.getElementById('total_due').value,
        };
        localStorage.setItem('applicationData', JSON.stringify(formData));
    });

    startTimer();
});
</script>

@endsection
