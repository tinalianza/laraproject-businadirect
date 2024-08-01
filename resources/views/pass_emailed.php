<!-- resources/views/pass_emailed.blade.php -->
<!DOCTYPE html>
<html>
<head>
    <title>Password Reset Email Sent</title>
</head>
<body>
    <h1>Password Reset Email Sent</h1>
    @if (session('success'))
        <p style="color: green;">{{ session('success') }}</p>
    @endif
</body>
</html>
