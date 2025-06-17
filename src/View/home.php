<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Home</title>
    <link rel="stylesheet" href="/css/style.css">
    <link rel="icon" href="./favicon.ico" type="image/x-icon">
    <script src="/js/inactiveLogout.js"></script>
</head>
<body>
<main>
    <div class="container">
        <h1>Witaj!</h1>

        <form action="/zones" method="GET" class="container__button">
            <button type="submit" class="container__button-btn">Strefy</button>
        </form>

        <form action="/changePassword" method="GET" class="container__button">
            <button type="submit" class="container__button-btn">Zmień hasło</button>
        </form>

        <form action="/logout" method="POST" class="container__button">
            <button type="submit" class="container__button-btn">Wyloguj się</button>
        </form>
    </div>
</main>
</body>
</html>
