@extends('layouts.app')

@section('title', 'Payment Failed')

@section('content')
<div style="padding: 20px; max-width: 600px; margin: auto;">
    <h1>Payment Failed</h1>
    <p>There was an issue processing your payment. Please try again.</p>
    <a href="{{ url('/') }}" class="btn btn-primary">Go to Home</a>
</div>
@endsection
