<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>503 Error</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap"
        rel="stylesheet">
    <link rel="stylesheet" href="{{asset('assets/css/error.css')}}">
</head>

<body>
    <main class="flex justify-center items-center">
        <section>
            <div class="image">
                <img src="{{asset('assets/images/dino-503.svg')}}" alt="">
            </div>
            <div class="details">
                <p class="title">Scheduled Maintenance</p>
                <p class="description">The cache is refreshing now. That's why this website is undergoing maintenance.
                    It will be up within
                    10 seconds automatically. If it's not up automatically, you can

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
