<?php
session_start();
include_once '../../database/DatabaseConnection.php';
include_once '../../classes/Main.php';
$Main = new Main();

$clientId = $_GET['id'];
if (!isset($clientId)) {
    header("Location: ../../index.php");
}

$_SESSION['clientId'] = $clientId;

$client = $Main->getClientById($clientId);

$clientRelations = DatabaseConnection::getConn()->prepare("SELECT * FROM verzorgerregel WHERE clientid = ?");
$clientRelations->bind_param("i", $clientId);
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
    <link rel="stylesheet" href="../../assets/css/bootstrap.min.css">
    <title>Overzicht van <?= $client['naam'] ?></title>
</head>
<body>
<div class="main">
    <?php
    include '../../includes/n-header.php';
    ?>

    <?php
    include '../../includes/n-sidebar.php';
    ?>
        
    <div class="content">
        <div class="mt-4 mb-3 bg-white p-3" style="height: 96%; overflow: auto;">
            <p class="card-text">
                <h2 class="lead text-primary">Episodes</h2>
                <p class="text">Geen episodes</p>

                <h2 class="lead text-primary">Opgenomen op</h2>
                <p class="text"><?= $Main->getAdmissionDateByClientId($clientId); ?></p>

                <h2 class="lead text-primary">Medische voorgeschiedenis</h2>
                <p class="text">
                    <?php
                    $mv = $Main->getMedischOverzichtByClientId($clientId)['medischevoorgeschiedenis'];
                    if ($mv) {
                        echo $mv;
                    } else {
                        echo "Geen medische voorgeschiedenis ingevuld";
                    }
                    ?>
                </p>

                <h2 class="lead text-primary">Allergieën</h2>
                <p class="text">
                    <?php
                    $allergieen = $Main->getMedischOverzichtByClientId($clientId)['alergieen'];
                    if ($allergieen) {
                        echo $allergieen;
                    } else {
                        echo "Geen allergieën ingevuld";
                    }
                    ?>
                </p>

                <h2 class="lead text-primary">Medicatie</h2>
                <p class="text">
                    <?php
                    $medicatie = $Main->getMedischOverzichtByClientId($clientId)['medicatie'];
                    if ($medicatie) {
                        echo $medicatie;
                    } else {
                        echo "Geen medicatie ingevuld";
                    }
                    ?>
                </p>

            </p>
        </div>
    </div>    
</body>
</html>