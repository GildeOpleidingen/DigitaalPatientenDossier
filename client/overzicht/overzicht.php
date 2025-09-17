<?php
session_start();
include_once '../../database/DatabaseConnection.php';
include_once '../../models/autoload.php';
$Main = new Main();

$clientId = $_GET['id'];
if (!isset($clientId)) {
    header("Location: ../../index.php");
}

$_SESSION['clientId'] = $clientId;

$client = $Main->getClientById($clientId);
$clientRelations = $Main->getVerzorgerregelByClientId($clientId);
$verzorgers = [];
foreach ($clientRelations as $relation) {
    $verzorger = $Main->getVerzorgersById($relation['medewerkerid']);
    array_push($verzorgers, $verzorger);
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="Stylesheet" href="../../assets/css/client/overzicht.css">
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
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" 
            integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" 
            crossorigin="anonymous"></script>  
</body>
</html>