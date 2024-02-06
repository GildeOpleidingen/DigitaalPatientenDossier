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

        <form id="patientForm">
            <!-- metingen -->
            <label for="Hartslag">Hartslag:</label>
            <input type="number" id="hartslag" name="hartslag" placeholder="slagen per minuut" required min="0" max="200"> <!-- o tot 200 -->

            <label for="Ademhaling">Ademhaling:</label>
            <input type="number" id="ademhaling" name="ademhaling" placeholder="tussen 0 , 80" required min="0" max="80"> <!-- o tot 80 -->

            <label for="Bloed druk">Bloeddruk:</label>
            <input type="number" id="bloeddruk" name="bloeddruk" placeholder="b.v.b, 80/120" required min="0" max="80"> <!-- o tot 80 -->

            <label for="Temperatuur">Temperatuur:</label>
            <input type="number" id="temperatuur" name="temperatuur" placeholder="b.v.b, 37.9°" required min="34" max="42"> <!-- 34° tot 42° -->

            <label for="Vochtinname">Vochtinname:</label>
            <input type="number" id="vochtinname" name="vochtinname" placeholder="Invoeren in aantal milliliters" required min="0" max="80"> <!-- o tot 80 -->

            <label for="Uitscheiding">Uitscheiding:</label>
            <input type="number" id="uitscheiding" name="uitscheiding" placeholder="Invoeren in frequentie per dag" required>

            <label for="Uitscheidingplas">Uitscheiding plas:</label>
            <input type="number" id="uitscheidingplas" name="uitscheidingPlas" placeholder="Invoeren in aantal milliliters" required>

            <label for="Pijnschaal">Pijnschaal:</label>
            <input type="number" id="pijnschaal" name="pijnschaal" placeholder="van 1 tot 10" required min="1" max="10">
            <br/><button class="metingButton" type="button" onclick="submit()">Submit</button>
        </form>

    </div>
        <script>
            const submitButton = document.getElementById('submitButton');
            const hartslag = document.getElementById('hartslag');
            const ademhaling = document.getElementById('ademhaling');
            const bloeddruk = document.getElementById('bloeddruk');
            const temperatuur = document.getElementById('temperatuur');
            const vochtinname = document.getElementById('vochtinname');
            const uitscheiding = document.getElementById('uitscheiding');
            const uitscheidingplas = document.getElementById('uitscheidingplas');
            const pijnschaal = document.getElementById('pijnschaal');


            hartslag.addEventListener('input', hartslagUpdate);
            ademhaling.addEventListener('input', ademHalingUpdate);
            bloeddruk.addEventListener('input', bloeddrukUpdate);
            temperatuur.addEventListener('input', temperatuurUpdate);
            vochtinname.addEventListener('input', vochtinnameUpdate);
            uitscheiding.addEventListener('input', uitscheidingUpdate);
            uitscheidingplas.addEventListener('input', uitscheidingplasUpdate);
            pijnschaal.addEventListener('input', pijnschaalUpdate);

            function hartslagUpdate() {
                if (hartslag) {
                    if (hartslag.value < 0 || hartslag.value > 200) {
                        hartslag.style.border = '5px solid red';
                    }else {
                        hartslag.style.border = '1px solid black';
                    }
                }else {
                    console.log("error");
                }
            }

            function ademHalingUpdate() {
                if (ademhaling) {
                    if (ademhaling.value < 0 || ademhaling.value > 80) {
                        ademhaling.style.border = '5px solid red';
                    }else {
                        ademhaling.style.border = '1px solid black';
                    }
                }else{
                    console.log('error');
                }
            }
            function bloeddrukUpdate() {
                if (bloeddruk) {
                    if (bloeddruk.value < 0 || bloeddruk.value > 80) {
                        bloeddruk.style.border = '5px solid red';
                    }else {
                        bloeddruk.style.border = '1px solid black';
                    }
                }else{
                    console.log('error');
                }
            }
            function temperatuurUpdate() {
                if (temperatuur) {
                    if (temperatuur.value <34 || temperatuur.value >42) {
                        temperatuur.style.border = '5px solid red';
                    }else {
                        temperatuur.style.border = '1px solid black';
                    }
                }else{
                    console.log('error');
                }
            }
            function vochtinnameUpdate() {
                if (vochtinname) {
                    if (vochtinname.value < 0 || vochtinname.value > 80) {
                        vochtinname.style.border = '5px solid red';
                    }else {
                        vochtinname.style.border = '1px solid black';
                    }
                }else{
                    console.log('error');
                }
            }
            function uitscheidingUpdate() {
                if (uitscheiding) {
                    if (!uitscheiding.value) {
                        uitscheiding.style.border = '5px solid red';
                    }else {
                        uitscheiding.style.border = '1px solid black';
                    }
                }else{
                    console.log('error');
                }
            }
            function uitscheidingplasUpdate() {
                if (uitscheidingplas) {
                    if (!uitscheidingplas.value) {
                        uitscheidingplas.style.border = '5px solid red';
                    }else {
                        uitscheidingplas.style.border = '1px solid black';
                    }
                }else{
                    console.log('error');
                }
            }
            function pijnschaalUpdate() {
                if (pijnschaal) {
                    if (pijnschaal.value < 1 || pijnschaal.value > 10) {
                        pijnschaal.style.border = '5px solid red';
                    }else {
                        pijnschaal.style.border = '1px solid black';
                    }
                }else{
                    console.log('error');
                }
            }
        </script>
</body>
</html>