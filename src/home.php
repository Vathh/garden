<?php
session_start();

    if(!isset($_SESSION['user_id'])){
        header("Location: loginPanel.php");
        exit;
    }

    if(!(isset($_SESSION['last_activity']) && (time() - $_SESSION['last_activity'] < 10))){
        session_unset();
        session_destroy();
        header("Location: loginPanel.php?msg=inactive");
        exit;
    }

    $_SESSION['last_activity'] = time();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>My Website</title>
    <link rel="stylesheet" href="./style.css">
    <link rel="icon" href="./favicon.ico" type="image/x-icon">
    <script src="inactiveLogout.js"></script>
</head>
<body>
<main>
    <h1>Witaj!</h1>

    <form action="changePasswordForm.php" method="GET" style="display:inline;">
        <button type="submit">Zmień hasło</button>
    </form>

    <form action="logout.php" method="POST" style="display:inline;">
        <button type="submit">Wyloguj się</button>
    </form>
</main>
</body>
</html>