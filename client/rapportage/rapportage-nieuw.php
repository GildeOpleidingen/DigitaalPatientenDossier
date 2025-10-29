<?php
session_start();
include_once '../../database/DatabaseConnection.php';
include_once '../../models/autoload.php';

$Main = new Main();

// Controleer of de gebruiker is ingelogd
$loggedInId = $_SESSION['loggedin_id'] ?? null;
if (!$loggedInId) {
    header("Location: ../index.php");
    exit;
}

// Controleer of er een client is geselecteerd
$clientId = $_SESSION['clientId'] ?? null;
if (!$clientId) {
    header("Location: ../client.php");
    exit;
    exit;
}

// Haal clientgegevens op
$client = $_SESSION['client'] = $Main->getClientById($clientId);
if (!$client) {
    header("Location: ../client.php");
    exit;
}

// Controleer of er een verzorgerregel bestaat voor deze client en medewerker
$conn = DatabaseConnection::getConn();
$stmt = $conn->prepare(
    "SELECT id FROM verzorgerregel WHERE clientid = ? AND medewerkerid = ?"
);
$stmt->bind_param("ii", $clientId, $loggedInId);
$stmt->execute();
$result = $stmt->get_result();

// Als er geen verzorgerregel is, maak er een aan
if ($result->num_rows > 0) {
    $verzorgerregelId = $result->fetch_assoc()['id'];
} else {
    $stmtInsert = $conn->prepare(
        "INSERT INTO verzorgerregel (clientid, medewerkerid, toegang) VALUES (?, ?,0)"
    );
    $stmtInsert->bind_param("ii", $clientId, $loggedInId);
    $stmtInsert->execute();
    $verzorgerregelId = $stmtInsert->insert_id;
}

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
