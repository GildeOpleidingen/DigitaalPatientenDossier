<?php
session_start();
include_once '../../database/DatabaseConnection.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $rapportageInhoud = $_POST['inhoud'];
    $rapportageId = $_POST['rapportageId'];
    $returnUrl = $_POST['returnUrl']; // Haal de refererende URL op

    $tijd = date('Y-m-d H:i:s');
    $stmt = DatabaseConnection::getConn()->prepare("UPDATE rapport SET inhoud = ?, datumtijd = ? WHERE id = ?");
    $stmt->bind_param("ssi", $rapportageInhoud, $tijd, $rapportageId);
    $stmt->execute();
    $stmt->close();
    
    // Redirect naar de refererende URL na het aanpassen
    header("Location: " . $returnUrl);
    exit();
} else {
    $rapportageId = $_GET['id'];

    if (!$rapportageId) {
        header("Location: ../client.php");
        exit();
    }

    $rapportage = DatabaseConnection::getConn()->prepare("SELECT * FROM rapport WHERE id = ?");
    $rapportage->bind_param("i", $rapportageId);
    $rapportage->execute();
    $rapportage = $rapportage->get_result()->fetch_assoc();

    if ($rapportage == null) {
        header("Location: ../client.php");
        exit();
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="Stylesheet" href="rapportage.css">
    <link rel="stylesheet" href="../../assets/css/bootstrap.min.css">
    <title>Rapportage</title>
</head>
<body>
<div class="main">
    <?php
    include '../../includes/n-header.php';
    include '../../includes/n-sidebar.php';
    ?>

    <div class="content">
        <form method="POST">
            <input type="hidden" value="<?= $rapportage['id'] ?>" name="rapportageId">
            <input type="hidden" name="returnUrl" value="<?= $_SERVER['HTTP_REFERER'] ?>">
            <div class="rapportage">
                <h1>Rapportage aanpassen van <?= $rapportage['datumtijd'] ?> (<?= $rapportage['id'] ?>)</h1>
                <textarea name="inhoud" id="rapportage" placeholder="Rapportage" style="width: 100%; height: 100%; box-sizing: border-box;"><?= htmlspecialchars($rapportage['inhoud']) ?></textarea>
                <button class="rapportageButton" type="submit">Submit</button>
            </div>
        </form>
    </div>
</div>

</body>
</html>
