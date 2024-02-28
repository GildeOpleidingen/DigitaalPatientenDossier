<?php
session_start();
include_once '../../Database/DatabaseConnection.php';

if(!isset($_GET['id'])) {
    header("Location: ../client.php");
}

$id = $_GET['id'];
$_SESSION['clientId'] = $_GET['id'];

$client = DatabaseConnection::getConn()->prepare("SELECT * FROM client WHERE id = ?");
$client->bind_param("i", $id);
$client->execute();
$client = $client->get_result()->fetch_assoc();

if ($client == null) {
    header("Location: ../client.php");
}

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
$verzorgerNamen = []
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
            include_once '../../Includes/header.php';
            include_once '../../Includes/sidebar.php';
            ?>

        <div class="content">
            <div class="overzicht">
                <div class="overzicht-content">
                    <div class="text">
                        <strong>Geslacht:</strong>
                        <p><?= $client['geslacht'] ?></p>
                    </div>
                    <div class="text">
                        <strong>Geboortedatum:</strong>
                        <p><?= date_create($client['geboortedatum'])->format('d-m-Y') ?></p>
                    </div>
                    <div class="text">
                        <strong>Adres:</strong>
                        <p><?= $client['adres'] ?></p>
                    </div>
                    <div class="text">
                        <strong>Postcode:</strong>
                        <p><?= $client['postcode'] ?></p>
                    </div>
                    <div class="text">
                        <strong>Woonplaats:</strong>
                        <p><?= $client['woonplaats'] ?></p>
                    </div>
                    <div class="text">
                        <strong>Telefoonnummer:</strong>
                        <p><?= $client['telefoonnummer'] ?></p>
                    </div>
                    <div class="text">
                            <strong>E-mail:</strong>
                            <p><?= $client['email'] ?></p>
                        </div>
                        <div class="text">
                            <strong>Afdeling:</strong>
                            <p><?= $client['afdeling'] ?></p>
                        </div>
                        <div class="text">
                            <strong>Burgelijke staat:</strong>
                            <p><?= $client['burgelijkestaat'] ?></p>
                        </div>
                        <div class="text">
                            <strong>Nationaliteit:</strong>
                            <p><?= $client['nationaliteit'] ?></p>
                        </div>
                        <div class="text">
                            <a href="verzorgers.php?id=<?= $_GET['id']?>">
                                <strong>Verzorger(s):</strong>
                            </a>
                            <div class="p2">
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
        </div>
    </body>
</html>
