<?php

class DatabaseConnection
{
    private static ?mysqli $conn = null;

    public static function getConn(): mysqli
    {
        if (self::$conn === null) {
            $env = [];
            $envFile = __DIR__ . '/../.env';

            // Lees .env bestand in
            if (file_exists($envFile)) {
                foreach (file($envFile, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES) as $line) {
                    if (str_contains($line, '=') && !str_starts_with(trim($line), '#')) {
                        [$key, $val] = explode('=', $line, 2);
                        $env[trim($key)] = trim($val, " \t\n\r\0\x0B\"'");
                    }
                }
            }

            $host = $env['DB_HOST'] ?? 'localhost';
            $user = $env['DB_USER'] ?? 'root';
            $pass = $env['DB_PASS'] ?? '';
            $db   = $env['DB_NAME'] ?? 'dpd_dev';

            try {
                self::$conn = new mysqli($host, $user, $pass, $db);
            } catch (mysqli_sql_exception $e) {
                die("<h3>Database verbinding mislukt</h3>" .
                    "<p>Kon geen verbinding maken met <strong>" . htmlspecialchars($host) . "</strong>.</p>" .
                    "<p>Controleer je <code>.env</code> bestand.</p>");
            }
        }

        return self::$conn;
    }
}
