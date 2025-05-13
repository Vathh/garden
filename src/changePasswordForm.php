<?php
session_start();
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
    <script src="registerValidation.js" defer></script>
</head>
<body>
<main>
    <form name="changePasswordForm" class="form" action="change_password.php" method="POST">
        <div class="form__container">
            <h1 class="form__container-header">Change Password</h1>

            <label class="form__container-label" for="old_password"><b>Old Password</b></label>
            <input class="form__container-input" type="password" placeholder="Enter Old Password" name="old_password" id="old_password" required>

            <label class="form__container-label" for="new_password"><b>New Password</b></label>
            <input class="form__container-input" type="password" placeholder="Enter New Password" name="new_password" id="new_password" required>

            <label class="form__container-label" for="confirm_password"><b>Confirm New Password</b></label>
            <input class="form__container-input" type="password" placeholder="Confirm New Password" name="confirm_password" id="confirm_password" required>

            <button class="form__container-btn" type="submit" name="change_password">Submit</button>
        </div>
    </form>
</main>
</body>
</html>