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
    SELECT r.*, vr.id AS verzorgerregel_id, m.naam AS medewerker_naam 
    FROM rapport r 
    LEFT JOIN verzorgerregel vr ON r.verzorgerregelid = vr.id 
    LEFT JOIN medewerker m ON vr.medewerkerid = m.id 
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
    <title>Rapportage van <?= htmlspecialchars($client['naam']) ?></title>
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
            <div class="mt-4 mb-3 bg-white p-4 rounded shadow-sm" style="height: 96%; overflow: auto;">
                <?php if (isset($_SESSION['succes'])) { ?>
                    <div class="alert alert-success alert-dismissible fade show mb-3" role="alert">
                        <i class="fa fa-check-circle me-2"></i><?= htmlspecialchars($_SESSION['succes']) ?>
                    </div>
                    <?php unset($_SESSION['succes']); ?>
                <?php } ?>

                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h1 class="h3 mb-0 text-dark">Rapportages van <?= htmlspecialchars($client['naam']) ?></h1>
                    <a href='rapportage-nieuw.php?id=<?= $clientId ?>' class='btn btn-primary'>
                        <i class="fa fa-plus me-1"></i>Nieuwe rapportage
                    </a>
                </div>

                <?php
                if (empty($rapportages)) {
                    echo "<div class='alert alert-info'><i class='fa fa-info-circle me-2'></i>Er zijn nog geen rapportages voor deze cliënt.</div>";
                }

                $maanden = [
                    1 => 'januari',
                    2 => 'februari',
                    3 => 'maart',
                    4 => 'april',
                    5 => 'mei',
                    6 => 'juni',
                    7 => 'juli',
                    8 => 'augustus',
                    9 => 'september',
                    10 => 'oktober',
                    11 => 'november',
                    12 => 'december'
                ];

                foreach ($rapportages as $rapport) {
                    $dt = new DateTime($rapport['datumtijd']);
                    $dag = $dt->format('d');
                    $maand = $maanden[(int)$dt->format('n')];
                    $jaar = $dt->format('Y');
                    $tijd = $dt->format('H:i');
                    $auteur = !empty($rapport['medewerker_naam']) ? htmlspecialchars($rapport['medewerker_naam']) : 'Zorgverlener';

                    // Schoon inhoud op en kort in met '...' indien te lang
                    $schoneInhoud = trim(str_replace(['<br />', '<br>', '<br/>'], "\n", $rapport['inhoud']));
                    $maxTekens = 160;
                    $isTeLang = mb_strlen($schoneInhoud) > $maxTekens;
                    $korteInhoud = $isTeLang ? rtrim(mb_substr($schoneInhoud, 0, $maxTekens)) . '...' : $schoneInhoud;

                    echo "<div class='card mb-3 shadow-sm border-0' border-left: 4px solid #00365E !important;'>";
                    echo "  <div class='card-header d-flex justify-content-between align-items-center bg-light py-2'>";
                    echo "    <div>";
                    echo "      <span class='fw-bold text-primary fs-5'>$dag $maand $jaar om $tijd</span>";
                    echo "      <span class='badge bg-secondary ms-2'><i class='fa fa-user me-1'></i>$auteur</span>";
                    echo "    </div>";
                    echo "    <a href='rapportage-aanpassen.php?id=" . $clientId . "&rapport_id=" . $rapport['id'] . "' class='btn btn-sm btn-outline-secondary'>";
                    echo "      <i class='fa fa-pen me-1'></i>Aanpassen";
                    echo "    </a>";
                    echo "  </div>";
                    echo "  <div class='card-body py-3'>";
                    if (!empty($korteInhoud)) {
                        echo "    <p class='card-text mb-0 text-dark' style='max-width: 850px; overflow: hidden; text-overflow: ellipsis; display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; word-break: break-word;'>";
                        echo htmlspecialchars($korteInhoud);
                        echo "    </p>";
                    } else {
                        echo "    <p class='card-text text-muted fst-italic mb-0'>Geen inhoud ingevoerd.</p>";
                    }
                    echo "  </div>";
                    echo "</div>";
                }
                ?>
            </div>
        </div>
    </div>
</body>

</html>