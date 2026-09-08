<?php

if (file_exists(dirname(__FILE__) . "/../config.php")) {
    include_once dirname(__FILE__) . "/../config.php";
} elseif (file_exists(dirname(__FILE__) . "/../../config.php")) {
    include_once dirname(__FILE__) . "/../../config.php";
}
else{
    echo "geen config gevonden";
}

class DatabaseConnection
{
    private static ?mysqli $conn = null;

    public static function getConn(): mysqli {
        self::checkConnection();
        return self::$conn;
    }

    private static function checkConnection(): void {
        if (self::$conn == null) {
            try {
                self::$conn = new mysqli(config::$host, config::$username, config::$pass, config::$db);
            } catch (mysqli_sql_exception $e) {
                die("<h3>Database verbinding mislukt</h3>" .
                    "<p>Kon geen verbinding maken met de database op <strong>" . htmlspecialchars(config::$host) . "</strong>.</p>" .
                    "<p><strong>Foutmelding:</strong> " . htmlspecialchars($e->getMessage()) . "</p>" .
                    "<p>Als je niet verbonden bent met het schoolnetwerk (Gilde 1.09 wifi of school-VPN), is het IP-adres <code>" . htmlspecialchars(config::$host) . "</code> niet bereikbaar. " .
                    "Gebruik in dat geval een lokale database (bijv. <code>localhost</code>) en importeer de tabellen uit <code>SQLscripts/</code>.</p>");
            }
        }
    }
}
