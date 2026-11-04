<?php
session_start();
include_once '../../database/DatabaseConnection.php';
include_once '../../models/autoload.php';
$Main = new Main();
$ClientModel = new ClientModel();

$medewerkerid = isset($_GET['id']) ? (int)$_GET['id'] : 0;
if ($medewerkerid <= 0) {
    header("Location: ../../index.php");
    exit;
}

$pdo = DatabaseConnection::getPdo();
$stmt = $pdo->prepare("SELECT * FROM medewerker WHERE id = :id");
$stmt->execute(['id' => $medewerkerid]);
$verzorger = $stmt->fetch();

if (!$verzorger || (int)($verzorger['is_deleted'] ?? 0) === 1) {
    header("Location: ../medewerker.php");
    exit;
}

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="Stylesheet" href="../../assets/css/medewerker/overzicht.css">
    <link rel="stylesheet" href="../../assets/vendor/bootstrap/css/bootstrap.min.css">
    <title>Overzicht van <?= $verzorger['naam'] ?></title>
    <link rel="icon" type="image/x-icon" href="../../assets/images/favicon.ico">
</head>

<body>
    <div class="main">
        <?php
        include '../../includes/n-header.php';
        ?>

        <?php
        include '../../includes/medewerker-sidebar.php';
        ?>

        <div class="content">
            <div class="mt-4 mb-3 bg-white p-3" style="height: 96%; overflow: auto;">
                <p class="card-text">

                </p>
            </div>
        </div>
</body>

</html>