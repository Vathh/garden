<?php
    require_once "conn.php";

    $login = $_POST["login"];
    $password = $_POST["password"];

    $stmt = $conn->prepare("SELECT * FROM users WHERE login = ?");
    $stmt->execute([$login]);
    $user = $stmt->fetch();

    if($user && password_verify($password, $user["password"])){
        if(!$user['confirmed']){
            echo "Konto nieaktywne. Sprawdź pocztę.";
            exit;
        }

        $_SESSION['user_id'] = $user['id'];
        $_SESSION['last_activity'] = time();

        header("Location: home.php");
        exit;
    }else {
        echo "Nieprawidłowy login lub hasło.";
    }