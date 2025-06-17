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
    <form name="registerForm" class="form" action="/register" method="POST">
        <div class="form__container">
            <h1 class="form__container-header">Register</h1>

            <label class="form__container-label" for="login"><b>Login</b></label>
            <input class="form__container-input" type="text" placeholder="Enter Login" name="login" id="login" required>

            <label class="form__container-label" for="email"><b>Email</b></label>
            <input class="form__container-input" type="text" placeholder="Enter Email" name="email" id="email" required>

            <label class="form__container-label" for="password"><b>Password</b></label>
            <input class="form__container-input" type="password" placeholder="Enter Password" name="password" id="password" required>

            <label class="form__container-label" for="passwordRepeat"><b>Repeat Password</b></label>
            <input class="form__container-input" type="password" placeholder="Repeat Password" name="passwordRepeat" id="passwordRepeat" required>

            <button class="form__container-btn" type="submit" name="register">Log In</button>
        </div>
    </form>
</main>
</body>
</html>
