<?php
include_once '../../../database/DatabaseConnection.php';
include_once '../../../models/autoload.php';

Auth::startSession();
Auth::requireAdmin();

$clientId = isset($_POST['clientId']) ? (int)$_POST['clientId'] : 0;
if ($clientId <= 0) {
    header("Location: ../../client.php");
    exit;
}

$verzorgers = $_POST['verzorgers'] ?? [];
$conn = DatabaseConnection::getConn();

// Prepared statements klaarzetten
$stmtKoppel = $conn->prepare("
    INSERT INTO verzorgerregel (clientid, medewerkerid, is_deleted)
    VALUES (?, ?, 0)
    ON DUPLICATE KEY UPDATE is_deleted = 0
");

$stmtOntkoppel = $conn->prepare("
    UPDATE verzorgerregel 
    SET is_deleted = 1 
    WHERE clientid = ? AND medewerkerid = ?
");

// Loop door verzorgers: 'on' = koppelen, anders = ontkoppelen
foreach ($verzorgers as $medewerkerId => $status) {
    $medewerkerId = (int)$medewerkerId;
    if ($medewerkerId <= 0) {
        continue;
    }

    if ($status === 'on') {
        $stmtKoppel->bind_param("ii", $clientId, $medewerkerId);
        $stmtKoppel->execute();
    } else {
        $stmtOntkoppel->bind_param("ii", $clientId, $medewerkerId);
        $stmtOntkoppel->execute();
    }
}

$stmtKoppel->close();
$stmtOntkoppel->close();

$_SESSION['succes'] = "Verzorgers zijn succesvol aangepast";
header("Location: ../../verzorgers/verzorgers.php?id=$clientId");
exit;
