<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Log In</title>
    <link rel="stylesheet" href="/css/style.css">
    <link rel="icon" href="./favicon.ico" type="image/x-icon">
</head>
<body>
<main>
    <?php include __DIR__ . "/partials/nav.php"; ?>
    <div class="container">
        <form name="loginForm" class="loginForm" action="/login" method="POST">
            <div class="loginForm__container">
                <h1 class="loginForm__container-header">Zaloguj się</h1>

                <label class="loginForm__container-label" for="login"><b>Login</b></label>
                <input class="loginForm__container-input" type="text" placeholder="Wprowadź login" name="login" id="login" required>

                <label class="loginForm__container-label" for="password"><b>Hasło</b></label>
                <input class="loginForm__container-input" type="password" placeholder="Wprowadź hasło" name="password" id="password" required>

                <button class="loginForm__container-btn" type="submit" name="loginBtn">Potwierdź</button>
            </div>
        </form>
        <p class="loginForm__question">Nie masz jeszcze konta?</p>
        <a class="loginForm__link" href="/register">Zarejestruj się</a>
    </div>
</main>
</body>
</html>