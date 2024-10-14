<?php
session_start();
include_once '../../database/DatabaseConnection.php';
include_once '../../classes/Main.php';
$Main = new Main();

$clientId = $_SESSION['clientId'];

$client = $_SESSION['client'] = $Main->getClientById($clientId);
$rapportages = DatabaseConnection::getConn()->prepare("SELECT r.*, vr.id AS verzorgerregel_id FROM rapport r LEFT JOIN verzorgerregel vr ON r.verzorgerregelid = vr.id WHERE vr.clientid = ?;");
$rapportages->bind_param("i", $clientId);
$rapportages->execute();
$rapportages = $rapportages->get_result()->fetch_all(MYSQLI_ASSOC);
if ($client == null) {
    header("Location: ../client.php");
}

include '../../includes/n-header.php';
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="Stylesheet" href="../../assets/css/client/rapportage.css">
    <link rel="stylesheet" href="../../assets/css/bootstrap.min.css">
    <title>Rapportage van <?= $client['naam'] ?></title>
</head>

<body>
    <div class="main">

        <?php
        include '../../includes/n-sidebar.php';
        ?>

        <div class="content">
            <div class="mt-4 mb-3 bg-white p-3" style="height: 96%; overflow: auto;">
                <p class="card-text">
                <h1>Rapportages</h1>
                <a href='rapportage-nieuw.php?id=<?= $clientId ?>' class='btn btn-primary'>Nieuwe rapportage</a>
                <?php
                foreach ($rapportages as $rapport) {
                    echo "<h1>Rapportage van " . $rapport['titel_rapport'] . "   " .$rapport['datumtijd'] . "</h1>";
                    
                    echo "<a href='rapportage-aanpassen.php?id=" . $rapport['id'] . "'>";
                    echo "<button class='btn btn-secondary'>Aanpassen</button>";
                    echo "</a>";
                    
                     // Voorwaardelijke knop voor verwijderen
                    if ($userRole === 'admin') {
                        echo "<a href='rapportage-verwijderen.php?id=" . $rapport['id'] . "' class='ml-2'>";
                        echo "<button id='btn-delete' class='btn btn-danger delete-btn' style='margin-left: 15px;'>Verwijderen</button>";
                        echo "</a>";
                    } else {
                        // JavaScript-alert voor niet-admin gebruikers
                        echo "<button class='btn btn-danger delete-btn ml-2' style='margin-left: 15px;' onclick='alert(\"Deze actie is alleen beschikbaar voor de admin.\");'>Verwijderen</button>";
                    }
                }
                ?>
            </div>
        </div>
    </div>
    </div>

</body>
<style>
    #btn-delete{
        margin-left: 10px;
    }
</style>
</html>