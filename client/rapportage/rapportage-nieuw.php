<?php
include_once '../../database/DatabaseConnection.php';
include_once '../../models/autoload.php';

Auth::startSession();
Auth::requireLogin();
$loggedInId = Auth::id();

$Main = new Main();
$ClientModel = new ClientModel();

// Controleer of er een client is geselecteerd (via GET of SESSION)
$clientId = isset($_GET['id']) ? (int)$_GET['id'] : ($_SESSION['clientId'] ?? null);
if (!$clientId) {
    header("Location: ../client.php");
    exit;
}
$_SESSION['clientId'] = $clientId;

// Haal clientgegevens op
$client = $_SESSION['client'] = $ClientModel->getClientById($clientId);
if (!$client) {
    header("Location: ../client.php");
    exit;
}

// Zorg dat er een actieve verzorgerregel bestaat voor deze client en medewerker
$conn = DatabaseConnection::getConn();
$stmt = $conn->prepare("
    INSERT INTO verzorgerregel (clientid, medewerkerid, is_deleted)
    VALUES (?, ?, 0)
    ON DUPLICATE KEY UPDATE is_deleted = 0
");
$stmt->bind_param("ii", $clientId, $loggedInId);
$stmt->execute();
$stmt->close();

$stmtId = $conn->prepare("SELECT id FROM verzorgerregel WHERE clientid = ? AND medewerkerid = ? LIMIT 1");
$stmtId->bind_param("ii", $clientId, $loggedInId);
$stmtId->execute();
$verzorgerregelId = (int)$stmtId->get_result()->fetch_assoc()['id'];
$stmtId->close();
// Maak een nieuwe lege rapportage aan
$tijd = date('Y-m-d H:i:s');
$rapport = "";
$stmtRapport = $conn->prepare(
    "INSERT INTO rapport (verzorgerregelid, datumtijd, inhoud) VALUES (?, ?, ?)"
);
$stmtRapport->bind_param("iss", $verzorgerregelId, $tijd, $rapport);
$stmtRapport->execute();
$rapportId = $stmtRapport->insert_id;

// Redirect naar de pagina om de rapportage aan te passen
header("Location: rapportage-aanpassen.php?id=" . $rapportId);
exit;
