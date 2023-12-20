<?php
include '../../Database/DatabaseConnection.php';
include '../../Functions/ClientFunctions.php';

if(!isset($_GET['id'])) {
    header("Location: ../../index.php");
    exit;
}

if(checkIfClientExistsById($_GET['id'])) {
    $client = $_SESSION['client'] = getClientById($_GET['id']);
    if(checkIfClientStoryExistsByClientId($client['id'])) {
        $clientStory = getClientStoryByClientId($client['id']);
    }
} else {
    header("Location: ../../index.php");
    exit;
}

if(isset($_POST['submit'])){
    // if(isset($_FILES['foto']) && $_FILES['foto']['error'] == 0) {
    //     $name = $_FILES['image']['name'];
    //     $type = $_FILES['image']['type'];
    //     $data = file_get_contents($_FILES['image']['tmp_name']);
    // }
    $foto = base64_encode($_POST['foto']);
    $introductie = $_POST['introductie'];
    $gezinfamilie = $_POST['gezinfamilie'];
    $hobbys = $_POST['hobbys'];
    $belangrijkeinfo = $_POST['belangrijkeinfo'];

    insertClientStory($client['id'], $foto, $introductie, $gezinfamilie, $hobbys, $belangrijkeinfo);
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
	<link rel="Stylesheet" href="../../Includes/header.css">
	<link rel="Stylesheet" href="cliëntverhaal.css">

    <title>Clientverhaal invullen</title>
</head>
<?php include '../../Includes/header.php'; ?>
<body>
    <main>
        <form method="POST">
            <h1>Foto:</h1>
            <div><input type="file" name="foto" accept=".png" value=<?= base64_decode($clientStory['foto']) ?? "" ?>></div>
            <div>Introductie: </div><input type="text" name="introductie" value=<?= $clientStory['introductie'] ?? "" ?>>
            <div>Gezin en familie: </div><input type="text" name="gezinfamilie" value=<?= $clientStory['gezinfamilie'] ?? "" ?>>
            <div>Hobby's: </div><input type="text" name="hobbys" value=<?= $clientStory['introductie'] ?? "" ?>>
            <div>Belangrijke informatie voor omgang: </div><input type="text" name="belangrijkeinfo" value=<?= $clientStory['introductie'] ?? "" ?>>

            <input type="submit" name="submit">
        </form>
    </main>
</body>
</html>