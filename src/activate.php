<?php
require_once 'conn.php';

$token = $_GET['token'] ?? '';

if($token){
    $stmt = $conn->prepare("SELECT * FROM users WHERE token = :token");
    $stmt->execute(array(":token" => $token));
    $user = $stmt->fetch();

    if($user && !$user['confirmed']){
        $stmt = $conn->prepare("UPDATE users SET confirmed = 1, activation_token = null WHERE token = :token");
        $stmt->execute(array(":token" => $token));
        echo "Konto zostało aktywowane.";
    }else{
        echo "Nieprawidłowy token lub konto zostało juz aktywowane.";
    }
} else {
    echo "Brak tokena aktywacyjnego.";
}