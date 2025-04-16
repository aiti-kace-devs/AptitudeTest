<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $programme->title }} Course Details</title>
    <link rel="stylesheet" href="{{ asset('assets/home/css/style.css') }}">
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #e8f0fe;
        }

        .container {
            display: grid;
            grid-template-columns: 2fr 1fr;
            align-items: center;
            height: 100vh;
            padding: 20px;
            gap: 20px;
        }

        .text-content {
            background-color: #ffffff;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0px 4px 8px rgba(0, 0, 0, 0.1);
        }

        .image-content img {
            width: 100%;
            height: 100vh;
            object-fit: cover;
            border-radius: 10px;
        }

        h1,
        .detail-item {
            margin-bottom: 15px;
        }

        .detail-label {
            font-weight: bold;
            display: block;
        }

        /* .register-button {
            display: inline-block;
            padding: 10px 20px;
            background-color: #007BFF;
            color: white;
            text-decoration: none;
            border-radius: 5px;
            margin-top: 15px;
        } */
    </style>
</head>

<body>
    <div class="container">
        <div class="text-content">
            <div class="detail-item">
                <h1>{{ $programme->title }}</h1>
                <!-- <span class="detail-value">Certified Cybersecurity Professional</span> -->
            </div>

            <div class="detail-item">
                {!! $programme->content !!}
            </div>

            <div class="detail-item">
                <h3>Training Duration:</h3>
                <p>{{ $programme->duration }}</p>
            </div>

            <a href="{{ url('/forms/register') }}" class="register-button">Register</a>
        </div>
        <div class="image-content">
            <img src="{{ asset('storage/programme/' . $programme->image) }}" alt="{{ $programme->title }} Course Image" />
        </div>
    </div>
</body>

</html>