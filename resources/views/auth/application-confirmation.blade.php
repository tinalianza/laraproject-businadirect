@extends('layouts.app')

@section('title', 'Application Confirmation')

@section('content')
@if (Auth::check())
    {{ Auth::user()->name }}
@else
    Guest
@endif

<head>
    <link href="{{ asset('css/application-confirmation.css') }}" rel="stylesheet">
</head>

<div style="padding: 20px; max-width: 600px; margin: auto; text-align: center;">
    <form id="applicationconfirmation-form" method="POST" action="{{ route('application.createPayment') }}" enctype="multipart/form-data">
        @csrf
        <div class="application-confirmation">
            <div style="text-align: left;">
                <h2>Application Details</h2>
                <p id="applicant-name">Applicant Name:</p>
                <p id="applicant-type">Applicant Type:</p>
                <p id="contact-number">Contact Number:</p>
                <p id="email">- Email Address:</p>
                <p id="application-date">Application Date:</p>
                <p id="vehicle-model">Vehicle Make/Color:</p>
                <p id="license-plate">License Plate Number:</p>
                <input type="hidden" name="total_due" id="total_due_value"> <!-- Ensure the ID is correctly referenced -->
                <p id="total_due">Total Due:</p>
            </div>
            <div style="text-align: left;">
                <h3>- Instructions -</h3>
                <p id="one">1. <strong>Await Confirmation.</strong> Wait for an email confirmation from the Motorpool Office regarding your application status.<br>
                    Approval Notification: Once approved, you will receive an email containing your E-Receipt, Password, and Registration Number.</p>
                <p id="two">2. <strong>Access the Busina Portal.</strong> Use your Email and Password to access the Beeper Portal once registered.</p>
                <p id="three">3. <strong>Sticker Issuance Schedule.</strong> A schedule for sticker issuance and card collection will be emailed to you upon approval.</p>
                <p id="four">4. <strong>Visit Motorpool Office.</strong> Visit the Motorpool Office with necessary documents for sticker issuance upon your scheduled appointment.</p>
            </div>
        </div>

        <button type="submit">Proceed to Payment</button>
    </form>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const applicationData = JSON.parse(localStorage.getItem('applicationData'));

        if (applicationData) {
            document.getElementById('applicant-name').innerText = `- Applicant Name: ${applicationData.name}`;
            document.getElementById('applicant-type').innerText = `- Applicant Type: ${applicationData.applicant_type}`;
            document.getElementById('contact-number').innerText = `- Contact Number: ${applicationData.contact_no}`;
            document.getElementById('email').innerText = `- Email Address: ${applicationData.email}`;
            document.getElementById('application-date').innerText = `- Application Date: ${applicationData.application_date}`;
            document.getElementById('vehicle-model').innerText = `- Vehicle Make/Color: ${applicationData.vehicle_model}`;
            document.getElementById('license-plate').innerText = `- License Plate Number: ${applicationData.plate_number}`;
            document.getElementById('total_due').innerText = `- Total Due: ${applicationData.total_due}`;
            document.getElementById('total_due_value').value = applicationData.total_due; // Set the hidden input value
        }
    });
</script>
@endsection
