<?php
session_start();
include_once '../../database/DatabaseConnection.php';
include_once '../../classes/Main.php';
$Main = new Main();

$loggedInId = $_SESSION['loggedin_id'];

if ($loggedInId == null) {
    header("Location: ../index.php");
    exit();
}

$clientId = $_SESSION['clientId'];

if (!isset($clientId)) {
    header("Location: ../client.php");
    exit();
}

$client = $_SESSION['client'] = $Main->getClientById($clientId);

$verzorgerregel = DatabaseConnection::getConn()->prepare("SELECT * FROM verzorgerregel WHERE clientid = ? AND medewerkerid = ?");
$verzorgerregel->bind_param("ii", $clientId, $loggedInId);
$verzorgerregel->execute();
$verzorgerregel = $verzorgerregel->get_result()->fetch_assoc();
if ($verzorgerregel == null) {
    header("Location: ../overzicht/overzicht.php");
    exit();
}

$rapportageId = $_GET['id']; // Het rapportage ID ophalen uit de URL-parameter

// Verwijderen van de rapportage
$deleteRapportage = DatabaseConnection::getConn()->prepare("DELETE FROM rapport WHERE id = ?");
$deleteRapportage->bind_param("i", $rapportageId);
$deleteRapportage->execute();

header("Location: ../rapportage/rapportage.php"); // Terugsturen naar het overzicht
exit();
?>
