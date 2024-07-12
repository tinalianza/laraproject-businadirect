@extends('layouts.app')

@section('title', 'Payment Success')

@section('content')
<div style="padding: 20px; max-width: 600px; margin: auto;">
    <h1>Payment Successful</h1>
    <p>Your payment has been successfully processed.</p>
    <a href="{{ url('/') }}" class="btn btn-primary">Go to Home</a>
</div>
@endsection
