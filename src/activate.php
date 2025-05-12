<?php
require_once 'conn.php';

$token = $_GET['token'] ?? '';

if($token){
    $stmt = $conn->prepare("SELECT * FROM users WHERE activation_token = ?");
    $stmt->execute([$token]);
    $user = $stmt->fetch();

    if($user && !$user['confirmed']){
        $createdAt = new DateTime($user('activation_token_created_at'));
        $now = new DateTime();
        $interval = $createdAt->diff($now);

        if($interval->days > 15){
            $stmt = $conn->prepare("DELETE FROM users WHERE activation_token = ?");
            $stmt->execute([$token]);
            die("Link do aktywacji wygasł.");
        }

        $stmt = $conn->prepare("UPDATE users SET confirmed = 1, activation_token = null WHERE activation_token = ?");
        $stmt->execute([$token]);
        echo "Konto zostało aktywowane.";
    }else{
        echo "Nieprawidłowy token lub konto zostało juz aktywowane.";
    }
} else {
    echo "Brak tokena aktywacyjnego.";
}