<?php
session_start();
include_once '../../Database/DatabaseConnection.php';

if(!isset($_GET['id'])) {
    header("Location: ../client.php");
}

$id = $_GET['id'];
$_SESSION['clientId'] = $_GET['id'];

$client = DatabaseConnection::getConn()->prepare("SELECT * FROM client WHERE id = ?");
$client->bind_param("i", $id);
$client->execute();
$client = $client->get_result()->fetch_assoc();

if ($client == null) {
    header("Location: ../client.php");
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="Stylesheet" href="rapportage.css">
    <title>Rapportage van <?= $client['naam'] ?></title>
</head>
<body>
<div class="main">
        <?php
        include '../../Includes/header.php';
        ?>

        <?php
        include '../../Includes/sidebar.php';
        ?>

        <div class="content">
                    <form method="POST">
                        <div class="rapportage">
                            <textarea name="rapportage" id="rapportage" placeholder="Rapportage" style="width: 100%; height: 100%; box-sizing: border-box;"></textarea>
                            <button class="rapportageButton" type="button" onclick="submit()">Submit</button>
                        </div>

                    </form>
        </div>
    </div>

</body>
</html>
