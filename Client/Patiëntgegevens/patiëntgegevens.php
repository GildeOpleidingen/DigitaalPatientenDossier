<?php
session_start();
require_once('../../Includes/auth.php');
include_once '../../Database/DatabaseConnection.php';

if (!isset($_GET['id'])) {
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

$contactpersonen = DatabaseConnection::getConn()->prepare("SELECT * FROM relatie WHERE clientid = ?");
$contactpersonen->bind_param("i", $id);
$contactpersonen->execute();
$contactpersonen = $contactpersonen->get_result()->fetch_all(MYSQLI_ASSOC);

$medischoverzicht = DatabaseConnection::getConn()->prepare("SELECT * FROM medischoverzicht WHERE clientid = ?");
$medischoverzicht->bind_param("i", $id);
$medischoverzicht->execute();
$medischoverzicht = $medischoverzicht->get_result()->fetch_assoc();

$verzorgerArr = DatabaseConnection::getConn()->prepare("SELECT * FROM medewerker");
$verzorgerArr->execute();
$verzorgerArr = $verzorgerArr->get_result()->fetch_all(MYSQLI_ASSOC);

$verzorgers = [];
foreach ($clientRelations as $relation) {
    foreach ($verzorgerArr as $naam)
        if ($relation['medewerkerid'] == $naam['id']) {
            $verzorgers[] = $naam;
        }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="Stylesheet" href="patiëntgegevens.css">
    <title>Overzicht van <?= $client['naam'] ?></title>
</head>
<body>
<div class="main">
    <?php
    include_once '../../Includes/header.php';
    include_once '../../Includes/sidebar.php';
    ?>

    <div class="content">
        <div class="overzicht">
            <div class="overzicht-content">
                <div class="infotext">
                    <strong>Geslacht:</strong>
                    <p><?= $client['geslacht'] ?></p>
                </div>
                <div class="infotext">
                    <strong>Geboortedatum:</strong>
                    <p><?= date_create($client['geboortedatum'])->format('d-m-Y') ?></p>
                </div>
                <div class="infotext">
                    <strong>Adres:</strong>
                    <p><?= $client['adres'] ?></p>
                </div>
                <div class="infotext">
                    <strong>Postcode:</strong>
                    <p><?= $client['postcode'] ?></p>
                </div>
                <div class="infotext">
                    <strong>Woonplaats:</strong>
                    <p><?= $client['woonplaats'] ?></p>
                </div>
                <div class="infotext">
                    <strong>Telefoonnummer:</strong>
                    <p><?= $client['telefoonnummer'] ?></p>
                </div>
                <div class="infotext">
                    <strong>E-mail:</strong>
                    <p><?= $client['email'] ?></p>
                </div>
                <div class="infotext">
                    <strong>Afdeling:</strong>
                    <p><?= $client['afdeling'] ?></p>
                </div>
                <div class="infotext">
                    <strong>Burgelijke staat:</strong>
                    <p><?= $client['burgelijkestaat'] ?></p>
                </div>
                <div class="infotext">
                    <strong>Nationaliteit:</strong>
                    <p><?= $client['nationaliteit'] ?></p>
                </div>
                <div class="infotext">
                    <a href="../Overzicht/verzorgers.php?id=<?= $_GET['id']?>">
                        <strong>Verzorger(s):</strong>
                    </a>
                    <?php $i=0?>
                    <?php foreach ($verzorgers as $key => $verzorger) { ?>
                        <?php $verzorgerNamen[$i] = $verzorger['naam']; ?>
                        <?php $i++?>
                    <?php } ?>
                    <p><?php echo join(", ",$verzorgerNamen); ?></p>
                </div>
            </div>
        </div>
    </div>
</body>
</html>