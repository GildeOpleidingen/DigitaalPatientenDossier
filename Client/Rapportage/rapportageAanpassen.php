<?php
session_start();
include_once '../../Database/DatabaseConnection.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $rapportageInhoud = $_POST['inhoud'];
    $rapportageId = $_POST['rapportageId'];

    $tijd = date('Y-m-d H:i:s');
    $stmt = DatabaseConnection::getConn()->prepare("UPDATE rapport SET inhoud = ?, datumtijd = ? WHERE id = ?");
    $stmt->bind_param("ssi", $rapportageInhoud, $tijd, $rapportageId);
    $stmt->execute();
    $stmt->close();
    
    header("Refresh:0");
    exit();
} else {
    $rapportageId = $_GET['id'];

    if(!$rapportageId) {
        header("Location: ../client.php");
    }

    if (!isset($_GET['id'])) {
        header("Location: ../client.php");
    }

    $rapportage = DatabaseConnection::getConn()->prepare("SELECT * FROM rapport WHERE id = ?");
    $rapportage->bind_param("i", $rapportageId);
    $rapportage->execute();
    $rapportage = $rapportage->get_result()->fetch_assoc();

    if ($rapportage == null) {
        header("Location: ../client.php");
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="Stylesheet" href="rapportage.css">
    <title>Rapportage</title>
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
                        <input type="hidden" value="<?= $rapportage['id'] ?>" name="rapportageId">
                        <div class="rapportage">
                            <h1>Rapportage aanpassen van <?= $rapportage['datumtijd'] ?> (<?= $rapportage['id'] ?>)</h1>
                            <textarea name="inhoud" id="rapportage" placeholder="Rapportage" style="width: 100%; height: 100%; box-sizing: border-box;"><?= $rapportage['inhoud'] ?></textarea>
                            <button class="rapportageButton" type="submit">Submit</button>
                        </div>

                    </form>
        </div>
    </div>

</body>
</html>
