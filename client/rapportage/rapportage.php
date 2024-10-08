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
                    echo "<h1>Rapportage van " . $rapport['datumtijd'] . " (" . $rapport['id'] . ")</h1>";
                    
                    echo "<a href='rapportage-aanpassen.php?id=" . $rapport['id'] . "'>";
                    echo "<button class='btn btn-secondary'>Aanpassen</button>";
                    echo "</a>";
                    
                    echo "<a href='rapportage-verwijderen.php?id=" . $rapport['id'] . "' class='ml-2'>";
                    echo "<button id='btn-delete' class='btn btn-danger delete-btn'>Verwijderen</button>";
                    echo "</a>";
                }
                ?>
            </div>
        </div>
    </div>
    </div>

</body>
<style>
    .btn-delete{
        margin-left: 9px;
    }
</style>
</html>