<?php
session_start();
require_once('../../includes/auth.php');
include '../../database/DatabaseConnection.php';
include_once '../../models/autoload.php';
$Main = new Main();

$medewerker_id = $_SESSION['loggedin_id'];
$clientId = $_SESSION['clientId'];
if (!isset($clientId)) {
    header("Location: ../../index.php");
}

$client = $Main->getClientById($clientId);

if ($client == null) {
    header("Location: ../../index.php");
}

if (!isset($_SESSION['loggedin_id'])) {
    header("Location: ../../index.php");
}

$pdo = DatabaseConnection::getPdo();

$samenStellingen = $pdo->query("SELECT id, type, uiterlijk FROM samenstelling")->fetchAll();
$pijnSchalen = $pdo->query("SELECT pijnindex, pijnomschrijving FROM pijnkaart")->fetchAll();

if (isset($_POST['metingen_invullen'])) {
    $hartslag                  = $_POST['hartslag'];
    $ademhaling                 = $_POST['ademhaling'];
    $bloeddruklaag              = $_POST['bloeddruk'];
    $bloeddrukhoog              = $_POST['bloeddruk2'];
    $temperatuur                = $_POST['temperatuur'];
    $vochtinname                = $_POST['vochtinname'];
    $uitscheiding               = $_POST['uitscheiding'];
    $uitscheidingSamenstelling  = $_POST['uitscheidingSamenstelling'];
    $uitscheidingUrine          = $_POST['uitscheidingUrine'];
    $pijnschaal                 = $_POST['pijnschaal'];

    $stmtVr = $pdo->prepare("
        SELECT id 
        FROM verzorgerregel 
        WHERE clientid = :clientId AND medewerkerid = :medewerkerId 
        ORDER BY id DESC 
        LIMIT 1
    ");
    $stmtVr->execute([
        'clientId'     => $clientId,
        'medewerkerId' => $medewerker_id
    ]);
    $verzorgerregelid = (int)$stmtVr->fetchColumn();

    $time = date("Y-m-d H:i:s");

    $stmtMeting = $pdo->prepare("
        INSERT INTO meting (
            verzorgerregelid, datumtijd, hartslag, ademhaling, 
            bloeddruklaag, bloeddrukhoog, temperatuur, vochtinname, pijn
        ) VALUES (
            :verzorgerregelid, :datumtijd, :hartslag, :ademhaling, 
            :bloeddruklaag, :bloeddrukhoog, :temperatuur, :vochtinname, :pijn
        )
    ");
    $stmtMeting->execute([
        'verzorgerregelid' => $verzorgerregelid,
        'datumtijd'        => $time,
        'hartslag'         => $hartslag,
        'ademhaling'        => $ademhaling,
        'bloeddruklaag'     => $bloeddruklaag,
        'bloeddrukhoog'     => $bloeddrukhoog,
        'temperatuur'       => $temperatuur,
        'vochtinname'       => $vochtinname,
        'pijn'              => $pijnschaal
    ]);
    $metingId = (int)$pdo->lastInsertId();

    $stmtUrine = $pdo->prepare("
        INSERT INTO metingurine (metingid, datumtijd, hoeveelheid) 
        VALUES (:metingid, :datumtijd, :hoeveelheid)
    ");
    $stmtUrine->execute([
        'metingid'    => $metingId,
        'datumtijd'   => $time,
        'hoeveelheid' => $uitscheidingUrine
    ]);

    $stmtOntlasting = $pdo->prepare("
        INSERT INTO metingontlasting (metingid, samenstellingid, datumtijd, uitscheiding) 
        VALUES (:metingid, :samenstellingid, :datumtijd, :uitscheiding)
    ");
    $stmtOntlasting->execute([
        'metingid'        => $metingId,
        'samenstellingid' => $uitscheidingSamenstelling,
        'datumtijd'       => $time,
        'uitscheiding'    => $uitscheiding
    ]);

    $_SESSION['succes'] = "Metingen zijn succesvol ingevuld";
    header("Location: metingen.php");
    exit;
}

$stmtGrens = $pdo->prepare("
    SELECT grens_assistent 
    FROM medewerker 
    WHERE id = :medewerkerId
");
$stmtGrens->execute(['medewerkerId' => $medewerker_id]);
$asisstent_bool = $stmtGrens->fetchColumn();
?>
<!doctype html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport"
        content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Metingen</title>
    <link rel="icon" type="image/x-icon" href="../../assets/images/favicon.ico">
    <link rel="stylesheet" href="../../assets/css/client/metingen.css">
    <link rel="stylesheet" href="../../assets/vendor/bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css" integrity="sha512-Kc323vGBEqzTmouAECnVceyQqyqdsSiqLQISBL29aUW4U/M7pSPA/gEUZQqv1cwx4OnYxTxve5UMg5GT6L4JJg==" crossorigin="anonymous" referrerpolicy="no-referrer" />
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
                <p class="card-text">
                <form id="patientForm" method="POST" class="needs-validation flex-grow-1 d-flex flex-column" novalidate>
                    <div class="flex-grow-1 d-flex flex-column">
                        <a href='metingen.php' class='mb-3 text-decoration-none text-primary fw-bold'><i class='fa-xs fa-solid fa-arrow-left'></i> Teruggaan</a>
                        <?php echo '<input type="hidden" id="shown" value="' . $asisstent_bool . '">'; ?>

                        <div class="row">
                            <div class="col-lg-3 col-md-6 col-sm-12">
                                <div class="mb-3">
                                    <label for="Hartslag">Hartslag</label>
                                    <input type="number" id="hartslag" name="hartslag" class="form-control" placeholder="slagen per minuut" required min="0" max="200"> <!-- o tot 200 -->
                                </div>
                            </div>

                            <div class="col-lg-3 col-md-6 col-sm-12">
                                <div class="mb-3">
                                    <label for="Ademhaling">Ademhaling</label>
                                    <input type="number" id="ademhaling" name="ademhaling" placeholder="tussen 0 , 80" required min="0" max="80" class="form-control"> <!-- o tot 80 -->
                                </div>
                            </div>

                            <div class="col-lg-3 col-md-6 col-sm-12">
                                <div class="mb-3">
                                    <label for="Bloed druk">Bloeddruk Hoog</label>
                                    <input type="text" id="bloeddruk2" name="bloeddruk2" placeholder="Hoog" required min="0" max="140" class="form-control"> <!-- o tot 140 -->
                                </div>
                            </div>

                            <div class="col-lg-3 col-md-6 col-sm-12">
                                <div class="mb-3">
                                    <label for="Bloed druk">Bloeddruk Laag</label>
                                    <input type="text" id="bloeddruk" name="bloeddruk" placeholder="Laag" required min="0" max="140" class="form-control"> <!-- o tot 140 -->
                                </div>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="Temperatuur">Temperatuur</label>
                            <input type="number" id="temperatuur" name="temperatuur" placeholder="b.v.b, 37.9" required min="34" max="42" class="form-control"> <!-- 34° tot 42° -->
                        </div>

                        <div class="mb-3">
                            <label for="Vochtinname">Vochtinname</label>
                            <input type="number" id="vochtinname" name="vochtinname" placeholder="Invoeren in aantal milliliters" required min="0" max="5000" class="form-control"> <!-- o tot 5000 -->
                        </div>

                        <div class="row">
                            <div class="col-lg-6 col-md-6 col-sm-12">
                                <div class="mb-3">
                                    <label for="Uitscheiding">Uitscheiding</label>
                                    <input type="number" id="uitscheiding" name="uitscheiding" placeholder="Invoeren in frequentie per dag" class="form-control">
                                </div>
                            </div>

                            <div class="col-lg-6 col-md-6 col-sm-12">
                                <div class="mb-3">
                                    <label for="uitscheidingSamenstelling">Uitscheiding samenstelling</label>
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
                            <label for="Uitscheidingurine">Uitscheiding urine</label>
                            <input type="number" id="uitscheidingurine" name="uitscheidingUrine" placeholder="Invoeren in aantal milliliters" class="form-control" required>
                        </div>

                        <div class="mb-3">
                            <label for="Pijnschaal">Pijnschaal</label>
                            <select id="pijnschaal" name="pijnschaal" class="form-select" required>
                                <option selected>Kies een optie</option>
                                <?php
                                foreach ($pijnSchalen as $pijnSchaal) {
                                    echo "<option value='$pijnSchaal[pijnindex]'>$pijnSchaal[pijnomschrijving]</option>";
                                }
                                ?>
                            </select>
                        </div>
                    </div>

                    <button class="btn btn-secondary w-100" type="submit" name="metingen_invullen">Indienen</button>
                </form>

            </div>
</body>

</html>