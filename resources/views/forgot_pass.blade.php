<!-- resources/views/forgot_pass.blade.php -->
<!DOCTYPE html>
<html>
<head>
    <title>Forgot Password</title>
</head>
<body>
    <h1>Forgot Password</h1>
    @if (session('error'))
        <p style="color: red;">{{ session('error') }}</p>
    @endif

    <form action="{{ route('password.email') }}" method="POST">
        @csrf
        <label for="emp_no">Employee Number:</label>
        <input type="text" name="emp_no" id="emp_no" required>
        @error('emp_no')
            <p style="color: red;">{{ $message }}</p>
        @enderror
        <button type="submit">Send Reset Code</button>
    </form>
</body>
</html>
