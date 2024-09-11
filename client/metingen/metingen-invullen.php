<?php
session_start();
require_once('../../includes/auth.php');
include '../../database/DatabaseConnection.php';
include_once '../../classes/Main.php';
$Main = new Main();

$medewerker_id = $_SESSION['loggedin_id'];
$clientId = $_SESSION['clientId'];
if (!isset($clientId)) {
    header("Location: ../../inloggen");
}

$client = $Main->getClientById($clientId);

if ($client == null) {
    header("Location: ../../inloggen");
}

if (!isset($_SESSION['loggedin_id'])) {
    header("Location: ../../inloggen");
}

$samenStellingen = DatabaseConnection::getConn()->query("SELECT id, type, uiterlijk FROM samenstelling")->fetch_all(MYSQLI_ASSOC);
$pijnSchalen = DatabaseConnection::getConn()->query("SELECT pijnindex, pijnomschrijving FROM pijnkaart")->fetch_all(MYSQLI_ASSOC);

if (isset($_POST['metingen_invullen'])) {
    $hartslag = $_POST['hartslag'];
    $ademhaling = $_POST['ademhaling'];
    $bloeddruklaag = $_POST['bloeddruk'];
    $bloeddrukhoog = $_POST['bloeddruk2'];
    $temperatuur = $_POST['temperatuur'];
    $vochtinname = $_POST['vochtinname'];
    $uitscheiding = $_POST['uitscheiding'];
    $uitscheidingSamenstelling = $_POST['uitscheidingSamenstelling'];
    $uitscheidingUrine = $_POST['uitscheidingUrine'];
    $pijnschaal = $_POST['pijnschaal'];

    $verzorgerregelid = DatabaseConnection::getConn()->prepare("SELECT id FROM verzorgerregel WHERE clientid = ? AND medewerkerid = ?");
    $verzorgerregelid->bind_param("ii", $clientId, $medewerker_id);
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

    $metingUrineSamenstelling = DatabaseConnection::getConn()->prepare("INSERT INTO metingontlasting (metingid, samenstellingid, datumtijd, uitscheiding) VALUES (?, ?, ?, ?)");
    $metingUrineSamenstelling->bind_param("iisi", $metingId, $uitscheidingSamenstelling, $time, $uitscheiding);
    $metingUrineSamenstelling->execute();

    $_SESSION['succes'] = "Metingen zijn succesvol ingevuld";
    header("Location: metingen.php");
    exit;
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
    <link rel="stylesheet" href="../../assets/css/bootstrap.min.css">
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
    <div class="mt-4 mb-3 bg-white p-3" style="height: 96%; overflow: auto;">
        <p class="card-text">
        <form id="patientForm" method="POST" class="needs-validation" novalidate>
            <a href="metingen.php?id=<?= $clientId = $_SESSION['clientId'] ?>">Teruggaan</a>

            <?php echo '<input type="hidden" id="shown" value="'.$asisstent_bool.'">'; ?>

            <div class="row">
                <div class="col-lg-3 col-md-6 col-sm-12">
                    <div class="mb-3">
                        <label for="Hartslag">Hartslag:</label>
                        <input type="number" id="hartslag" name="hartslag" class="form-control" placeholder="slagen per minuut" required min="0" max="200"> <!-- o tot 200 -->
                    </div>
                </div>

                <div class="col-lg-3 col-md-6 col-sm-12">
                    <div class="mb-3">
                        <label for="Ademhaling">Ademhaling:</label>
                        <input type="number" id="ademhaling" name="ademhaling" placeholder="tussen 0 , 80" required min="0" max="80" class="form-control"> <!-- o tot 80 -->
                    </div>
                </div>

                <div class="col-lg-3 col-md-6 col-sm-12">
                    <div class="mb-3">
                        <label for="Bloed druk">Bloeddruk Hoog:</label>
                        <input type="text" id="bloeddruk2" name="bloeddruk2" placeholder="Hoog" required min="0" max="140" class="form-control"> <!-- o tot 140 -->
                    </div>
                </div>

                <div class="col-lg-3 col-md-6 col-sm-12">
                    <div class="mb-3">
                        <label for="Bloed druk">Bloeddruk Laag:</label>
                        <input type="text" id="bloeddruk" name="bloeddruk" placeholder="Laag" required min="0" max="140" class="form-control"> <!-- o tot 140 -->
                    </div>
                </div>
            </div>

            <div class="mb-3">
                <label for="Temperatuur">Temperatuur:</label>
                <input type="number" id="temperatuur" name="temperatuur" placeholder="b.v.b, 37.9" required min="34" max="42" class="form-control"> <!-- 34° tot 42° -->
            </div>

            <div class="mb-3">
                <label for="Vochtinname">Vochtinname:</label>
                <input type="number" id="vochtinname" name="vochtinname" placeholder="Invoeren in aantal milliliters" required min="0" max="5000" class="form-control"> <!-- o tot 5000 -->
            </div>

            <div class="row">
                <div class="col-lg-6 col-md-6 col-sm-12">
                    <div class="mb-3">
                        <label for="Uitscheiding">Uitscheiding:</label>
                        <input type="number" id="uitscheiding" name="uitscheiding" placeholder="Invoeren in frequentie per dag" class="form-control">
                    </div>
                </div>

                <div class="col-lg-6 col-md-6 col-sm-12">
                    <div class="mb-3">
                        <label for="uitscheidingSamenstelling">Uitscheiding samenstelling:</label>
                        <select id="uitscheidingSamenstelling" name="uitscheidingSamenstelling" class="form-select" required>
                            <option selected>Kies een optie</option>
                            <?php
                            foreach ($samenStellingen as $samenStelling) {
                                echo "<option value='$samenStelling[id]'>$samenStelling[uiterlijk]</option>";
                            }
                            ?>
                        </select>
                    </div>
                </div>
            </div>

            <div class="mb-3">
                <label for="Uitscheidingurine">Uitscheiding urine:</label>
                <input type="number" id="uitscheidingurine" name="uitscheidingUrine" placeholder="Invoeren in aantal milliliters" class="form-control" required>
            </div>

            <div class="mb-3">
                <label for="Pijnschaal">Pijnschaal:</label>
                <select id="pijnschaal" name="pijnschaal" class="form-select" required>
                    <option selected>Kies een optie</option>
                    <?php
                    foreach ($pijnSchalen as $pijnSchaal) {
                        echo "<option value='$pijnSchaal[pijnindex]'>$pijnSchaal[pijnomschrijving]</option>";
                    }
                    ?>
                </select>
            </div>

            <button class="mt-3 btn btn-secondary w-100" type="submit" name="metingen_invullen">Indienen</button>
        </form>

    </div>
    <script src="../../assets/js/bootstrap.bundle.min.js"></script>
</body>
</html>