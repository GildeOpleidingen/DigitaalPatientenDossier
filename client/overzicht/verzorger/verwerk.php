<?php
session_start();
include_once '../../../database/DatabaseConnection.php';

if (!isset($_SESSION['loggedin_id'])) {
    return;
}

$id = $_SESSION['loggedin_id'];
$clientId = $_POST['clientId'];
if (!$clientId) {
    header("Location: ../verzorgers.php?id=$clientId");
    return;
}
$verzorgers = $_POST['verzorgers'];

foreach ($verzorgers as $key => $value) {
    if ($value == 'on') {
        $stmt = DatabaseConnection::getConn()->prepare("INSERT IGNORE INTO verzorgerregel (clientid, medewerkerid) VALUES (?, ?)");
        $stmt->bind_param("ii", $clientId, $key);
        $stmt->execute();
    } else {
        $stmt = DatabaseConnection::getConn()->prepare("DELETE FROM verzorgerregel WHERE clientid = ? AND medewerkerid = ?");
        $stmt->bind_param("ii", $clientId, $key);
        $stmt->execute();
    }
}

$_SESSION['verzorgersUpdated'] = true;
header("Location: ../verzorgers.php?id=$clientId");