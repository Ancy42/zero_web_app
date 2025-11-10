<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Payment Failed</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            font-family: 'Arial', sans-serif;
            background: linear-gradient(135deg, #f8d7da, #fefefe);
            margin: 0;
            padding: 0;
        }

        .payment-failed-page {
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            text-align: center;
        }

        .card {
            max-width: 500px;
            width: 100%;
            background: white;
            border: none;
            border-radius: 15px;
            padding: 30px;
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.1);
            animation: fadeInUp 0.8s ease-in-out;
        }

        .card img {
            width: 120px;
            margin-bottom: 20px;
        }

        .highlight-error {
            background-color: #f8eff0;
            color: #721c24;
            padding: 10px;
            border-radius: 5px;
            display: inline-block;
            margin-bottom: 20px;
        }

        .countdown {
            font-size: 18px;
            font-weight: bold;
            color: #d9534f;
            margin-top: 15px;
        }

        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
    </style>
</head>
<body>
    <div class="payment-failed-page">
        <div class="card">
            <img src="{{ asset('assets/images/payment-fail.svg') }}" alt="Payment Failed" class="mx-auto">
            <h3 class="text-danger">Payment Failed</h3>
            <div class="highlight-error">{{ $request->error }}</div>
            <p class="text-muted">Redirecting you back in <span id="countdown">10</span> seconds...</p>
        </div>
    </div>

    <script>
        // Countdown Timer Logic
        let countdownElement = document.getElementById('countdown');
        let countdownValue = 10;

        const interval = setInterval(() => {
            countdownValue--;
            countdownElement.textContent = countdownValue;

            if (countdownValue === 0) {
                clearInterval(interval);
                window.close();
            }
        }, 1000);
    </script>
</body>
</html>
