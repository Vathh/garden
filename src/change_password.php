<?php
session_start();
require_once 'conn.php';

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

$userId = $_SESSION['user_id'];
$oldPassword = $_POST['old_password'];
$newPassword = $_POST['new_password'];
$confirmPassword = $_POST['confirm_password'];

if($newPassword !== $confirmPassword){
    die("Nowe hasła różnią się.");
}

try{
    $stmt = $conn->prepare("SELECT password FROM users WHERE id = ?");
    $stmt->execute([$userId]);
    $user=$stmt->fetch();

    if(!$user || $oldPassword !== $user['password']){
        die("Nieprawidłowe stare hasło.");
    }

    $stmt = $conn->prepare("UPDATE users SET password = ? WHERE id = ?");
    $stmt->execute([$newPassword, $userId]);

    session_unset();
    session_destroy();

    header("Location: loginPanel.php?msg=password_changed");
    exit;
} catch (PDOException $e) {
    echo "Błąd: " . $e->getMessage();
}