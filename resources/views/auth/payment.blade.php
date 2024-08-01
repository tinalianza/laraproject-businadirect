@extends('layouts.app')

@section('title', 'Payment - BUsina Online')

@section('content')
<div style="padding: 20px; max-width: 800px; margin: auto; display: flex; align-items: flex-start;">
    <div style="flex: 1;">
        <h2>Payment</h2>

        <p id="applicant-name">Applicant Name: {{ $applicationData['name'] }}</p>
        <p id="applicant-type">Applicant Type: {{ $applicationData['applicant_type'] }}</p>
        <p id="total_due">Total Due: PHP {{ number_format($applicationData['total_due'], 2) }}</p>

        <div id="dropin-container"></div>
    </div>

    <div style="flex: 1; margin-left: 20px;">
        <h3 style="margin-bottom: 10px;">GCash QR Code:</h3>
        <img src="{{ asset('images/GCash_QR.jpg') }}" alt="GCash QR Code" style="max-width: 200px;">
    </div>
</div>

<script src="https://checkoutshopper-test.adyen.com/checkoutshopper/sdk/5.15.0/adyen.js"></script>
<link rel="stylesheet" href="https://checkoutshopper-test.adyen.com/checkoutshopper/sdk/5.15.0/adyen.css">

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const paymentSession = @json($paymentSession);

        const configuration = {
            clientKey: '{{ env('ADYEN_CLIENT_KEY') }}',
            locale: 'en_US',
            environment: '{{ env('ADYEN_ENVIRONMENT') }}',
            paymentMethodsResponse: paymentSession.paymentMethods,
            session: {
                id: paymentSession.id,
                sessionData: paymentSession.sessionData
            },
            onPaymentCompleted: (result, component) => {
                window.location.href = "{{ route('payment.success') }}";
            },
            onError: (error, component) => {
                console.error(error.name, error.message, error.stack, component);
            }
        };

        const checkout = new AdyenCheckout(configuration);
        checkout.create('dropin').mount('#dropin-container');
    });
</script>
@endsection
