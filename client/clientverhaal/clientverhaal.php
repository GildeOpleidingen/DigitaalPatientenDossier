<?php
session_start();
include '../../database/DatabaseConnection.php';
include_once '../../classes/autoload.php';
$Main = new Main();

$clientId = $_SESSION['clientId'];
if (!isset($clientId) || !$Main->checkIfClientExistsById($clientId) || !$Main->getMedischOverzichtByClientId($clientId)) {
    header("Location: ../../index.php");
    exit;
}

if (isset($_SESSION['client'])) {
    unset($_SESSION['client']);
}

$client = $_SESSION['client'] = $Main->getClientById($clientId);
if ($Main->checkIfClientStoryExistsByClientId($client['id'])) {
    $clientStory = $Main->getClientStoryByClientId($client['id']);
}

$clientStory = $Main->getClientStoryByClientId($client['id']);

if (isset($_POST['clientverhaal-opslaan'])) {
    if ($_FILES != null) {
        $file = $_FILES["foto"]["tmp_name"] ?? "";

        if (isset($file) && $file != "") {
            $foto = file_get_contents($file);
            $foto_size = getimagesize($file);

            if ($foto_size == FALSE) { // Als de foto geen foto is dan wordt $foto leeg gemaakt
                $foto = null;
                $_SESSION['error'] = "Er is geen foto geupload.";
                header("Location: ./clientverhaal.php");
                exit;
            }

            // Check of de foto niet groter is dan 16 mb omdat een mediumblob maximaal 16 mb kan zijn
            if ($_FILES["foto"]["size"] > 16000000) {
                $foto = null;
                $_SESSION['error'] = "Het bestand is te groot, het bestand mag maximaal 16MB groot zijn.";
                header("Location: ./clientverhaal.php");
                exit;

            }
        }
    }

    $introductie = $_POST['introductie'];
    $gezinfamilie = $_POST['gezinfamilie'];
    $hobbies = $_POST['hobbies'];
    $belangrijkeinfo = $_POST['belangrijkeinfo'];

    $result = $Main->insertClientStory($client['id'], $foto ?? $clientStory['foto'] ?? "", $introductie, $gezinfamilie, $belangrijkeinfo, $hobbies);

    if ($result === true) {
        $_SESSION['succes'] = "De gegevens zijn succesvol bijgewerkt!";
        header("Location: ./clientverhaal.php");
        exit;
    } else {
        $_SESSION['error'] = $result; 
        header("Location: ./clientverhaal.php");
        exit;
    }
    
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../../assets/css/bootstrap.min.css">
    <link rel="Stylesheet" href="../../assets/css/client/clientverhaal.css">

    <title>Cliëntverhaal invullen</title>
</head>
<?php include '../../includes/n-header.php'; ?>

<body>
    <div class="main">
        <?php include '../../includes/n-sidebar.php'; ?>

        <div class="content">
            <div class="mt-4 mb-3 bg-white p-3" style="height: 96%; overflow: auto;">
                <p class="card-text">
                <?php if(isset($_SESSION['succes'])){ ?>
                <div class="mb-3 alert alert-success" role="alert">
                    <?php echo $_SESSION['succes']; ?>
                </div>
                <?php unset($_SESSION['succes']); } ?>
                <?php if(isset($_SESSION['error'])){ ?>
                <div class="mb-3 alert alert-danger" role="alert">
                    <?php echo $_SESSION['error']; ?>
                </div>
                <?php unset($_SESSION['error']); } ?>
                <form class="invulformulier" method="POST" enctype="multipart/form-data">
                    <div class="mb-3">
                        <label for="formFile" class="form-label">Foto</label>
                        <input class="form-control" type="file" id="file-selector" name="foto" accept="image/png, image/jpg, image/jpeg">
                        <?php if (isset($clientStory['foto'])){ ?>
                        <img id="image" src="data:image/png;base64,<?= base64_encode($clientStory['foto'] ?? "") ?? "" ?>" alt=" " width="200" height="200">
                        <?php } ?>
                    </div>

                    <div class="mb-3">
                        <label for="introductie">Introductie</label>
                        <textarea name="introductie" class="form-control"><?= $clientStory['introductie'] ?? "" ?></textarea>
                    </div>
                    
                    <div class="mb-3">
                        <label for="gezinfamilie">Gezin en familie</label>
                        <textarea name="gezinfamilie" class="form-control"><?= $clientStory['gezinfamilie'] ?? "" ?></textarea>
                    </div>
                    
                    <div class="mb-3 w-100">
                        <label for="hobbies">Hobbies</label=>
                        <textarea name="hobbies" class="form-control w-100"><?= $clientStory['gezinfamilie'] ?? "" ?></textarea>
                    </div>
                    
                    <div class="mb-3">
                        <label for="belangrijkeinfo">Belangrijke informatie voor omgang</label>
                        <textarea name="belangrijkeinfo" class="form-control"><?= $clientStory['gezinfamilie'] ?? "" ?></textarea>
                    </div>
                    
                    <button type="submit" name="clientverhaal-opslaan" class="btn btn-secondary w-100">Opslaan</button>
                </form>
            </div>
        </div>

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