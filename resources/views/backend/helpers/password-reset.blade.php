<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Password Reset OTP</title>
    <style>
        body {
            background-color: #f8f9fa;
            font-family: Arial, sans-serif;
            padding: 20px;
        }
        .email-container {
            max-width: 500px;
            margin: auto;
            background-color: #ffffff;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.1);
            text-align: center;
        }
        .email-logo {
            width: 100px;
            margin-bottom: 20px;
        }
        .otp-box {
            font-size: 32px;
            font-weight: bold;
            background-color: #f1f3f5;
            padding: 15px 0;
            border-radius: 8px;
            margin: 20px 0;
            letter-spacing: 5px;
        }
        .footer {
            font-size: 12px;
            color: #6c757d;
            margin-top: 30px;
        }
    </style>
</head>
<body>
    <div class="email-container">
        <img class="email-logo" src="{{ asset($website_settings->site_icon ?? 'No Icon') }}" alt="Logo">

        <h2>Password Reset OTP</h2>

        <p>Hello {{ $tos}}</p>

        <p>You requested to reset your password. Use the OTP below to proceed:</p>

        <div class="otp-box">
            {{ $otp}}
        </div>

        <p>This OTP is valid for a limited time. Please do not share it with anyone.</p>

        <div class="footer">
            If you did not request a password reset, you can safely ignore this email.<br>
            &copy; {{ date('Y') }} {{ env('APP_NAME') }}. All rights reserved.
        </div>
    </div>
</body>
</html>
