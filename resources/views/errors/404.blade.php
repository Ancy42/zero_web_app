<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>500 Error</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap"
        rel="stylesheet">
    <link rel="stylesheet" href="{{asset('assets/css/error.css')}}">
</head>

<body>
    <main class="flex justify-center items-center">
        <section>
            <div class="image">
                <img src="{{asset('assets/images/dino-404.svg')}}" alt="">
            </div>
            <div class="details">
                <p class="title">Page Not Found</p>
                <p class="description">The page you’re looking for might have been moved, deleted, or does not exist.
Please check the URL or return to the homepage.
                    <br>
                </p>
            </div>

            <a href="/" class="reload_btn" style="text-decoration: none">
                Return to Homepage
            </a>
        </section>
    </main>
</body>
</html>
