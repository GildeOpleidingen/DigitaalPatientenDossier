<?php
session_start();
include_once '../../../Database/DatabaseConnection.php';

if (!isset($_SESSION['loggedin_id'])) {
    return;
}

$id = $_SESSION['loggedin_id'];
$clientId = $_POST['clientId'];
$omtoeTeVoegen = $_POST['medewerkerId'];

$result = DatabaseConnection::getConn()->prepare("INSERT INTO verzorgerregel (medewerkerid, clientid) VALUES (?, ?)");
$result->bind_param("ss", $omtoeTeVoegen, $clientId);
$result->execute();

header("Location: ../overzicht.php?id=$clientId");
?>
