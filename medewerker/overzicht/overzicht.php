<?php
session_start();
include_once '../../database/DatabaseConnection.php';
include_once '../../models/autoload.php';
$Main = new Main();

$medewerkerid = $_GET['id'];
if (!isset($medewerkerid)) {
    header("Location: ../../index.php");
}

$verzorger = DatabaseConnection::getConn()->prepare("SELECT * FROM medewerker WHERE id = ?");
$verzorger->bind_param("i", $medewerkerid);
$verzorger->execute();
$verzorger = $verzorger->get_result()->fetch_assoc();

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="Stylesheet" href="../../assets/css/medewerker/overzicht.css">
    <link rel="stylesheet" href="../../assets/css/bootstrap.min.css">
    <title>Overzicht van <?= $verzorger['naam'] ?></title>
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