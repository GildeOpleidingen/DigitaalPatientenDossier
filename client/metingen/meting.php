<?php
session_start();

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once('../../includes/auth.php');
include '../../database/DatabaseConnection.php';
include_once '../../classes/autoload.php';
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

if (!isset($_GET['m'])) {
    header("Location: metingen.php");
}

$samenStellingen = DatabaseConnection::getConn()->query("SELECT id, type, uiterlijk FROM samenstelling")->fetch_all(MYSQLI_ASSOC);
$pijnSchalen = DatabaseConnection::getConn()->query("SELECT pijnindex, pijnomschrijving FROM pijnkaart")->fetch_all(MYSQLI_ASSOC);

$query = "
    SELECT 
        m.*, 
        mo.*, 
        mu.* 
    FROM 
        meting m
    LEFT JOIN 
        metingontlasting mo ON m.id = mo.metingid
    LEFT JOIN 
        metingurine mu ON m.id = mu.metingid
    WHERE 
        m.id = ?
";
if ($stmt = DatabaseConnection::getConn()->prepare($query)) {
    $stmt->bind_param("i", $_GET['m']);
    $stmt->execute();
    $meting = $stmt->get_result()->fetch_assoc();
} else {
    $_SESSION['error'] = "Er is iets fout gegaan: " . DatabaseConnection::getConn()->error;
    header('Location: meting.php');
    exit;
}

if (isset($_POST['metingen_aanpassen'])) {
    $hartslag = $_POST['hartslag'];
    $ademhaling = $_POST['ademhaling'];
    $bloeddruk = $_POST['bloeddruk'];
    $bloeddruk2 = $_POST['bloeddruk2'];
    $temperatuur = $_POST['temperatuur'];
    $vochtinname = $_POST['vochtinname'];
    $uitscheiding = $_POST['uitscheiding'];
    $uitscheidingSamenstelling = $_POST['uitscheidingSamenstelling'];
    $uitscheidingUrine = $_POST['uitscheidingUrine'];
    $pijnschaal = $_POST['pijnschaal'];

    $query = "
        UPDATE 
            meting m
        LEFT JOIN 
            metingontlasting mo ON m.id = mo.metingid
        LEFT JOIN 
            metingurine mu ON m.id = mu.metingid
        SET 
            m.hartslag = ?, 
            m.ademhaling = ?, 
            m.bloeddrukhoog = ?, 
            m.bloeddruklaag = ?, 
            m.temperatuur = ?, 
            m.vochtinname = ?, 
            mo.uitscheiding = ?, 
            mo.samenstellingid = ?, 
            mu.hoeveelheid = ?, 
            m.pijn = ?
        WHERE 
            m.id = ?";
    if ($stmt = DatabaseConnection::getConn()->prepare($query)) {
        $stmt->bind_param("iiiiidiiiii", $hartslag, $ademhaling, $bloeddruk2, $bloeddruk, $temperatuur, $vochtinname, $uitscheiding, $uitscheidingSamenstelling, $uitscheidingUrine, $pijnschaal, $_GET['m']);
        $stmt->execute();

        $_SESSION['succes'] = "Meting is aangepast";
        header('Location: meting.php?m=' . $_GET['m']);
        exit;
    } else {
        $_SESSION['error'] = "Er is iets fout gegaan bij het voorbereiden van de query: " . DatabaseConnection::getConn()->error;
        header('Location: meting.php?m=' . $_GET['m']);
        exit;
    }
}

