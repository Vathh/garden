<?php

$db_host = "mysql_db";
$db_name = "garden";
$db_port = "3306";
$db_username = 'user';
$db_password = 'secret';
$conn = new PDO("mysql:host=$db_host;dbname=$db_name", $db_username, $db_password);

if(!$conn){
    die("Connection failed: " . mysqli_connect_error());
}
?>