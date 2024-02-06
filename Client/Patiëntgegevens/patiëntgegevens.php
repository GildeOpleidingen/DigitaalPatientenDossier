<?php
session_start();
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
    <link rel="Stylesheet" href="patiëntgegevens.css">
    <title>Gegevens van <?= $client['naam'] ?></title>
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
        <div class="patiëntgegevens">
            <div class="patiëntgegevens-content">
                <div class="text">
                    <strong>Geslacht</strong>
                    <p><?= $client['geslacht'] ?></p>
                </div>
                <div class="text">
                    <strong>Geboortedatum</strong>
                    <p><?= $client['geboortedatum'] ?></p>
                </div>
                <div class="text">
                    <strong>Adres</strong>
                    <p><?= $client['adres'] ?></p>
                </div>
                <div class="text">
                    <strong>Postcode</strong>
                    <p><?= $client['postcode'] ?></p>
                </div>
                <div class="text">
                    <strong>Woonplaats</strong>
                    <p><?= $client['woonplaats'] ?></p>
                </div>
            </div>
            <br>
            <div class="patiëntgegevens-content">
                <div class="text">
                    <strong>Telefoonnummer</strong>
                    <p><?= $client['telefoonnummer'] ?></p>
                </div>
                <div class="text">
                    <strong>E-mail</strong>
                    <p><?= $client['email'] ?></p>
                </div>
                <div class="text">
                    <strong>Afdeling</strong>
                    <p><?= $client['afdeling'] ?></p>
                </div>
                <div class="text">
                    <strong>Burgelijke staat</strong>
                    <p><?= $client['burgelijkestaat'] ?></p>
                </div>
                <div class="text">
                    <strong>Nationaliteit</strong>
                    <p><?= $client['nationaliteit'] ?></p>
                </div>
            </div>
            <br>
            <div class="patiëntgegevens-content">
                <div class="text">
                    <?php
                    if ($contactpersonen <= null) { ?>
                        <strong>Contactpersonen</strong>
                        <p>Geen contactpersonen<p>
                    <?php } else {
                        $num = 1;
                        foreach ($contactpersonen as $contactpersoon) { ?>
                            <strong>Contactpersoon <?= $num ?> </strong>
                            <p><?= $contactpersoon['naam'] ?></p>
                            <p><?= $contactpersoon['relatietype'] ?></p>
                            <p><?= $contactpersoon['telefoonnummer'] ?></p>
                            <br>
                            <?php $num++;
                        }
                    } ?>
                </div>
                <div class="text">
                    <strong>Zorgopname</strong>
                    <p>Vanaf <?= $medischoverzicht['opnamedatum'] ?></p>
                </div>
                <div class="text">
                    <a href="verzorgers.php?id=<?= $_GET['id'] ?>">
                    </a>
                    <?php $num = 1;
                    foreach ($verzorgers as $verzorger) { ?>
                        <strong>Verzorger <?= $num ?></strong>
                        <p><?= $verzorger['naam'] ?></p>
                        <p><?= $verzorger['klas'] ?></p>
                        <p><?= $verzorger['email'] ?></p>
                        <br>
                        <?php $num++;
                    } ?>
                </div>
            </div>
        </div>
    </div>
</div>

</body>
</html>