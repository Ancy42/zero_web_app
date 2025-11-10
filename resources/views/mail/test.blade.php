<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Test Email</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
        }

        .email-container {
            max-width: 600px;
            margin: 20px auto;
            background: #ffffff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }

        .header {
            background: #007bff;
            color: white;
            text-align: center;
            padding: 15px;
            font-size: 22px;
            font-weight: bold;
            border-radius: 8px 8px 0 0;
        }

        .content {
            padding: 20px;
            color: #333;
            line-height: 1.6;
        }

        .footer {
            text-align: center;
            padding: 15px;
            font-size: 14px;
            color: #777;
        }

        .btn {
            display: inline-block;
            padding: 10px 20px;
            margin-top: 15px;
            background: #007bff;
            color: white;
            text-decoration: none;
            border-radius: 5px;
        }

        .btn:hover {
            background: #0056b3;
        }

        .message-content {
            background-color: #f0f0f0;
            padding: 10px;
            border-radius: 5px;
            font-size: 16px;
        }
    </style>
</head>

<body>

    <div class="email-container">
        <div class="header">
            Test Email - Confirmation
        </div>

        <div class="content">
            <p>Dear Admin,</p>
            <p>Well done! Your mail configuration is perfect. You have successfully received this test email.</p>

            <p><strong>Test Details:</strong></p>
            <ul>
                <li><strong>Sender:</strong> from test</li>
                <li><strong>Recipient:</strong> {{ $email }}</li>
                <li><strong>Date & Time:</strong> {{ now()->format('F d, Y h:i A') }}</li>
                <li><strong>Subject:</strong> Test Email</li>
            </ul>

            <p><strong>Test Message:</strong></p>
            <p class="message-content">{{ $messageContent }}</p>

            <p>If you have any questions or need further assistance, please don't hesitate to contact our support team.
            </p>
        </div>

        <div class="footer">
            &copy; {{ date('Y') }} {{ config('app.name') }}. All rights reserved.
        </div>
    </div>

</body>

</html>
