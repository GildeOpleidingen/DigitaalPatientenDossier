<?php
session_start();
include '../../Database/DatabaseConnection.php';
include_once '../../Functions/ClientFunctions.php';

$clientId = $_SESSION['clientId'];
if (!isset($clientId)) {
    header("Location: ../../index.php");
}

$client = getClientById($clientId);

if ($client == null) {
    header("Location: ../../index.php");
}

if (!isset($_SESSION['loggedin_id'])) {
    header("Location: ../../index.php");
}

$samenStellingen = DatabaseConnection::getConn()->query("SELECT id, type, uiterlijk FROM samenstelling")->fetch_all(MYSQLI_ASSOC);
$pijnSchalen = DatabaseConnection::getConn()->query("SELECT pijnindex, pijnomschrijving FROM pijnkaart")->fetch_all(MYSQLI_ASSOC);

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $hartslag = $_POST['hartslag'];
    $ademhaling = $_POST['ademhaling'];
    $bloeddruklaag = $_POST['bloeddruk'];
    $bloeddrukhoog = $_POST['bloeddruk2'];
    $temperatuur = $_POST['temperatuur'];
    $vochtinname = $_POST['vochtinname'];
    $uitscheiding = $_POST['uitscheiding'];
    $uitscheidingStool = $_POST['uitscheiding2'];
    $uitscheidingUrine = $_POST['uitscheidingUrine'];
    $pijnschaal = $_POST['pijnschaal'];
    $uitscheidingSamenstelling = $_POST['uitscheidingSamenstelling'];

    $verzorgerregelid = DatabaseConnection::getConn()->prepare("SELECT id FROM verzorgerregel WHERE medewerkerid = ?");
    $verzorgerregelid->bind_param("i", $_SESSION['loggedin_id']);
    $verzorgerregelid->execute();
    $verzorgerregelid = $verzorgerregelid->get_result()->fetch_array()[0];
    $time = date("Y-m-d H:i:s");

    $meting = DatabaseConnection::getConn()->prepare("INSERT INTO meting (verzorgerregelid, datumtijd, hartslag, ademhaling, bloeddruklaag, bloeddrukhoog, temperatuur, vochtinname, pijn) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
    $meting->bind_param("isiiiiiii", $verzorgerregelid, $time, $hartslag, $ademhaling, $bloeddruklaag, $bloeddrukhoog, $temperatuur, $vochtinname, $pijnschaal);
    $meting->execute();
    $metingId = $meting->insert_id;

    $metingUrine = DatabaseConnection::getConn()->prepare("INSERT INTO metingurine (metingid, datumtijd, hoeveelheid) VALUES (?, ?, ?)");
    $metingUrine->bind_param("isi", $metingId, $time, $uitscheidingUrine);
    $metingUrine->execute();

    $metingUrineSamenstelling = DatabaseConnection::getConn()->prepare("INSERT INTO metingontlasting (metingid, samenstellingid, datumtijd) VALUES (?, ?, ?)");
    $metingUrineSamenstelling->bind_param("iis", $metingId, $uitscheidingSamenstelling, $time);
    $metingUrineSamenstelling->execute();
}

