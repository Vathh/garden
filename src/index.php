<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>My Website</title>
    <link rel="stylesheet" href="./style.css">
    <link rel="icon" href="./favicon.ico" type="image/x-icon">
</head>
<body>
<main>
    <form class="form"  action="register.php">
        <div class="form__container">
            <h1 class="form__container-header">Register</h1>

            <label class="form__container-label" for="login"><b>Login</b></label>
            <input class="form__container-input" type="text" placeholder="Enter Login" name="login" id="login" required>

            <label class="form__container-label" for="email"><b>Email</b></label>
            <input class="form__container-input" type="text" placeholder="Enter Email" name="email" id="email" required>

            <label class="form__container-label" for="psw"><b>Password</b></label>
            <input class="form__container-input" type="password" placeholder="Enter Password" name="psw" id="psw" required>

            <label class="form__container-label" for="psw-repeat"><b>Repeat Password</b></label>
            <input class="form__container-input" type="password" placeholder="Repeat Password" name="psw-repeat" id="psw-repeat" required>

            <button class="form__container-btn" type="submit">Register</button>
        </div>
    </form>
</main>
</body>
</html>
