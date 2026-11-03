<?php

class DatabaseConnection
{
    private static ?mysqli $conn = null;
    private static ?PDO $pdo = null;

    private static function getEnv(): array
    {
        $env = [];
        $envFile = __DIR__ . '/../.env';

        if (file_exists($envFile)) {
            foreach (file($envFile, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES) as $line) {
                if (str_contains($line, '=') && !str_starts_with(trim($line), '#')) {
                    [$key, $val] = explode('=', $line, 2);
                    $env[trim($key)] = trim($val, " \t\n\r\0\x0B\"'");
                }
            }
        }
        return $env;
    }

    public static function getConn(): mysqli
    {
        if (self::$conn === null) {
            $env  = self::getEnv();
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

    public static function getPdo(): PDO
    {
        if (self::$pdo === null) {
            $env  = self::getEnv();
            $host = $env['DB_HOST'] ?? 'localhost';
            $port = $env['DB_PORT'] ?? 3306;
            $user = $env['DB_USER'] ?? 'root';
            $pass = $env['DB_PASS'] ?? '';
            $db   = $env['DB_NAME'] ?? 'dpd_dev';

            $dsn = "mysql:host={$host};port={$port};dbname={$db};charset=utf8mb4";
            $options = [
                PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::ATTR_EMULATE_PREPARES   => false,
            ];

            try {
                self::$pdo = new PDO($dsn, $user, $pass, $options);
            } catch (PDOException $e) {
                die("<h3>PDO Database verbinding mislukt</h3>" .
                    "<p>Fout: " . htmlspecialchars($e->getMessage()) . "</p>" .
                    "<p>Controleer je <code>.env</code> bestand.</p>");
            }
        }

        return self::$pdo;
    }
}
