<?php
session_start();
include_once '../../Database/DatabaseConnection.php';

if(!isset($_GET['id'])) {
    header("Location: ../client.php");
}

$id = $_GET['id'];
$_SESSION['clientId'] = $_GET['id'];

$result = DatabaseConnection::getConn()->prepare("SELECT * FROM client WHERE id = ?");
$result->bind_param("s", $id);
$result->execute();
$client = $result->get_result()->fetch_assoc();
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
    <div class="main-content">
        <?php
        include '../../Includes/header.php';
        ?>

        <?php
        include '../../Includes/sidebar.php';
        ?>

        <div class="content">
                <div class="overzicht">
                    <div class="overzicht-content">
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
                    <div class="overzicht-content">
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
                </div>
            </div>
        </div>
    </div>

</body>
</html>