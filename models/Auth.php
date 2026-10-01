<?php

class Auth
{
    /**
     * @return void
     */
    public static function startSession(): void
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
    }

    /**
     * @return int|null
     */
    public static function id(): ?int
    {
        self::startSession();
        return isset($_SESSION['loggedin_id']) ? (int)$_SESSION['loggedin_id'] : null;
    }

    /**
     * @return void
     */
    public static function requireLogin(): void
    {
        if (!self::id()) {
            header("Location: /index.php");
            exit;
        }
    }

    /**
     * @return bool
     */
    public static function isAdmin(): bool
    {
        return self::id() !== null && ($_SESSION['rol'] ?? '') === 'beheerder';
    }

    /**
     * @param string $redirect
     * @return void
     */
    public static function requireAdmin(string $redirect = '/client/client.php'): void
    {
        self::requireLogin();
        if (!self::isAdmin()) {
            header("Location: " . $redirect);
            exit;
        }
    }

    /**
     * @return array|null
     */
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