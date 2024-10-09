<?php
session_start();
include_once '../../database/DatabaseConnection.php';

if (!isset($_SESSION['loggedin_id'])) {
    header("Location: ../index.php");
    exit();
}

$loggedInId = $_SESSION['loggedin_id'];
$clientId = $_SESSION['clientId'];

if (!$clientId) {
    header("Location: ../client.php");
    exit();
}

if (isset($_POST['aanmaken'])) {
    $rapportageInhoud = $_POST['inhoud'];
    $rapportageInhoud = nl2br($rapportageInhoud);
    $tijd = date('Y-m-d H:i:s');
    $verzorgerregel = DatabaseConnection::getConn()->prepare("SELECT id FROM verzorgerregel WHERE clientid = ? AND medewerkerid = ?");
    $verzorgerregel->bind_param("ii", $clientId, $loggedInId);
    $verzorgerregel->execute();
    $verzorgerregel = $verzorgerregel->get_result()->fetch_assoc()['id'];
    
    $stmt = DatabaseConnection::getConn()->prepare("INSERT INTO rapport (verzorgerregelid, datumtijd, inhoud, titel_rapport) VALUES (?, ?, ?, '')");
    $stmt->bind_param("iss", $verzorgerregel, $tijd, $rapportageInhoud);
    $stmt->execute();
    $nieuwRapportageId = $stmt->insert_id;
    $stmt->close();

    $_SESSION['succes'] = 'Nieuwe rapportage aangemaakt.';
    header("Location: http://fatih/DigitaalPatientenDossier/client/rapportage/rapportage.php?id=$nieuwRapportageId");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="Stylesheet" href="../../assets/css/client/rapportage.css">
    <link rel="stylesheet" href="../../assets/css/bootstrap.min.css">
    <title>Nieuwe Rapportage</title>
</head>

<body>
    <?php include_once '../../includes/n-header.php'; ?>
    <div class="main">
        <?php include_once '../../includes/n-sidebar.php'; ?>
        <div class="content">
            <div class="mt-4 mb-3 bg-white p-3 d-flex flex-column" style="height: 96%; overflow: auto;">
                <form method="POST" class="needs-validation flex-grow-1 d-flex flex-column" novalidate>
                    <div class="flex-grow-1 d-flex flex-column">
                        <?php if (isset($_SESSION['succes'])) { ?>
                            <div class="alert alert-success" role="alert">
                                <?= $_SESSION['succes'] ?>
                            </div>
                            <?php unset($_SESSION['succes']); ?>
                        <?php } ?>
                        <div class="rapportage flex-grow-1">
                            <div class="display-5 text-primary mb-3">Nieuwe Rapportage Aanmaken</div>
                            <div class="mb-3">
                                <textarea name="inhoud" id="rapportage" rows="16" placeholder="Rapportage" class="form-control" required></textarea>
                            </div>
                        </div>
                    </div>
                    <button type="submit" name="aanmaken" class="btn btn-primary w-100 mt-3">Aanmaken</button>
                </form>
            </div>
        </div>
    </div>
</body>

<script src="../../assets/js/validatie.js"></script>

</html>
