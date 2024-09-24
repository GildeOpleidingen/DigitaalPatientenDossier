<?php
session_start();
include_once '../../database/DatabaseConnection.php';

$rapportageId = $_GET['id'];

$rapportage = DatabaseConnection::getConn()->prepare("SELECT * FROM rapport WHERE id = ?");
$rapportage->bind_param("i", $rapportageId);
$rapportage->execute();
$rapportage = $rapportage->get_result()->fetch_assoc();

if ($rapportage == null) {
    header("Location: ../client.php");
}

if (!$rapportageId) {
    header("Location: ../client.php");
}

if (isset($_POST['aanpassen'])) {
    $rapportageInhoud = $_POST['inhoud'];
    $rapportageId = $_POST['rapportageId'];

    $rapportageInhoud = nl2br($rapportageInhoud);

    $tijd = date('Y-m-d H:i:s');
    $stmt = DatabaseConnection::getConn()->prepare("UPDATE rapport SET inhoud = ?, datumtijd = ? WHERE id = ?");
    $stmt->bind_param("ssi", $rapportageInhoud, $tijd, $rapportageId);
    $stmt->execute();
    $stmt->close();

    $_SESSION['succes'] = 'Rapportage aangepast.';
    header("Location: ./rapportage-aanpassen.php?id=$rapportageId");
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
    <title>Rapportage</title>
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
            <div class="mt-4 mb-3 bg-white p-3 d-flex flex-column" style="height: 96%; overflow: auto;">
                <form method="POST" class="needs-validation flex-grow-1 d-flex flex-column" novalidate>
                    <div class="flex-grow-1 d-flex flex-column">
                        <?php if (isset($_SESSION['succes'])) { ?>
                            <div class="alert alert-success" role="alert">
                                <?= $_SESSION['succes'] ?>
                            </div>
                            <?php session_unset(); ?>
                        <?php } ?>
                        <input type="hidden" value="<?= $rapportage['id'] ?>" name="rapportageId">
                        <div class="rapportage flex-grow-1">
                            <div class="display-5 text-primary mb-3">Rapportage aanpassen van <?= $rapportage['datumtijd'] ?> (<?= $rapportage['id'] ?>)</div>
                            <div class="mb-3">
                                <textarea name="inhoud" id="rapportage" rows="16" placeholder="Rapportage" class="form-control" required><?= $rapportage['inhoud'] ?></textarea>
                            </div>
                        </div>
                    </div>
                    <button type="submit" name="aanpassen" class="btn btn-secondary w-100 mt-3">Aanpassen</button>
                </form>
            </div>
        </div>
</body>

<script src="../../assets/js/validatie.js"></script>

</html>