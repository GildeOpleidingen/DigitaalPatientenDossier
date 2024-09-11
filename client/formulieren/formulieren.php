<?php
session_start();
require_once('../../includes/auth.php');
include '../../database/DatabaseConnection.php';
include_once '../../classes/Main.php';
$Main = new Main();

$clientId = $_SESSION['clientId'];
if (!isset($clientId)) {
    header("Location: ../../inloggen");
}

$client = $Main->getClientById($clientId);

if ($client == null) {
    header("Location: ../../inloggen");
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="Stylesheet" href="formulieren.css">
    <link rel="stylesheet" href="../../assets/css/bootstrap.min.css">
    <title>Formulieren</title>
</head>

<body>
    <div class="main">
        <?php
        include '../../includes/n-header.php';
        ?>

        <?php
        include '../../includes/n-sidebar.php';
        ?>

        <div class="content">
            <div class="mt-4 mb-3 bg-white p-3" style="height: 96%; overflow: auto;">
                <p class="card-text">
                <div class="form">
                    <div class="inputs"><input type="text" placeholder="Zoek ingevulde vragenlijst">
                        <div>
                            <p>Sorteren op</p><select>
                                <option value="1">Open</option>
                                <option value="1">Gesloten</option>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="formulieren">
                    <div class="formulieren-content">
                        <p class="open">OPEN</p>
                        <div class="text">
                            <strong>
                                <p>Verwijsformulier huisarts</p>
                            </strong>
                            <p>Aangemaakt op 06-11-2022 door F. Janccen</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    </div>

</body>

</html>