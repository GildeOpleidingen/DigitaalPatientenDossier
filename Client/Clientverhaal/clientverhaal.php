<?php
include '../../Database/DatabaseConnection.php';
include '../../Functions/ClientFunctions.php';

if (!isset($_GET['id']) || !checkIfClientExistsById($_GET['id']) || !getMedischOverzichtByClientId($_GET['id'])) {
    header("Location: ../../index.php");
    exit;
}

if (isset($_SESSION['client'])) {
    unset($_SESSION['client']);
}
$client = $_SESSION['client'] = getClientById($_GET['id']);
if (checkIfClientStoryExistsByClientId($client['id'])) {
    $clientStory = getClientStoryByClientId($client['id']);
}


if (isset($_POST['submit'])) {
    if ($_FILES != null) {
        $file = $_FILES["foto"]["tmp_name"];

        if (isset($file) && $file != "") {
            $foto = file_get_contents($_FILES['foto']['tmp_name']);
            $foto_size = getimagesize($_FILES['foto']['tmp_name']);

            if ($foto_size == FALSE) { // Als de foto geen foto is dan wordt $foto leeg gemaakt
                $foto = "";
            }
        }
    }

    $introductie = $_POST['introductie'];
    $gezinfamilie = $_POST['gezinfamilie'];
    $hobbies = $_POST['hobbys'];
    $belangrijkeinfo = $_POST['belangrijkeinfo'];

    insertClientStory($client['id'], $foto ?? $clientStory['foto'], $introductie, $gezinfamilie, $belangrijkeinfo, $hobbies);
    header("Refresh:0");
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="Stylesheet" href="../../Includes/header.css">
    <link rel="Stylesheet" href="clientverhaal.css">

    <title>Cliëntverhaal invullen</title>
</head>
<?php include '../../Includes/header.php'; ?>

<body>
    <main>
        <form method="POST" enctype="multipart/form-data">
            <div>Foto:</div>
            Klik op de foto om het te veranderen
            
            <label>
                <img id="image" src="data:image/png;base64,<?= base64_encode($clientStory['foto']) ?? "" ?>" alt=" " width="200" height="200">
                <input type="file" name="image" accept=".png" value="" style="<?= $clientStory['foto'] ? "display:none" : "" ?>">
            </label>
            
            <div>Introductie: </div><input type="text" name="introductie" value=<?= $clientStory['introductie'] ?? "" ?>>
            <div>Gezin en familie: </div><input type="text" name="gezinfamilie" value=<?= $clientStory['gezinfamilie'] ?? "" ?>>
            <div>Hobby's: </div><input type="text" name="hobbys" value=<?= $clientStory['hobbies'] ?? "" ?>>
            <div>Belangrijke informatie voor omgang: </div><input type="text" name="belangrijkeinfo" value=<?= $clientStory['belangrijkeinfo'] ?? "" ?>>

            <input type="submit" name="submit">
        </form>
    </main>

    <script>
        if (window.history.replaceState) {
            window.history.replaceState(null, null, window.location.href);
        }
    </script>

</body>

</html>