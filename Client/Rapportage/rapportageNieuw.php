<?php
session_start();
include_once '../../Database/DatabaseConnection.php';

$loggedInId = $_SESSION['loggedin_id'];

if ($loggedInId == null) {
    header("Location: ../index.php");
}

if(!isset($_GET['id'])) {
    header("Location: ../client.php");
}

$clientId = $_GET['id'];
$_SESSION['clientId'] = $_GET['id'];

$client = DatabaseConnection::getConn()->prepare("SELECT * FROM client WHERE id = ?");
$client->bind_param("i", $id);
$client->execute();
$client = $client->get_result()->fetch_assoc();

$verzorgerregel = DatabaseConnection::getConn()->prepare("SELECT * FROM verzorgerregel WHERE clientid = ? AND medewerkerid = ?");
$verzorgerregel->bind_param("ii", $clientId, $loggedInId);
$verzorgerregel->execute();
$verzorgerregel = $verzorgerregel->get_result()->fetch_assoc()['id'];

$tijd = time();
$rapport = "rapportage";
$rapportage = DatabaseConnection::getConn()->prepare("INSERT INTO rapport (verzorgerregelid, datumtijd, inhoud) VALUES (?, ?, ?)");
$rapportage->bind_param("iss", $verzorgerregel, $tijd, $rapport);
$rapportage->execute();
$rapportage = $rapportage->insert_id;

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
        include '../../Includes/header.php';
        ?>

        <?php
        include '../../Includes/sidebar.php';
        ?>

        <div class="content">
                    <form method="POST">
                        <div class="rapportage">
                            <textarea name="rapportage" id="rapportage" placeholder="Rapportage" style="width: 100%; height: 100%; box-sizing: border-box;"></textarea>
                            <button class="rapportageButton" type="button" onclick="submit()">Submit</button>
                        </div>

                    </form>
        </div>
    </div>

</body>
</html>
