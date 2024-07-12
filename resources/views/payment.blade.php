<!-- resources/views/payment.blade.php -->

<form action="{{ route('payment.initiate') }}" method="POST">
    @csrf
    <!-- Add payment form fields here -->
    <button type="submit">Pay Now</button>
</form>
