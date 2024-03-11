<?php
session_start();
include '../../Database/DatabaseConnection.php';

$clientId = $_SESSION['clientId'];
if (!isset($clientId)) {
    header("Location: ../../index.php");
}

$client = getClientById($clientId);

if ($client == null) {
    header("Location: ../../index.php");
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
	<link rel="Stylesheet" href="formulieren.css">
    <title>Formulieren</title>
</head>
<body>
    <div class="main">
    <div class="main-content">
        <?php
            include '../../Includes/header.php';
        ?>

        <?php
            include '../../Includes/sidebar.php';
        ?>

        <div class="content">
            <div class="form-content">
                <div class="form">
                    <div class="inputs"><input type="text" placeholder="Zoek ingevulde vragenlijst"><div><p>Sorteren op</p><select><option value="1">Open</option><option value="1">Gesloten</option></select></div></div>
                </div>
                <div class="formulieren">
                        <div class="formulieren-content">
                            <p class="open">OPEN</p>
                            <div class="text">
                                 <strong><p>Verwijsformulier huisarts</p></strong>
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