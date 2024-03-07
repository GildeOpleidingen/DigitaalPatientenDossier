<?php
session_start();
include_once '../../Database/DatabaseConnection.php';
include_once '../../Functions/ClientFunctions.php';

$clientId = $_GET['id'];
$_SESSION['clientId'] = $_GET['id'];

$client = $_SESSION['client'] = getClientById($clientId);
$rapportages = DatabaseConnection::getConn()->prepare("SELECT r.*, vr.id AS verzorgerregel_id FROM rapport r LEFT JOIN verzorgerregel vr ON r.verzorgerregelid = vr.id WHERE vr.clientid = ?;");
$rapportages->bind_param("i", $clientId);
$rapportages->execute();
$rapportages = $rapportages->get_result()->fetch_all(MYSQLI_ASSOC);
if ($client == null) {
    header("Location: ../client.php");
}

include '../../Includes/header.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="Stylesheet" href="rapportage.css">
    <title>Rapportage van <?= $client['naam'] ?></title>
</head>
<body>
<div class="main">

        <?php
        include '../../Includes/sidebar.php';
        ?>

        <div class="content">
            <div class="back">
                <h2>Reportages</h2>
            <?php echo "<a href='rapportageNieuw.php?id=" . $clientId . "'>" ?>
                    <button class="rapportageButton" type="submit">Nieuwe rapportage</button>
            <?php
            foreach ($rapportages as $rapport) {
                echo "<h1>Rapportage van " . $rapport['datumtijd'] . " (" . $rapport['id'] . ")</h1>";
                echo "<a href='rapportageAanpassen.php?id=" . $rapport['id'] . "'>";
                echo "<button class='rapportageButton' type='submit'>Aanpassen</button>";
                echo "</a>";
            }
            ?>
            </div>
        </div>
</div>
        <div class="alleRapportages">

        </div>

</body>
</html>
