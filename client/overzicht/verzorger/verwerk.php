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
$pdo = DatabaseConnection::getPdo();

// Controleer of de kolom is_deleted bestaat in verzorgerregel
$hasSoftDelete = false;
try {
    $check = $pdo->query("SHOW COLUMNS FROM `verzorgerregel` LIKE 'is_deleted'");
    if ($check && $check->fetch()) {
        $hasSoftDelete = true;
    }
} catch (Throwable $e) {
    $hasSoftDelete = false;
}

if ($hasSoftDelete) {
    $stmtKoppel = $pdo->prepare("
        INSERT INTO verzorgerregel (clientid, medewerkerid, is_deleted)
        VALUES (:clientId, :medewerkerId, 0)
        ON DUPLICATE KEY UPDATE is_deleted = 0
    ");

    $stmtOntkoppel = $pdo->prepare("
        UPDATE verzorgerregel 
        SET is_deleted = 1 
        WHERE clientid = :clientId AND medewerkerid = :medewerkerId
    ");
} else {
    $stmtKoppel = $pdo->prepare("
        INSERT IGNORE INTO verzorgerregel (clientid, medewerkerid)
        VALUES (:clientId, :medewerkerId)
    ");

    $stmtOntkoppel = $pdo->prepare("
        DELETE FROM verzorgerregel 
        WHERE clientid = :clientId AND medewerkerid = :medewerkerId
    ");
}

// Loop door verzorgers: 'on' = koppelen, anders = ontkoppelen
foreach ($verzorgers as $medewerkerId => $status) {
    $medewerkerId = (int)$medewerkerId;
    if ($medewerkerId <= 0) {
        continue;
    }

    if ($status === 'on') {
        $stmtKoppel->execute([
            'clientId'     => $clientId,
            'medewerkerId' => $medewerkerId
        ]);
    } else {
        $stmtOntkoppel->execute([
            'clientId'     => $clientId,
            'medewerkerId' => $medewerkerId
        ]);
    }
}

$_SESSION['succes'] = "Verzorgers zijn succesvol aangepast";
header("Location: ../../verzorgers/verzorgers.php?id=$clientId");
exit;
