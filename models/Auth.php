<?php

class Auth
{
 
    public static function startSession(): void
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
    }

   public static function requireLogin(): void
   {
    try {
     self::startsession();
     if (!isset($_SESSION['loggedin_id'])){
        self::redirectToLogin();
     }
    } catch (Throwable $e) {
        error_log("Auth::requireLogin fout: " . $e->getMessage());
        self::redirectToLogin();
    }
   }

   public static function isAdmin(): bool
   {
    try {
        self::startSession();
        return isset ($_SESSION['loggedin_id']) && $_SESSION['rol'] === 'beheerder';
    } catch (Throwable $e) {
        error_log("Auth::isAdmin fout: " . $e->getMessage());
        return false;
    }
   }
 
}