@extends('layouts.app')

@section('title', 'Application Pending Status')

@section('content')
<head>
    <link href="{{ asset('css/application-pending.css') }}" rel="stylesheet">
</head>
<div class= "message">
    <p>Thank you for registering for a portal account with our Motorpool system. </p>
    <p id="approval">Your registration is currently pending approval from the Motorpool Section team.</p>
    <p>Upon approval, you will receive an email containing the schedule for claiming your sticker and card. </p>
    <p>Thank you, Beeper!</p>
</div>
@endsection