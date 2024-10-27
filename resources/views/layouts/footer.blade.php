
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>{{ config('app.name', 'BUsina Online') }}</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    <link rel="icon" href="{{ asset('favicon.png') }}" type="image/png">
    @yield('head')
    <style>
        /* General Styles */
        body, html {
            margin: 0;
            font-family: 'Poppins', sans-serif;
            background-color: #f5f5f9;
            height: 100vh;
            font-size: 12px;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
        }

        /* Footer Styles */
        .footer {
            background-color: #ffffff;
            padding: 20px;
            text-align: center;
            box-shadow: 0 -2px 10px rgba(0, 0, 0, 0.1);
            border-top: 1px solid #ddd;
        }

        .footer img {
            width: 80px;
            height: auto;
        }

        /* Additional Styles */
        .container {
            flex: 1;
            display: flex;
            justify-content: center;
            align-items: center;
        }
    </style>
</head>
<body>

    <div class="container">
        @yield('content')
    </div>

    <div class="footer">
        <img src="{{ asset('images/bu-logo.png') }}" alt="Bug Logo"> <!-- Path to your bug logo -->
    </div>

</body>
</html>
