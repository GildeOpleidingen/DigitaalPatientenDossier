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

$medewerkers = DatabaseConnection::getConn()->prepare("SELECT * FROM medewerker");
$medewerkers->execute();
$medewerkers = $medewerkers->get_result()->fetch_all(MYSQLI_ASSOC);

// Loop door alle medewerkers heen, als de medewerker al in de database staat, zet dan de checked variabele op true
foreach ($medewerkers as $key => $medewerker) {
    $stmt = DatabaseConnection::getConn()->prepare("SELECT * FROM verzorgerregel WHERE clientid = ? AND medewerkerid = ?");
    $stmt->bind_param("ii", $id, $medewerker['id']);
    $stmt->execute();
    $stmt = $stmt->get_result()->fetch_assoc();

    if ($stmt) {
        $medewerkers[$key]['checked'] = true;
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="Stylesheet" href="verzorgers.css">
    <title>Verzorgers van <?= $client['naam'] ?></title>
</head>
<body>
<div class="main">
        <?php
        include '../../Includes/header.php';
        ?>

        <?php
        include '../../Includes/sidebar.php';
        ?>

        <form action="Verzorger/verwerk.php" method="post" class="content">
            <input type="hidden" name="clientId" value="<?= $id ?>">
            <?php if (isset($_SESSION['verzorgersUpdated'])) { ?>
                <div class="successMessage">
                    <h1>De verzorgers zijn succesvol aangepast.</h1>
                </div>
            <?php
                unset($_SESSION['verzorgersUpdated']);
            } ?>
            <div class="form-content">
                <div class="pages">Verzorgers van <?= $client['naam'] ?></div>
                <div class="form">
                    <div class="questionnaire">
                            <?php foreach ($medewerkers as $medewerker) { ?>
                                <div class="question"><p><?= $medewerker['naam'] ?></p>
                                    <div class="checkboxes">
                                        <div>
                                            <input type="hidden" name="verzorgers[<?= $medewerker['id'] ?>]"> <!-- Hidden input om de waarde mee te geven als hij niet gecheckt is -->
                                            <input type="checkbox" name="verzorgers[<?= $medewerker['id'] ?>]" <?php if (isset($medewerker['checked'])) { echo "checked"; } ?>>
                                        </div>
                                    </div>
                                </div>
                            <?php } ?>
                    </div>
                </div>
                <div class="submit">
                    <button type="submit">Opslaan</button>
                </div>
            </div>
        </form>
        </div>
</div>

</body>
</html>
