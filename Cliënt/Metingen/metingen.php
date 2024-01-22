<?php
session_start();
include '../../Database/DatabaseConnection.php';

$id = $_GET['id'];
$_SESSION['clientId'] = $_GET['id'];
?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
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
        <form id="patientForm">
            <!-- metingen -->
            <label for="Hardslag">Hard slag:</label>
            <input type="number" id="hardSlag" name="hartSlag" placeholder="slagen per minuut" required min="0" max="200">

            <label for="Ademhaling">Ademhaling:</label>
            <input type="number" id="ademhaling" name="ademhaling" placeholder="tussen 0 , 80" required min="0" max="80"> <!-- o tot 80 -->

            <label for="Bloed druk">Bloed druk:</label>
            <input type="number" id="bloedDruk" name="bloedDruk" placeholder="b.v.b, 80/120" required min="0" max="80">

            <label for="Temperatuur">Temperatuur:</label>
            <input type="number" id="temperatuur" name="temperatuur" placeholder="b.v.b, 37.9°" required min="34" max="42"> <!-- 34° tot 42° -->

            <label for="Vochtinname">Vochtinname:</label>
            <input type="number" id="vochtinname" name="vochtinname" placeholder="Invoeren in aantal milliliters" required min="0" max="80"> <!-- o tot 80 -->

            <label for="Uitscheiding">Uitscheiding:</label>
            <input type="number" id="uitscheiding" name="uitscheiding" placeholder="Invoeren in frequentie per dag" required>

            <label for="Uitscheidingplas">Uitscheiding plas:</label>
            <input type="number" id="uitscheidingPlas" name="uitscheidingPlas" placeholder="Invoeren in aantal milliliters" required>

            <label for="Pijnschaal">Pijnschaal:</label>
            <input type="number" id="pijnSchaal" name="pijnSchaal" placeholder="van 1 tot 10" required min="1" max="10">
            <br/><button type="button" onclick="submitForm()">Submit</button>
            <?php
            ?>
        </form>

    </div>
</body>
</html>
