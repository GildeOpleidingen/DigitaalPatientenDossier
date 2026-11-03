<?php
include_once '../../database/DatabaseConnection.php';
include_once '../../models/autoload.php';

Auth::startSession();
Auth::requireLogin();

// Haal rapportage-ID op (ondersteun zowel rapport_id als id parameter)
$rapportageId = isset($_GET['rapport_id']) ? (int)$_GET['rapport_id'] : (isset($_GET['id']) ? (int)$_GET['id'] : 0);
if ($rapportageId <= 0) {
    header("Location: ../client.php");
    exit;
}

$stmt = DatabaseConnection::getConn()->prepare("
    SELECT r.*, vr.clientid, m.naam AS medewerker_naam 
    FROM rapport r 
    LEFT JOIN verzorgerregel vr ON r.verzorgerregelid = vr.id 
    LEFT JOIN medewerker m ON vr.medewerkerid = m.id 
    WHERE r.id = ?
");
$stmt->bind_param("i", $rapportageId);
$stmt->execute();
$rapportage = $stmt->get_result()->fetch_assoc();
$stmt->close();

if (!$rapportage) {
    header("Location: ../client.php");
    exit;
}

// Bepaal het échte client-ID via de gekoppelde verzorgerregel
$clientId = !empty($rapportage['clientid']) ? (int)$rapportage['clientid'] : (isset($_GET['id']) ? (int)$_GET['id'] : ($_SESSION['clientId'] ?? null));
if (!$clientId) {
    header("Location: ../client.php");
    exit;
}
$_SESSION['clientId'] = $clientId;

$ClientModel = new ClientModel();
$client = $ClientModel->getById($clientId);
if (!$client) {
    $client = $ClientModel->getClientById($clientId);
}

$error = null;

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['aanpassen'])) {
    $rapportageInhoud = trim($_POST['inhoud'] ?? '');
    $postRapportId = (int)($_POST['rapportageId'] ?? $rapportageId);

    if (empty($rapportageInhoud)) {
        $error = "De rapportage mag niet leeg zijn.";
    } else {
        // Schoon eventuele oude <br /> tags op zodat platte tekst wordt opgeslagen
        $schoneTekst = str_replace(['<br />', '<br>', '<br/>'], "\n", $rapportageInhoud);
        $tijd = date('Y-m-d H:i:s');

        $updateStmt = DatabaseConnection::getConn()->prepare("UPDATE rapport SET inhoud = ?, datumtijd = ? WHERE id = ?");
        if ($updateStmt) {
            $updateStmt->bind_param("ssi", $schoneTekst, $tijd, $postRapportId);
            $updateStmt->execute();
            $updateStmt->close();

            $_SESSION['succes'] = 'Rapportage succesvol bijgewerkt.';
            header("Location: rapportage.php?id=" . $clientId);
            exit;
        } else {
            $error = "Databasefout bij het bijwerken van de rapportage.";
        }
    }
}

// Formateer datum van het rapport voor de titel
$dt = new DateTime($rapportage['datumtijd']);
$datumFormat = $dt->format('d-m-Y H:i');
$auteur = !empty($rapportage['medewerker_naam']) ? htmlspecialchars($rapportage['medewerker_naam']) : 'Zorgverlener';
$inhoudWaarde = str_replace(['<br />', '<br>', '<br/>'], "\n", $rapportage['inhoud']);
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
    <title>Rapportage aanpassen - <?= htmlspecialchars($client['naam'] ?? '') ?></title>
    <link rel="icon" type="image/x-icon" href="../../assets/images/favicon.ico">
</head>

<body>
    <?php
    include_once '../../includes/n-header.php';
    ?>
    <div class="main">
        <?php
        include_once '../../includes/n-sidebar.php';
        ?>
        <div class="content">
            <div class="mt-4 mb-3 bg-white p-4 rounded shadow-sm d-flex flex-column" style="height: 96%; overflow: auto;">
                <div class="mb-3">
                    <h1 class="h3 text-dark mb-1">Rapportage aanpassen</h1>
                    <div class="text-muted small">
                        Oorspronkelijk aangemaakt op <strong><?= $datumFormat ?></strong> door <strong><?= $auteur ?></strong>
                    </div>
                </div>

                <?php if ($error) { ?>
                    <div class="alert alert-danger" role="alert">
                        <i class="fa fa-exclamation-triangle me-2"></i><?= htmlspecialchars($error) ?>
                    </div>
                <?php } ?>

                <form method="POST" class="needs-validation flex-grow-1 d-flex flex-column" novalidate>
                    <input type="hidden" value="<?= (int)$rapportage['id'] ?>" name="rapportageId">
                    
                    <div class="mb-3 flex-grow-1 d-flex flex-column">
                        <label for="rapportage" class="form-label fw-bold">Inhoud rapportage <span class="text-danger">*</span></label>
                        <textarea name="inhoud" id="rapportage" rows="14" placeholder="Typ hier de rapportage..." class="form-control flex-grow-1" required><?= htmlspecialchars($inhoudWaarde) ?></textarea>
                    </div>

                    <div class="d-flex gap-2 mt-3">
                        <button type="submit" name="aanpassen" class="btn btn-primary px-4">
                            <i class="fa fa-save me-1"></i>Aanpassingen opslaan
                        </button>
                        <a href="rapportage.php?id=<?= $clientId ?>" class="btn btn-secondary px-3">Terug naar overzicht</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</body>
<script src="../../assets/vendor/jquery/jquery.min.js"></script>
<script src="../../assets/js/validatie.js"></script>

</html>
