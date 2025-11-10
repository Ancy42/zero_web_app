<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="description" content="OTP Verification">
    <title>OTP Verification</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f9;
            margin: 0;
            padding: 0;
            color: #333;
        }

        .email-container {
            max-width: 600px;
            margin: 40px auto;
            background: #fff;
            border-radius: 8px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            overflow: hidden;
        }

        .email-header {
            background-color: #4CAF50;
            color: white;
            text-align: center;
            padding: 12px;
        }

        .email-header h1 {
            margin: 0;
            font-size: 24px;
        }

        .email-body {
            padding: 20px;
            text-align: center;
        }

        .email-body h3 {
            margin-top: 0;
            color: #4CAF50;
        }

        .otp-code {
            display: inline-block;
            font-size: 32px;
            font-weight: bold;
            color: #4CAF50;
            letter-spacing: 4px;
            margin: 20px 0;
            padding: 10px 20px;
            border: 2px dashed #4CAF50;
            border-radius: 8px;
        }

        .email-footer {
            background-color: #f4f4f9;
            text-align: center;
            padding: 15px;
            font-size: 12px;
            color: #666;
        }

        .email-footer a {
            color: #4CAF50;
            text-decoration: none;
        }
    </style>
</head>

<body>
    <div class="email-container">
        <div class="email-header">
            <h2>Verification Code</h2>
        </div>
        <div class="email-body">
            <h3>Hello, Your One-Time Password (OTP) is:</h3>
            <div class="otp-code">{{ $otp }}</div>
            <p>{{ $messageContent }}</p>
            <p>Please use this OTP to complete your verification process. The code is valid for the next 10 minutes.</p>
            <p>If you did not request this code, please ignore this email or contact our support team.</p>
        </div>
        <div class="email-footer">
            <p>&copy; {{ date('Y') }} {{ config('app.name') }}. All rights reserved.</p>
            <p>
                <a href="{{ config('app.url') . '/privacy-policy' }}">Privacy Policy</a> |
                <a href="{{ config('app.url') . '/contact-us' }}">Contact Support</a>
            </p>
        </div>
    </div>
</body>

</html>
