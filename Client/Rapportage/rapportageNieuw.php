<?php
session_start();
include_once '../../Database/DatabaseConnection.php';

$loggedInId = $_SESSION['loggedin_id'];

if ($loggedInId == null) {
    header("Location: ../index.php");
}

$clientId = $_GET['id'];
$_SESSION['clientId'] = $_GET['id'];

if(!isset($clientId)){
    header("Location: ../client.php");
}

$client = DatabaseConnection::getConn()->prepare("SELECT * FROM client WHERE id = ?");
$client->bind_param("i", $id);
$client->execute();
$client = $client->get_result()->fetch_assoc();

$verzorgerregel = DatabaseConnection::getConn()->prepare("SELECT * FROM verzorgerregel WHERE clientid = ? AND medewerkerid = ?");
$verzorgerregel->bind_param("ii", $clientId, $loggedInId);
$verzorgerregel->execute();
$verzorgerregel = $verzorgerregel->get_result()->fetch_assoc()['id'];

$tijd = date('Y-m-d H:i:s');
$rapport = "";
$rapportage = DatabaseConnection::getConn()->prepare("INSERT INTO rapport (verzorgerregelid, datumtijd, inhoud) VALUES (?, ?, ?)");
$rapportage->bind_param("iss", $verzorgerregel, $tijd, $rapport);
$rapportage->execute();
$rapportage = $rapportage->insert_id;

header("Location: rapportageAanpassen.php?id=" . $rapportage);