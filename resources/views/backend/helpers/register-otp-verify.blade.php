<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Email Verification OTP</title>
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
        <img class="email-logo" src="{{ asset($website_settings->site_icon ?? 'default-logo.png') }}" alt="Logo">

        <h2>Email Verification OTP</h2>

        <p>Hello {{ $tos }},</p>

        <p>Thank you for registering with {{ env('APP_NAME') }}! To complete your registration, please verify your email using the OTP below:</p>

        <div class="otp-box">
            {{ $otp }}
        </div>

      

        <div class="footer">
            If you did not initiate this registration, please ignore this email.<br>
            &copy; {{ date('Y') }} {{ env('APP_NAME') }}. All rights reserved.
        </div>
    </div>
</body>
</html>
