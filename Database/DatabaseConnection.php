<?php

class DatabaseConnection
{
    private static string $host = "localhost";
    private static string $username = "root";
    private static string $pass = "";
    private static string $db = "dpd";
    private static ?mysqli $conn = null;

    public static function getConn(): mysqli {
        self::checkConnection();
        return self::$conn;
    }

    private static function checkConnection(): void {
        if (self::$conn == null) {
            self::$conn = new mysqli(self::$host, self::$username, self::$pass, self::$db);
        }
    }
}