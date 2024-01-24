<?php
session_start();
include_once '../../../Database/DatabaseConnection.php';

if (!isset($_SESSION['loggedin_id'])) {
    return;
}

$id = $_SESSION['loggedin_id'];
$clientId = $_POST['clientId'];
$omtoeTeVoegen = $_POST['medewerkerId'];

if (!$omtoeTeVoegen) {
    header("Location: ../overzicht.php?id=$clientId");
    return;
}

$result = DatabaseConnection::getConn()->prepare("INSERT INTO verzorgerregel (medewerkerid, clientid) VALUES (?, ?)");
$result->bind_param("ii", $omtoeTeVoegen, $clientId);
$result->execute();

header("Location: ../overzicht.php?id=$clientId");
?>