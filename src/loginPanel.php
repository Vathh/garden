<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>My Website</title>
    <link rel="stylesheet" href="./style.css">
    <link rel="icon" href="./favicon.ico" type="image/x-icon">
    <script src="registerValidation.js" defer></script>
</head>
<body>
<main>
    <?php require_once "loginPanel.php" ?>
    <form name="loginForm" class="form" action="loginPanel.php" method="POST">
        <div class="form__container">
            <h1 class="form__container-header">Sign In</h1>

            <label class="form__container-label" for="login"><b>Login</b></label>
            <input class="form__container-input" type="text" placeholder="Enter Login" name="login" id="login" required>

            <label class="form__container-label" for="password"><b>Password</b></label>
            <input class="form__container-input" type="password" placeholder="Enter Password" name="password" id="password" required>

            <button class="form__container-btn" type="submit" name="register">Register</button>
        </div>
    </form>
</main>
</body>
</html>