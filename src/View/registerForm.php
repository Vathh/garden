<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Register</title>
    <link rel="stylesheet" href="/css/style.css">
    <link rel="icon" href="./favicon.ico" type="image/x-icon">
    <script src="/js/registerValidation.js" defer></script>
</head>
<body>
<main>
    <div class="container">
        <form name="registerForm" class="loginForm" action="/register" method="POST">
            <div class="loginForm__container">
                <h1 class="loginForm__container-header">Register</h1>

                <label class="loginForm__container-label" for="login"><b>Login</b></label>
                <input class="loginForm__container-input" type="text" placeholder="Wprowadź login" name="login" id="login" required>

                <label class="loginForm__container-label" for="email"><b>Email</b></label>
                <input class="loginForm__container-input" type="text" placeholder="Wprowadź adres e-mail" name="email" id="email" required>

                <label class="loginForm__container-label" for="password"><b>Hasło</b></label>
                <input class="loginForm__container-input" type="password" placeholder="Wprowadź hasło" name="password" id="password" required>

                <label class="loginForm__container-label" for="passwordRepeat"><b>Powtórz hasło</b></label>
                <input class="loginForm__container-input" type="password" placeholder="Powtórz hasło" name="passwordRepeat" id="passwordRepeat" required>

                <button class="loginForm__container-btn" type="submit" name="register">Log In</button>
            </div>
        </form>
    </div>
</main>
</body>
</html>
