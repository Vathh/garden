<?php

$loginError = "";
if (empty($_POST["login"])) {
    $loginError = "Login jest wymagany.";
} else {
    $login = $_POST["login"];
    if (!preg_match("/^[a-zA-Z0-9]*$/", $login)) {
        $loginError = "Tylko litery i cyfry dozwolone.";
    }
}
