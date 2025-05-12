<?php
session_start();
require_once 'conn.php';

if(!isset($_SESSION['user_id'])){
    header("Location: loginPanel.php");
    exit;
}

$userId = $_SESSION['user_id'];
$oldPassword = $_POST['old_password'];
$newPassword = $_POST['new_password'];
$confirmPassword = $_POST['confirm_password'];

if($newPassword !== $confirmPassword){
    die("Nowe hasła różnią się.");
}

try{
    $stmt = $conn->prepare("SELECT password FROM users WHERE user_id = ?");
    $stmt->execute([$userId]);
    $user=$stmt->fetch();

    if(!$user || !password_verify($oldPassword, $user['password'])){
        die("Nieprawidłowe stare hasło.");
    }

    $stmt = $conn->prepare("UPDATE users SET password = ? WHERE user_id = ?");
    $stmt->execute([$newPassword, $userId]);

    echo "Hasło zostało zmienione";
} catch (PDOException $e) {
    echo "Błąd: " . $e->getMessage();
}