?>
<!doctype html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport"
        content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Meting <?= isset($_GET['m']) ?> aanpassen</title>
    <link rel="stylesheet" href="../../assets/css/client/metingen.css">
    <link rel="stylesheet" href="../../assets/css/bootstrap.min.css">
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
                    <?php if (isset($_SESSION['succes'])) { ?>
                        <div class="mb-3 alert alert-success" role="alert">
                            <?php echo $_SESSION['succes']; ?>
                        </div>
                    <?php unset($_SESSION['succes']);
                    } ?>
                    <?php if (isset($_SESSION['error'])) { ?>
                        <div class="alert alert-danger" role="alert">
                            <?= $_SESSION['error'] ?>
                        </div>
                        <?php unset($_SESSION['error']); ?>
                    <?php } ?>
                    <div class="flex-grow-1 d-flex flex-column">
                        <a href='metingen.php' class='mb-3 text-decoration-none text-primary fw-bold'><i class='fa-xs fa-solid fa-arrow-left'></i> Teruggaan</a>
                        <div class="row">
                            <div class="col-lg-3 col-md-6 col-sm-12">
                                <div class="mb-3">
                                    <label for="Hartslag">Hartslag</label>
                                    <input type="number" id="hartslag" name="hartslag" class="form-control" placeholder="slagen per minuut" required min="0" max="200" value="<?= isset($meting['hartslag']) ? $meting['hartslag'] : '' ?>"> <!-- o tot 200 -->
                                </div>
                            </div>

                            <div class="col-lg-3 col-md-6 col-sm-12">
                                <div class="mb-3">
                                    <label for="Ademhaling">Ademhaling</label>
                                    <input type="number" id="ademhaling" name="ademhaling" placeholder="tussen 0 , 80" required min="0" max="80" class="form-control" value="<?= isset($meting['ademhaling']) ? $meting['ademhaling'] : '' ?>"> <!-- o tot 80 -->
                                </div>
                            </div>

                            <div class="col-lg-3 col-md-6 col-sm-12">
                                <div class="mb-3">
                                    <label for="Bloed druk">Bloeddruk Hoog</label>
                                    <input type="text" id="bloeddruk2" name="bloeddruk2" placeholder="Hoog" required min="0" max="140" class="form-control" value="<?= isset($meting['bloeddrukhoog']) ? $meting['bloeddrukhoog'] : '' ?>"> <!-- o tot 140 -->
                                </div>
                            </div>

                            <div class="col-lg-3 col-md-6 col-sm-12">
                                <div class="mb-3">
                                    <label for="Bloed druk">Bloeddruk Laag</label>
                                    <input type="text" id="bloeddruk" name="bloeddruk" placeholder="Laag" required min="0" max="140" class="form-control" value="<?= isset($meting['bloeddruklaag']) ? $meting['bloeddruklaag'] : '' ?>"> <!-- o tot 140 -->
                                </div>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="Temperatuur">Temperatuur</label>
                            <input type="number" id="temperatuur" name="temperatuur" placeholder="b.v.b, 37.9" required min="34" max="42" class="form-control" value="<?= isset($meting['temperatuur']) ? $meting['temperatuur'] : '' ?>"> <!-- 34° tot 42° -->
                        </div>

                        <div class="mb-3">
                            <label for="Vochtinname">Vochtinname</label>
                            <input type="number" id="vochtinname" name="vochtinname" placeholder="Invoeren in aantal milliliters" required min="0" max="5000" class="form-control" value="<?= isset($meting['vochtinname']) ? $meting['vochtinname'] : '' ?>"> <!-- o tot 5000 -->
                        </div>

                        <div class="row">
                            <div class="col-lg-6 col-md-6 col-sm-12">
                                <div class="mb-3">
                                    <label for="Uitscheiding">Uitscheiding</label>
                                    <input type="number" id="uitscheiding" name="uitscheiding" placeholder="Invoeren in frequentie per dag" class="form-control" value="<?= isset($meting['uitscheiding']) ? $meting['uitscheiding'] : '' ?>">
                                </div>
                            </div>

                            <div class="col-lg-6 col-md-6 col-sm-12">
                                <div class="mb-3">
                                    <label for="uitscheidingSamenstelling">Uitscheiding samenstelling</label>
                                    <select id="uitscheidingSamenstelling" name="uitscheidingSamenstelling" class="form-select" required>
                                        <option>Kies een optie</option>
                                        <?php
                                        foreach ($samenStellingen as $samenStelling) {
                                            if ($meting['samenstellingid'] == $samenStelling['id']) {
                                                echo "<option value='$samenStelling[id]' selected>$samenStelling[uiterlijk]</option>";
                                                continue;
                                            } else {
                                                echo "<option value='$samenStelling[id]'>$samenStelling[uiterlijk]</option>";
                                            }
                                        }
                                        ?>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="Uitscheidingurine">Uitscheiding urine</label>
                            <input type="number" id="uitscheidingurine" name="uitscheidingUrine" placeholder="Invoeren in aantal milliliters" class="form-control" value="<?= isset($meting['hoeveelheid']) ? $meting['hoeveelheid'] : '' ?>" required>
                        </div>

                        <div class="mb-3">
                            <label for="Pijnschaal">Pijnschaal</label>
                            <select id="pijnschaal" name="pijnschaal" class="form-select" required>
                                <option selected>Kies een optie</option>
                                <?php
                                foreach ($pijnSchalen as $pijnSchaal) {
                                    if ($meting['pijn'] == $pijnSchaal['pijnindex']) {
                                        echo "<option value='$pijnSchaal[pijnindex]' selected>$pijnSchaal[pijnomschrijving]</option>";
                                        continue;
                                    } else {
                                        echo "<option value='$pijnSchaal[pijnindex]'>$pijnSchaal[pijnomschrijving]</option>";
                                    }
                                }
                                ?>
                            </select>
                        </div>
                    </div>

                    <button class="btn btn-secondary w-100" type="submit" name="metingen_aanpassen">Aanpassen</button>
                </form>

            </div>
            <script src="../../assets/js/bootstrap.bundle.min.js"></script>
</body>

</html>