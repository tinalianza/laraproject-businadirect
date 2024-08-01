<!-- resources/views/checkout.blade.php -->

@extends('layouts.app')

@section('content')
<div class="container">
    <h2>Checkout</h2>
    <div id="card-container"></div>
    <button id="pay-button">Pay</button>
</div>
@endsection

@section('scripts')
<script src="{{ asset('js/checkout.js') }}"></script>
@endsection
