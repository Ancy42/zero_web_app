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
                <img src="{{asset('assets/images/dino-500.svg')}}" alt="">
            </div>
            <div class="details">
                <p class="title">Something Went Wrong</p>
                <p class="description">We're experiencing an unexpected issue on our server.
                <br>Don't worry — we're already working to fix it.
                <br><br>
                The page will try to reload automatically in a seconds.
                If it doesn’t, you can manually
                    <br>
                    <span>
                        Click the Reload Button
                    </span>
                </p>
            </div>

            <button class="reload_btn">
                Reload
            </button>
        </section>
    </main>
</body>
<script>
  setInterval(function() {
    location.reload();
  }, 5000);
</script>
</html>
