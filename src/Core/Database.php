<?php

namespace App\Core;

use PDO;
use PDOException;

class Database
{
    private static $instance = null;
    private $connection;

    private function __construct()
    {
        $db_host = "mysql_db";
        $db_name = "garden";
        $db_port = "3306";
        $db_username = 'user';
        $db_password = 'secret';

        try {
            $this->connection = new PDO(
                "mysql:host=$db_host;
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
