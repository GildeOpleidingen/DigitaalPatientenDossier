<?php
session_start();
include '../../Database/DatabaseConnection.php';

$id = $_GET['id'];
$_SESSION['clientId'] = $_GET['id'];

if (!isset($id)) {
    header("Location: ../../index.php");
}

if (!isset($_SESSION['loggedin_id'])) {
    header("Location: ../../index.php");
}

$samenStellingen = DatabaseConnection::getConn()->query("SELECT id, type, uiterlijk FROM samenstelling")->fetch_all(MYSQLI_ASSOC);

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $hartslag = $_POST['hartslag'];
    $ademhaling = $_POST['ademhaling'];
    $bloeddruk = $_POST['bloeddruk'];
    $temperatuur = $_POST['temperatuur'];
    $vochtinname = $_POST['vochtinname'];
    $uitscheiding = $_POST['uitscheiding'];
    $uitscheidingSamenstelling = $_POST['uitscheidingSamenstelling'];
    $uitscheidingPlas = $_POST['uitscheidingPlas'];
    $pijnschaal = $_POST['pijnschaal'];

    $verzorgerregelid = DatabaseConnection::getConn()->query("SELECT id FROM verzorgerregel WHERE medewerkerid = $id")->fetch_array()[0];
    $time = date("Y-m-d H:i:s");

    $meting = DatabaseConnection::getConn()->prepare("INSERT INTO meting (verzorgerregelid, datumtijd, hartslag, ademhaling, bloeddruklaag, temperatuur, vochtinname, pijn, bloeddrukhoog) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
    $meting->bind_param("isiiiiiii", $verzorgerregelid, $time, $hartslag, $ademhaling, $bloeddruk, $temperatuur, $vochtinname, $pijnschaal, $uitscheidingPlas);
    $meting->execute();
    $metingId = $meting->insert_id;

    $metingUrine = DatabaseConnection::getConn()->prepare("INSERT INTO metingurine (metingid, datumtijd, hoeveelheid) VALUES (?, ?, ?)");
    $metingUrine->bind_param("isi", $metingId, $time, $uitscheidingPlas);
    $metingUrine->execute();

    $metingUrineSamenstelling = DatabaseConnection::getConn()->prepare("INSERT INTO metingontlasting (metingid, samenstellingid, datumtijd) VALUES (?, ?, ?)");
    $metingUrineSamenstelling->bind_param("iis", $metingId, $uitscheidingSamenstelling, $time);
    $metingUrineSamenstelling->execute();
}
?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Metingen</title>
    <link rel="stylesheet" href="metingen.css">
</head>
<body>
<?php
include_once '../../Includes/header.php';
?>
<div class="main">
    <?php
    include_once '../../Includes/sidebar.php';
    ?>
    <div class="main2">
        <div class="btns">
            <a href="metingen.php?id=1"><button type="button" class="MetingenInvul">Metingen invullen</button></a>
            <a href="metingenTabel.php?id=1"><button type="button" class="MetingenTabel">Metingen bekijken</button></a>
        </div>
    <div class="tabel">

    </div>
</body>
</html>