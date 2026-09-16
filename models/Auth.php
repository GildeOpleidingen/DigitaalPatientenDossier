<?php

class Auth
{
    public static function startSession(): void
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
    }

    public static function id(): ?int
    {
        self::startSession();
        return isset($_SESSION['loggedin_id']) ? (int)$_SESSION['loggedin_id'] : null;
    }

        public static function requireLogin(): void
        {
            if (!self::id()) {
                header("Location: /index.php");
                exit;
            }
        }

    public static function isAdmin(): bool
    {
        return self::id() !== null && ($_SESSION['rol'] ?? '') === 'beheerder';
    }
    // extra functie waar we niet aan hadden gedacht als een student naar http://digitaalpatientdosier/client/client_bewerken.php?id=1 gaat zonder deze funcite hebben ze toegang om patiente stam gegevens te bewerken wat we niet willen
    public static function requireAdmin(string $redirect = '/client/client.php'): void
    {
        self::requireLogin();
        if (!self::isAdmin()) {
            header("Location: " . $redirect);
            exit;
        }
    }

    public static function user(): ?array
    {
        if (!self::id()) {
            return null;
        }

        return [
            'id'      => self::id(),
            'naam'    => $_SESSION['loggedin_naam'] ?? null,
            'rol'     => $_SESSION['rol'] ?? null,
            'isAdmin' => self::isAdmin(),
        ];
    }
}