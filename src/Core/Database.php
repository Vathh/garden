<?php

namespace App\Core;

use PDO;
use PDOException;

class Database
{
    private static ?Database $instance = null;
    private PDO $connection;

    private function __construct()
    {
        $db_host = "127.0.0.1";
        $db_name = "garden";
        $db_port = "3306";
        $db_username = 'root';
        $db_password = '';

        try {
            $this->connection = new PDO(
                "mysql:host=$db_host;
                port=$db_port;
                dbname=$db_name",
                $db_username,
                $db_password
            );
        } catch (PDOException $e) {
            die("Połączenie nieudane: " . mysqli_connect_error());
        }
    }

    public static function getInstance()
    {
        if (is_null(self::$instance)) {
            self::$instance = new Database();
        }
        return self::$instance;
    }

    public function getConnection() : PDO
    {
        return $this->connection;
    }
}
