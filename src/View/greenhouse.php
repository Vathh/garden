<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Greenhouse</title>
    <link rel="stylesheet" href="/css/style.css">
    <link rel="icon" href="./favicon.ico" type="image/x-icon">
</head>
<body>
<main>
    <?php include __DIR__ . "/partials/nav.php"; ?>
    <div class="container">
        <div class="facade">
            <h1>Szklarnia</h1>
            <div class="facade__img"></div>
        </div>
        <div class="monitoring">
            <h2 class="title">Monitoring</h2>
            <div class="monitoring__temps">
                <div class="monitoring__temps-field">
                    <span class="monitoring__desc"></span>
                    <span class="monitoring__value"></span>
                </div>
                <div class="monitoring__temps-field">
                    <span class="monitoring__desc"></span>
                    <span class="monitoring__value"></span>
                </div>
            </div>
            <div class="monitoring__door">
                <span class="monitoring__desc"></span>
                <span class="monitoring__value"></span>
            </div>
        </div>
        <div class="controls">
            <h2 class="title">Sterowanie</h2>

        </div>
    </div>
</main>
</body>
</html>