<?php
session_start();
include '../../database/DatabaseConnection.php';
include_once '../../classes/Main.php';
$Main = new Main();

$clientId = $_SESSION['clientId'];
if (!isset($clientId) || !$Main->checkIfClientExistsById($clientId) || !$Main->getMedischOverzichtByClientId($clientId)) {
    header("Location: ../../inloggen");
    exit;
} 

if (isset($_SESSION['client'])) {
    unset($_SESSION['client']);
}

$client = $_SESSION['client'] = $Main->getClientById($clientId);
if ($Main->checkIfClientStoryExistsByClientId($client['id'])) {
    $clientStory = $Main->getClientStoryByClientId($client['id']);
}

if (isset($_POST['submit'])) {
    if ($_FILES != null) {
        $file = $_FILES["foto"]["tmp_name"] ?? "";

        if (isset($file) && $file != "") {
            $foto = file_get_contents($file);
            $foto_size = getimagesize($file);

            if ($foto_size == FALSE) { // Als de foto geen foto is dan wordt $foto leeg gemaakt
                $foto = null;
                $_SESSION['error'] = "Er is geen foto geupload.";
            }

            // Check of de foto niet groter is dan 16 mb omdat een mediumblob maximaal 16 mb kan zijn
            if ($_FILES["foto"]["size"] > 16000000) {
                $foto = null;
                $_SESSION['error'] = "Het bestand is te groot, het bestand mag maximaal 16mb groot zijn.";
            }
        }
    }

    $introductie = $_POST['introductie'];
    $gezinfamilie = $_POST['gezinfamilie'];
    $hobbies = $_POST['hobbies'];
    $belangrijkeinfo = $_POST['belangrijkeinfo'];

    if($Main->insertClientStory($client['id'], $foto ?? $clientStory['foto'] ?? "", $introductie, $gezinfamilie, $belangrijkeinfo, $hobbies)){
        $_SESSION['success'] = "De gegevens zijn succesvol bijgewerkt!";
    }
    $clientStory = $Main->getClientStoryByClientId($client['id']); // Update de informatie van de clientstory
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="Stylesheet" href="../../assets/css/header.css">
    <link rel="Stylesheet" href="../../assets/css/sidebar.css">
    <link rel="Stylesheet" href="clientverhaal.css">

    <title>Cliëntverhaal invullen</title>
</head>
<?php include '../../includes/header.php'; ?>

<body>
    <div class="main">
        <?php include '../../includes/sidebar.php'; ?>
        <div class="main2">
            <form class="invulformulier" method="POST" enctype="multipart/form-data">
                <p>Foto:</p>
                <label class="img-flex">
                    <img id="image" src="data:image/png;base64,<?= base64_encode($clientStory['foto'] ?? "") ?? "" ?>" alt=" " width="200" height="200">
                    <p id="status" style="color:red"><?= $_SESSION['error'] ?? "" ?></p>
                    <input type="file" id="file-selector" name="foto" accept="image/png, image/jpg, image/jpeg">
                </label>
                
                <p>Introductie: </p><textarea name="introductie"><?= $clientStory['introductie'] ?? "" ?></textarea>
                <p>Gezin en familie: </p><textarea name="gezinfamilie"><?= $clientStory['gezinfamilie'] ?? "" ?></textarea>
                <p>Hobbies: </p><textarea name="hobbies"><?= $clientStory['gezinfamilie'] ?? "" ?></textarea>
                <p>Belangrijke informatie voor omgang: </p><textarea name="belangrijkeinfo"><?= $clientStory['gezinfamilie'] ?? "" ?></textarea>
                <p style="color:green"><?= $_SESSION['success'] ?? "" ?></p>
                <input type="submit" name="submit" class="button" value="Update clientverhaal">
            </form>
        </div>
    </div>

    <?php unset($_SESSION['error'])?>
    <?php unset($_SESSION['success'])?>

    <script>
        const status = document.getElementById('status');
        const output = document.getElementById('image');
        if (window.FileList && window.File && window.FileReader) {
            document.getElementById('file-selector').addEventListener('change', event => {
                output.src = '';
                status.textContent = '';
                const file = event.target.files[0];
                if (!file.type) {
                    status.textContent = 'Het bestand die je probeert te gebruiken is geen .jpg, .jpeg of .png';
                    return;
                }
                if (!file.type.match('image.*')) {
                    status.textContent = 'Het bestand die je probeert te gebruiken is geen foto';
                    return;
                }
                const reader = new FileReader();
                reader.addEventListener('load', event => {
                    output.src = event.target.result;
                });
                reader.readAsDataURL(file);
            });
        }

        if (window.history.replaceState) {
            window.history.replaceState(null, null, window.location.href);
        }
    </script>

</body>

</html>