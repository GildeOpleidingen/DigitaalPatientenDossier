<?php
session_start();
require_once __DIR__ . '/../includes/auth.php';

// Alleen beheerders mogen medewerkers verwijderen
Auth::requireAdmin('medewerker.php');

if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $id = (int) $_GET['id'];

    // Voorkom dat de beheerder zichzelf verwijdert
    if ($id === (int) $_SESSION['loggedin_id']) {
        $_SESSION['error'] = "Je kunt je eigen account niet verwijderen.";
        header('Location: medewerker.php');
        exit;
    }

    require_once __DIR__ . '/../database/DatabaseConnection.php';
    require_once __DIR__ . '/../models/autoload.php';

    if (MedewerkerModel::delete($id)) {
        $_SESSION['success'] = "Medewerker is succesvol verwijderd.";
    } else {
        $_SESSION['error'] = "Er is een fout opgetreden bij het verwijderen van de medewerker.";
    }
} else {
    $_SESSION['error'] = "Geen geldig medewerker ID opgegeven.";
}

header('Location: medewerker.php');
exit;

