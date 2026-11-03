<?php
session_start();
include_once '../../database/DatabaseConnection.php';
include_once '../../models/autoload.php';
$Main = new Main();
$ClientModel = new ClientModel();

$clientId = isset($_GET['id']) ? (int)$_GET['id'] : ($_SESSION['clientId'] ?? null);
if (!$clientId) {
    header("Location: ../client.php");
    exit;
}
$_SESSION['clientId'] = (int)$clientId;

$client = $ClientModel->getById($clientId);
if (!$client) {
    $client = $ClientModel->getClientById($clientId);
}
if (!$client || empty($client)) {
    header("Location: ../client.php");
    exit;
}
$_SESSION['client'] = $client;

$rapportages = [];
$stmt = DatabaseConnection::getConn()->prepare("
    SELECT r.*, vr.id AS verzorgerregel_id 
    FROM rapport r 
    LEFT JOIN verzorgerregel vr ON r.verzorgerregelid = vr.id 
    WHERE vr.clientid = ? 
    ORDER BY r.datumtijd DESC
");
if ($stmt) {
    $stmt->bind_param("i", $clientId);
    $stmt->execute();
    $rapportages = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    $stmt->close();
}
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
    <?php
    include_once '../../includes/n-header.php';
    ?>
    <div class="main">

        <?php
        include '../../includes/n-sidebar.php';
        ?>

        <div class="content">
            <div class="mt-4 mb-3 bg-white p-3" style="height: 96%; overflow: auto;">
                <h1 class="mb-3">Rapportages van <?= htmlspecialchars($client['naam']) ?></h1>
                <a href='rapportage-nieuw.php?id=<?= $clientId ?>' class='btn btn-primary mb-3'>Nieuwe rapportage</a>
                <?php
                if (empty($rapportages)) {
                    echo "<p class='text-muted mt-2'>Er zijn nog geen rapportages voor deze cliënt.</p>";
                }

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