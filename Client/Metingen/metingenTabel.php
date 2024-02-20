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
            <?php
            echo "<a href='metingen.php?id=$id'><button type='button' class'MetingenInvul'>Metingen invullen</button></a>";
            echo "<a href='metingenTabel.php?id=$id'><button type='button' class='MetingenTabel'>Metingen bekijken</button></a>";
            ?>
        </div>
    <div class="tabel">

    </div>
</body>
</html>