<?php
session_start();
require_once('../../includes/auth.php');
include_once '../../database/DatabaseConnection.php';
include_once '../../models/autoload.php';
$Main = new Main();

$clientId = $_SESSION['clientId'];
if (!isset($clientId)) {
    header("Location: ../../index.php");
}

$client = $Main->getClientById($clientId);
$clientRelations = $Main->getPatientGegevens($clientId, 'clientRelations');
$contactpersonen = $Main->getPatientGegevens($clientId, 'contactPersonen');
$medischoverzicht = $Main->getPatientGegevens($clientId, 'medischOverzicht');
$verzorgerArr = $Main->getPatientGegevens($clientId, 'verzorgersArr');

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
    <link rel="Stylesheet" href="../../assets/css/client/patiëntgegevens.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" 
          rel="stylesheet" 
          integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" 
          crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css" 
          integrity="sha512-Kc323vGBEqzTmouAECnVceyQqyqdsSiqLQISBL29aUW4U/M7pSPA/gEUZQqv1cwx4OnYxTxve5UMg5GT6L4JJg==" 
          crossorigin="anonymous" referrerpolicy="no-referrer" />
    <title>Overzicht van <?= $client['naam'] ?></title>
</head>
<body>
<div class="main">
    <?php
    include_once '../../includes/n-header.php';
    include_once '../../includes/n-sidebar.php';
    ?>

    <div class="content">
        <div class="mt-4 mb-3 bg-white p-3" style="height: 96%; overflow: auto;">
            <p class="card-text">
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
                        <a href="../Overzicht/verzorgers.php?id=<?= $_GET['id']?>" style="text-decoration: underline dotted;">
                            <strong class="text-primary">Verzorgers:</strong>
                        </a>
                        <?php $i=0?>
                        <?php foreach ($verzorgers as $key => $verzorger) { ?>
                            <?php $verzorgerNamen[$i] = $verzorger['naam']; ?>
                            <?php $i++?>
                        <?php } ?>
                        <p><?php echo join(", ",$verzorgerNamen); ?></p>
                    </div>
                </div>
            </p>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" 
            integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" 
            crossorigin="anonymous"></script> 
</body>
</html>