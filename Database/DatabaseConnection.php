<?php

include_once dirname(__FILE__) . "/../config.php";

class DatabaseConnection
{
    private static ?mysqli $conn = null;

    public static function getConn(): mysqli {
        self::checkConnection();
        return self::$conn;
    }

    private static function checkConnection(): void {
        if (self::$conn == null) {
            self::$conn = new mysqli(config::$host, config::$username, config::$pass, config::$db);
        }
    }
}
