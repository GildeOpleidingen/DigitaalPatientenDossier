<?php
session_start();
include '../../includes/auth.php';
include_once '../../database/DatabaseConnection.php';
include_once '../../models/autoload.php';

$Main = new Main();
$ClientModel = new ClientModel();

// Controleer of de gebruiker is ingelogd
$loggedInId = Auth::id() ?? $_SESSION['loggedin_id'] ?? null;
if (!$loggedInId) {
    header("Location: /index.php");
    exit;
}

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

// Controleer of er een verzorgerregel bestaat voor deze client en medewerker
$conn = DatabaseConnection::getConn();
$stmt = $conn->prepare(
    "SELECT id FROM verzorgerregel WHERE clientid = ? AND medewerkerid = ? LIMIT 1"
);
$stmt->bind_param("ii", $clientId, $loggedInId);
$stmt->execute();
$result = $stmt->get_result();
$row = $result ? $result->fetch_assoc() : null;

if ($row) {
    $verzorgerregelId = (int)$row['id'];
} else {
    // Koppel medewerker automatisch als verzorger als die nog niet bestaat
    $insertStmt = $conn->prepare(
        "INSERT INTO verzorgerregel (clientid, medewerkerid) VALUES (?, ?)"
    );
    $insertStmt->bind_param("ii", $clientId, $loggedInId);
    $insertStmt->execute();
    $verzorgerregelId = (int)$insertStmt->insert_id;
    $insertStmt->close();
}
$stmt->close();
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
