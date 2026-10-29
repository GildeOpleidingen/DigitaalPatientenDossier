<?php
session_start();
include_once '../../database/DatabaseConnection.php';
include_once '../../models/autoload.php';
$Main = new Main();
$ClientModel = new ClientModel();

$clientId = $_SESSION['clientId'];

$client = $_SESSION['client'] = $ClientModel->getClientById($clientId);
$rapportages = DatabaseConnection::getConn()->prepare("SELECT r.*, vr.id AS verzorgerregel_id FROM rapport r LEFT JOIN verzorgerregel vr ON r.verzorgerregelid = vr.id WHERE vr.clientid = ? ORDER BY r.datumtijd DESC;");
$rapportages->bind_param("i", $clientId);
$rapportages->execute();
$rapportages = $rapportages->get_result()->fetch_all(MYSQLI_ASSOC);
if ($client == null) {
    header("Location: ../client.php");
}

include '../../includes/n-header.php';
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="Stylesheet" href="../../assets/css/client/rapportage.css">
    <link rel="stylesheet" href="../../assets/vendor/bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css"
        integrity="sha512-Kc323vGBEqzTmouAECnVceyQqyqdsSiqLQISBL29aUW4U/M7pSPA/gEUZQqv1cwx4OnYxTxve5UMg5GT6L4JJg=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />
    <title>Rapportage van <?= $client['naam'] ?></title>
    <link rel="icon" type="image/x-icon" href="../../assets/images/favicon.ico">
</head>

<body>
    <div class="main">

        <?php
        include '../../includes/n-sidebar.php';
        ?>

        <div class="content">
            <div class="mt-4 mb-3 bg-white p-3" style="height: 96%; overflow: auto;">
                <p class="card-text">
                <h1>Rapportages</h1>
                <a href='rapportage-nieuw.php?id=<?= $clientId ?>' class='btn btn-primary'>Nieuwe rapportage</a>
                <?php
                $maanden = [
                    1 => 'januari', 2 => 'februari', 3 => 'maart', 4 => 'april',
                    5 => 'mei', 6 => 'juni', 7 => 'juli', 8 => 'augustus',
                    9 => 'september', 10 => 'oktober', 11 => 'november', 12 => 'december'
                ];

                foreach ($rapportages as $rapport) {
                    $dt = new DateTime($rapport['datumtijd']);
                    $dag = $dt->format('d');
                    $maand = $maanden[(int)$dt->format('n')];
                    $jaar = $dt->format('Y');
                    $tijd = $dt->format('H:i');

                    echo "<h1>Rapportage van $dag $maand $jaar om $tijd</h1>";
                    echo "<a href='rapportage-aanpassen.php?id=" . $rapport['id'] . "'>";
                    echo "<button class='btn btn-secondary'>Aanpassen</button>";
                    echo "</a>";
                }
                ?>
            </div>
        </div>
    </div>
    </div>
</body>

</html>