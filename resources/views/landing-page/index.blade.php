<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>One Million Coders - Ghana</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: Arial, sans-serif;
        }

        body,
        html {
            height: 100%;
            width: 100%;
            overflow: hidden;
        }

        .container {
            display: flex;
            width: 100%;
            height: 100vh;
        }

        .image-section {
            flex: 1;
            background: url('assets/home/images/b.jpg') no-repeat center center/cover;
            position: relative;
        }

        .image-section::before {
            content: "";
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            /*            background: rgba(0, 0, 0, 0.5); /* Black overlay */
            */
        }

        .text-section {
            flex: 1;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            padding: 50px;
            background: linear-gradient(to right, #562c2c, #FF3333);
            /* Red and White Blend */
            color: white;
            text-align: center;
        }

        .text-section h1 {
            font-size: 42px;
            margin-bottom: 20px;
        }

        .text-section p {
            font-size: 20px;
            margin-bottom: 30px;
            line-height: 1.6;
            max-width: 80%;
        }

        .btn {
            display: inline-block;
            padding: 15px 30px;
            background: white;
            color: #FF3333;
            font-size: 20px;
            font-weight: bold;
            border-radius: 5px;
            text-decoration: none;
            transition: 0.3s ease-in-out;
        }

        .btn:hover {
            background: #FF3333;
            color: white;
        }

        /* Responsive Design */
        @media (max-width: 900px) {
            .container {
                flex-direction: column;
            }

            .image-section {
                height: 50vh;
            }

            .text-section {
                height: 50vh;
                padding: 30px;
                overflow-y: auto;
            }

            .text-section h1 {
                font-size: 32px;
            }

            .text-section p {
                font-size: 18px;
            }
        }

        .button-container {
            display: flex;
            justify-content: center;
            gap: 20px;
        }

        .logo-container {
            position: absolute;
            top: 20px;
            left: 20px;
        }

        .logo-container img {
            height: 80px;
            filter: drop-shadow(2px 2px 4px rgba(0, 0, 0, 0.5));
            margin-inline: 10px;
        }
    </style>
</head>

<body>

    <div class="container">
        <div class="image-section"></div>
        <div class="text-section">
            <div class="logo-container">
                <img src="{{ url('assets/home/images/c.png') }}">
                <img src="{{ url('assets/home/images/LOGO.png') }}" alt="One Million Coders Ghana Logo">

            </div>
            <h1>The One Million Coders Program - Ghana</h1>
            <p>Empowering Ghanaian youth with coding skills for the future.</p>
            <p>
            <h2>Are you ready to unlock your digital potential?</h2>

            The Ghana government's One Million Coders initiative is empowering Ghanaians with the skills of the future.
            Whether you're a student, professional, or simply curious, this program offers a pathway to a brighter
            future.
            </p>
            <div class="button-container">
                <!-- <a href="https://coders.gikace.org/login" class="btn">Log in</a> -->
                <a href="{{ route('available-courses') }}" class="btn">Register Now</a>

            </div>
        </div>
    </div>

</body>

</html>
