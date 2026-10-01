<?php

/**
 * Centrale Auth klasse voor sessiebeheer, inlogcontrole en rollen.
 * Voorkomt breekbare relatieve paden.
 */
class Auth
{
    /**
     * Start de sessie veilig als deze nog niet gestart is.
     */
    public static function startSession(): void
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
    }

    /**
     * Haalt het ID van de ingelogde gebruiker op.
     * Geeft null terug als de gebruiker niet is ingelogd.
     */
    public static function id(): ?int
    {
        self::startSession();
        return isset($_SESSION['loggedin_id']) ? (int)$_SESSION['loggedin_id'] : null;
    }

    /**
     * Controleert of de gebruiker is ingelogd.
     * Stuurt niet-ingelogde bezoekers direct door naar het inlogscherm /index.php ipv de echte website of een error.
     */
    public static function requireLogin(): void
    {
        if (!self::id()) {
            header("Location: /index.php");
            exit;
        }
    }

    /**
     * Controleert of de ingelogde gebruiker de rol 'beheerder' heeft.
     * Geeft true terug voor beheerders en false voor studenten/medewerkers of niet-ingelogden.
     */
    public static function isAdmin(): bool
    {
        return self::id() !== null && ($_SESSION['rol'] ?? '') === 'beheerder';
    }

    /**
     * Controleert of iemand is ingelogd én beheerder is.
     * Voorkomt dat studenten via directe URL's patiënten kunnen bewerken.
     * Niet-beheerders worden doorgestuurd naar het overzicht.
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
     * Haalt een array op met de gegevens van de huidige ingelogde gebruiker.
     * Geeft null terug als er niemand is ingelogd.
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