$grens_asistent = DatabaseConnection::getConn()->prepare ("SELECT `grens_assistent` FROM `medewerker` WHERE id = $medewerker_id");
$grens_asistent->execute();
$asisstent_bool = $grens_asistent->get_result()->fetch_array()[0];
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
            <?php echo '<a href="metingeninvullen.php?id='.$clientId.'"><button type="button" class="MetingenInvul">Metingen invullen</button></a>'; ?>
            <?php echo '<a href="metingen.php?id='.$clientId.'"><button type="button" class="MetingenTabel">Metingen bekijken</button></a>'; ?>
        </div>
        <form id="patientForm" method="POST">
            <!-- metingen -->
            <?php echo '<input type="hidden" id="shown" value="'.$asisstent_bool.'">'; ?>
            <label for="Hartslag">Hartslag:</label>
            <input type="number" id="hartslag" name="hartslag" placeholder="slagen per minuut" required min="0" max="200"> <!-- o tot 200 -->

            <label for="Ademhaling">Ademhaling:</label>
            <input type="number" id="ademhaling" name="ademhaling" placeholder="tussen 0 , 80" required min="0" max="80"> <!-- o tot 80 -->

            <div class="bloeddrukken">
                <div class="bloeddruk-lengte">
                    <label for="Bloed druk">Bloeddruk Laag:</label>
                    <input type="text" id="bloeddruk" name="bloeddruk" placeholder="Laag" required min="0" max="140"> <!-- o tot 140 -->
                </div>
                <div class="bloeddruk-lengte">
                    <label for="Bloed druk">Bloeddruk Hoog:</label>
                    <input type="text" id="bloeddruk2" name="bloeddruk2" placeholder="Hoog" required min="0" max="140"> <!-- o tot 140 -->
                </div>
            </div>

            <label for="Temperatuur">Temperatuur:</label>
            <input type="number" id="temperatuur" name="temperatuur" placeholder="b.v.b, 37.9" required min="34" max="42"> <!-- 34° tot 42° -->

            <label for="Vochtinname">Vochtinname:</label>
            <input type="number" id="vochtinname" name="vochtinname" placeholder="Invoeren in aantal milliliters" required min="0" max="5000"> <!-- o tot 5000 -->

            <div class="Uitscheidingen">
                <div class="Uitscheiding2">
                    <label for="Uitscheiding">Uitscheiding:</label>
                    <input type="number" id="uitscheiding" name="uitscheiding" placeholder="Invoeren in frequentie per dag" >
                </div>
                <div class="Uitscheiding2">
                    <label for="Uitscheiding">Uitscheiding bristol stool chart:</label>
                    <input type="number" id="uitscheiding2" name="uitscheiding2" placeholder="Invoeren in frequentie per dag" >
                </div>
            </div>

            <label for="Uitscheidingurine">Uitscheiding urine:</label>
            <input type="number" id="uitscheidingurine" name="uitscheidingUrine" placeholder="Invoeren in aantal milliliters" required>

            <label for="UitscheidingSamenstelling">Uitscheiding samenstelling:</label>
            <select id="uitscheidingSamenstelling" name="uitscheidingSamenstelling" required>
                <?php
                foreach ($samenStellingen as $samenStelling) {
                    echo "<option value='$samenStelling[id]'>$samenStelling[uiterlijk]</option>";
                }
                ?>
            </select>

            <label for="Pijnschaal">Pijnschaal:</label>
            <select id="pijnschaal" name="pijnschaal" required>
                <?php
                foreach ($pijnSchalen as $pijnSchaal) {
                    echo "<option value='$pijnSchaal[pijnindex]'>$pijnSchaal[pijnomschrijving]</option>";
                }
                ?>
            </select>
            <br/><button class="metingButton" type="button" onclick="submit()">Submit</button>
        </form>

    </div>
    <script>

        const shown = document.getElementById("shown");

        if (shown.value === "1") {
            const hartslag = document.getElementById('hartslag');
            const ademhaling = document.getElementById('ademhaling');
            const bloeddruk = document.getElementById('bloeddruk');
            const bloeddruk2 = document.getElementById('bloeddruk2');
            const temperatuur = document.getElementById('temperatuur');
            const vochtinname = document.getElementById('vochtinname');
            const uitscheiding = document.getElementById('uitscheiding');
            const uitscheiding2 = document.getElementById('uitscheiding2');
            const uitscheidingplas = document.getElementById('uitscheidingplas');
            const pijnschaal = document.getElementById('pijnschaal');


            hartslag.addEventListener('input', hartslagUpdate);
            ademhaling.addEventListener('input', ademHalingUpdate);
            bloeddruk.addEventListener('input', bloeddrukUpdate);
            bloeddruk2.addEventListener('input', bloeddruk2Update);
            temperatuur.addEventListener('input', temperatuurUpdate);
            vochtinname.addEventListener('input', vochtinnameUpdate);
            uitscheiding.addEventListener('input', uitscheidingUpdate);
            uitscheiding2.addEventListener('input', uitscheiding2Update);
            uitscheidingplas.addEventListener('input', uitscheidingplasUpdate);
            pijnschaal.addEventListener('input', pijnschaalUpdate);

            function hartslagUpdate() {
                if (hartslag) {
                    if (hartslag.value < 0 || hartslag.value > 200) {
                        hartslag.style.border = '5px solid red';
                    } else {
                        hartslag.style.border = '1px solid black';
                    }
                } else {
                    console.log("error");
                }
            }

            function ademHalingUpdate() {
                if (ademhaling) {
                    if (ademhaling.value < 0 || ademhaling.value > 80) {
                        ademhaling.style.border = '5px solid red';
                    } else {
                        ademhaling.style.border = '1px solid black';
                    }
                } else {
                    console.log('error');
                }
            }

            function bloeddrukUpdate() {
                if (bloeddruk) {
                    if (bloeddruk.value < 0 || bloeddruk.value > 140) {
                        bloeddruk.style.border = '5px solid red';
                    } else {
                        bloeddruk.style.border = '1px solid black';
                    }
                } else {
                    console.log('error');
                }
            }

            function bloeddruk2Update() {
                if (bloeddruk2) {
                    if (bloeddruk2.value < 0 || bloeddruk2.value > 140) {
                        bloeddruk2.style.border = '5px solid red';
                    } else {
                        bloeddruk2.style.border = '1px solid black';
                    }
                } else {
                    console.log('error');
                }
            }

            function temperatuurUpdate() {
                if (temperatuur) {
                    if (temperatuur.value < 34 || temperatuur.value > 42) {
                        temperatuur.style.border = '5px solid red';
                    } else {
                        temperatuur.style.border = '1px solid black';
                    }
                } else {
                    console.log('error');
                }
            }

            function vochtinnameUpdate() {
                if (vochtinname) {
                    if (vochtinname.value < 0 || vochtinname.value > 5000) {
                        vochtinname.style.border = '5px solid red';
                    } else {
                        vochtinname.style.border = '1px solid black';
                    }
                } else {
                    console.log('error');
                }
            }

            function uitscheidingUpdate() {
                if (uitscheiding) {
                    if (!uitscheiding.value) {
                        uitscheiding.style.border = '5px solid red';
                    } else {
                        uitscheiding.style.border = '1px solid black';
                    }
                } else {
                    console.log('error');
                }
            }

            function uitscheiding2Update() {
                if (uitscheiding2) {
                    if (uitscheiding2.value < 1 || uitscheiding2.value > 7) {
                        uitscheiding2.style.border = '5px solid red';
                    } else {
                        uitscheiding2.style.border = '1px solid black';
                    }
                } else {
                    console.log('error');
                }
            }

            function uitscheidingplasUpdate() {
                if (uitscheidingplas) {
                    if (!uitscheidingplas.value) {
                        uitscheidingplas.style.border = '5px solid red';
                    } else {
                        uitscheidingplas.style.border = '1px solid black';
                    }
                } else {
                    console.log('error');
                }
            }

            function pijnschaalUpdate() {
                if (pijnschaal) {
                    if (pijnschaal.value < 0 || pijnschaal.value > 10) {
                        pijnschaal.style.border = '5px solid red';
                    } else {
                        pijnschaal.style.border = '1px solid black';
                    }
                } else {
                    console.log('error');
                }
            }
        }
    </script>

</body>
</html>