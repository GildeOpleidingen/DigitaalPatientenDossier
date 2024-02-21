<?php
session_start();
include_once '../../Database/DatabaseConnection.php';
include_once '../../Functions/ClientFunctions.php';

if(!isset($_GET['id'])) {
    header("Location: ../client.php");
}

$id = $_GET['id'];
$_SESSION['clientId'] = $_GET['id'];

$client = DatabaseConnection::getConn()->prepare("SELECT * FROM client WHERE id = ?");
$client->bind_param("i", $id);
$client->execute();
$client = $client->get_result()->fetch_assoc();

$clientRelations = DatabaseConnection::getConn()->prepare("SELECT * FROM verzorgerregel WHERE clientid = ?");
$clientRelations->bind_param("i", $id);
$clientRelations->execute();
$clientRelations = $clientRelations->get_result()->fetch_all(MYSQLI_ASSOC);

$verzorgers = [];
foreach ($clientRelations as $relation) {
    $verzorger = DatabaseConnection::getConn()->prepare("SELECT * FROM medewerker WHERE id = ?");
    $verzorger->bind_param("i", $relation['medewerkerid']);
    $verzorger->execute();
    $verzorger = $verzorger->get_result()->fetch_assoc();
    array_push($verzorgers, $verzorger);
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="Stylesheet" href="overzicht.css">
    <title>Overzicht van <?= $client['naam'] ?></title>
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
            <div class="content-2">
                <div id="episodes" class="card">
                    <p class="header">Episodes</p>
                    <p class="text">Geen Episodes</p>
                </div>
                <div id="opnamedatum" class="card">
                    <p class="header">Opgenomen op</p>
                    <p class="text"><?php echo getAdmissionDateByClientId($id);?></p>
                </div>
        </div>
        </div>
    </div>

</body>
</html>