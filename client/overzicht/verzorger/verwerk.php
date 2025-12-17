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
$conn = DatabaseConnection::getConn();

foreach ($verzorgers as $key => $value) {
    if ($value == 'on') {
        $stmt = $conn->prepare("INSERT IGNORE INTO verzorgerregel (clientid, medewerkerid) VALUES (?, ?)");
        $stmt->bind_param("ii", $clientId, $key);
        $stmt->execute();
    } else {
        $queries = [
            // "DELETE FROM rapport WHERE verzorgerregelid IN (SELECT id FROM verzorgerregel WHERE clientid = ? AND medewerkerid = ?)",
            // "DELETE FROM patroon01gezondheidsbeleving WHERE vragenlijstid IN (SELECT id FROM vragenlijst WHERE verzorgerregelid IN (SELECT id FROM verzorgerregel WHERE clientid = ? AND medewerkerid = ?))",
            // "DELETE FROM patroon02voedingstofwisseling WHERE vragenlijstid IN (SELECT id FROM vragenlijst WHERE verzorgerregelid IN (SELECT id FROM verzorgerregel WHERE clientid = ? AND medewerkerid = ?))",
            // "DELETE FROM patroon03uitscheiding WHERE vragenlijstid IN (SELECT id FROM vragenlijst WHERE verzorgerregelid IN (SELECT id FROM verzorgerregel WHERE clientid = ? AND medewerkerid = ?))",
            // "DELETE FROM patroon04activiteiten WHERE vragenlijstid IN (SELECT id FROM vragenlijst WHERE verzorgerregelid IN (SELECT id FROM verzorgerregel WHERE clientid = ? AND medewerkerid = ?))",
            // "DELETE FROM patroon05slaaprust WHERE vragenlijstid IN (SELECT id FROM vragenlijst WHERE verzorgerregelid IN (SELECT id FROM verzorgerregel WHERE clientid = ? AND medewerkerid = ?))",
            // "DELETE FROM patroon06cognitiewaarneming WHERE vragenlijstid IN (SELECT id FROM vragenlijst WHERE verzorgerregelid IN (SELECT id FROM verzorgerregel WHERE clientid = ? AND medewerkerid = ?))",
            // "DELETE FROM patroon07zelfbeleving WHERE vragenlijstid IN (SELECT id FROM vragenlijst WHERE verzorgerregelid IN (SELECT id FROM verzorgerregel WHERE clientid = ? AND medewerkerid = ?))",
            // "DELETE FROM patroon08rollenrelatie WHERE vragenlijstid IN (SELECT id FROM vragenlijst WHERE verzorgerregelid IN (SELECT id FROM verzorgerregel WHERE clientid = ? AND medewerkerid = ?))",
            // "DELETE FROM patroon09seksualiteitvoorplanting WHERE vragenlijstid IN (SELECT id FROM vragenlijst WHERE verzorgerregelid IN (SELECT id FROM verzorgerregel WHERE clientid = ? AND medewerkerid = ?))",
            // "DELETE FROM patroon10stressverwerking WHERE vragenlijstid IN (SELECT id FROM vragenlijst WHERE verzorgerregelid IN (SELECT id FROM verzorgerregel WHERE clientid = ? AND medewerkerid = ?))",
            // "DELETE FROM patroon11waardelevensovertuiging WHERE vragenlijstid IN (SELECT id FROM vragenlijst WHERE verzorgerregelid IN (SELECT id FROM verzorgerregel WHERE clientid = ? AND medewerkerid = ?))",
            // "DELETE FROM metingontlasting WHERE metingid IN (SELECT id FROM meting WHERE verzorgerregelid IN (SELECT id FROM verzorgerregel WHERE clientid = ? AND medewerkerid = ?))",
            // "DELETE FROM metingurine WHERE metingid IN (SELECT id FROM meting WHERE verzorgerregelid IN (SELECT id FROM verzorgerregel WHERE clientid = ? AND medewerkerid = ?))",
            // "DELETE FROM vragenlijst WHERE verzorgerregelid IN (SELECT id FROM verzorgerregel WHERE clientid = ? AND medewerkerid = ?)",
            // "DELETE FROM meting WHERE verzorgerregelid IN (SELECT id FROM verzorgerregel WHERE clientid = ? AND medewerkerid = ?)",
            "DELETE FROM verzorgerregel WHERE clientid = ? AND medewerkerid = ?"
        ];

        foreach ($queries as $query) {
            $stmt = $conn->prepare($query);
            $stmt->bind_param("ii", $clientId, $key);
            $stmt->execute();
        }
    }
}

$_SESSION['succes'] = "Verzorgers zijn succesvol aangepast";
header("Location: ../../verzorgers/verzorgers.php?id=$clientId");
exit;
