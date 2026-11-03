<?php
include_once '../../database/DatabaseConnection.php';
include_once '../../models/autoload.php';

Auth::startSession();
Auth::requireLogin();
$loggedInId = Auth::id();

$Main = new Main();
$ClientModel = new ClientModel();

// Controleer of er een client is geselecteerd (via GET of SESSION)
$clientId = isset($_GET['id']) ? (int)$_GET['id'] : ($_SESSION['clientId'] ?? null);
if (!$clientId) {
    header("Location: ../client.php");
    exit;
}
$_SESSION['clientId'] = (int)$clientId;

// Haal clientgegevens op
$client = $ClientModel->getById($clientId);
if (!$client) {
    $client = $ClientModel->getClientById($clientId);
}
if (!$client || empty($client)) {
    header("Location: ../client.php");
    exit;
}
$_SESSION['client'] = $client;

$error = null;

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['opslaan'])) {
    $inhoud = trim($_POST['inhoud'] ?? '');

    if (empty($inhoud)) {
        $error = "De rapportage mag niet leeg zijn.";
    } else {
        $conn = DatabaseConnection::getConn();

        // Zorg dat er een actieve verzorgerregel bestaat voor deze client en medewerker
        $stmt = $conn->prepare("
            INSERT INTO verzorgerregel (clientid, medewerkerid, is_deleted)
            VALUES (?, ?, 0)
            ON DUPLICATE KEY UPDATE is_deleted = 0
        ");
        if ($stmt) {
            $stmt->bind_param("ii", $clientId, $loggedInId);
            $stmt->execute();
            $stmt->close();
        }

        $stmtId = $conn->prepare("SELECT id FROM verzorgerregel WHERE clientid = ? AND medewerkerid = ? ORDER BY id DESC LIMIT 1");
        $stmtId->bind_param("ii", $clientId, $loggedInId);
        $stmtId->execute();
        $vrRow = $stmtId->get_result()->fetch_assoc();
        $verzorgerregelId = $vrRow ? (int)$vrRow['id'] : 0;
        $stmtId->close();

        if ($verzorgerregelId > 0) {
            $tijd = date('Y-m-d H:i:s');
            $stmtRapport = $conn->prepare("INSERT INTO rapport (verzorgerregelid, datumtijd, inhoud) VALUES (?, ?, ?)");
            if ($stmtRapport) {
                $stmtRapport->bind_param("iss", $verzorgerregelId, $tijd, $inhoud);
                $stmtRapport->execute();
                $stmtRapport->close();

                $_SESSION['succes'] = 'Nieuwe rapportage succesvol opgeslagen.';
                header("Location: rapportage.php?id=" . $clientId);
                exit;
            } else {
                $error = "Databasefout bij het opslaan van de rapportage.";
            }
        } else {
            $error = "Kon geen actieve zorgrelatie aanmaken.";
        }
    }
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
    <title>Nieuwe rapportage - <?= htmlspecialchars($client['naam']) ?></title>
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
                <h1 class="h3 mb-3 text-dark">Nieuwe rapportage toevoegen voor <?= htmlspecialchars($client['naam']) ?></h1>
                
                <?php if ($error) { ?>
                    <div class="alert alert-danger" role="alert">
                        <i class="fa fa-exclamation-triangle me-2"></i><?= htmlspecialchars($error) ?>
                    </div>
                <?php } ?>

                <form method="POST" class="needs-validation flex-grow-1 d-flex flex-column" novalidate>
                    <div class="mb-3 flex-grow-1 d-flex flex-column">
                        <label for="rapportage" class="form-label fw-bold">Inhoud rapportage <span class="text-danger">*</span></label>
                        <textarea name="inhoud" id="rapportage" rows="14" placeholder="Typ hier de rapportage..." class="form-control flex-grow-1" required><?= htmlspecialchars($_POST['inhoud'] ?? '') ?></textarea>
                        <div class="form-text">Voer de observaties en bijzonderheden in over de cliënt.</div>
                    </div>

                    <div class="d-flex gap-2 mt-3">
                        <button type="submit" name="opslaan" class="btn btn-primary px-4">
                            <i class="fa fa-save me-1"></i>Rapportage opslaan
                        </button>
                        <a href="rapportage.php?id=<?= $clientId ?>" class="btn btn-secondary px-3">Annuleren</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</body>
<script src="../../assets/vendor/jquery/jquery.min.js"></script>
<script src="../../assets/js/validatie.js"></script>

</html>
