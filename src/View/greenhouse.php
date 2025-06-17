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
    <div class="container">
        <div class="subcontainer">
            <div class="subcontainer__row">
                <p class="subcontainer__row-title">Temperatura wewnątrz</p>
                <span class="subcontainer__row-value">45.1</span>
            </div>
            <div class="subcontainer__row">
                <p class="subcontainer__row-title">Temperatura na zewnątrz</p>
                <span class="subcontainer__row-value">21.6</span>
            </div>
        </div>
        <form action="/zones" method="GET" class="container__button">
            <button type="submit" class="container__button-btn">Wstecz</button>
        </form>
    </div>
</main>
</body>
</html>
