<?php
session_start();

if (!isset($_SESSION['loggedin_id']) || $_SESSION['rol'] != "beheerder") {
    header("Location: ../index.php");
}

include '../../Database/DatabaseConnection.php';
$error = "";

if (isset($_POST['submit'])) {
    $hashedPassword = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $email = $_POST['email'];
    $name = $_POST['name'];
    $rol = $_POST['rol'];
    $klas = $_POST['klas'];
    $telefoonnummer = $_POST['telefoonnummer'];
    
    // Check of de medewerker data al bestaat
    $result = DatabaseConnection::getConn()->query("SELECT `id`, `email`, `naam` FROM `medewerker`");
    $medewerkers = $result->fetch_all(MYSQLI_ASSOC);
    
    foreach ($medewerkers as $medewerker) {
        if ($medewerker['email'] == $email || $medewerker['naam'] == $name) {
            $error = "Medewerker bestaat al!";
        }
    }

    if ($error == "") {
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
        
        $result = DatabaseConnection::getConn()->prepare("INSERT INTO `medewerker` (naam, klas, foto, email, telefoonnummer, wachtwoord, rol) VALUES (?, ?, ?, ?, ?, ?, ?)");
        $result->bind_param("sssssss", $name, $klas, $foto, $email, $telefoonnummer, $hashedPassword, $rol);
        $result->execute();
    }
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="register.css">
    <title>Registreerpagina</title>
</head>

<body>
    <?php
    include '../../Includes/header.php';
    ?>
    <div class="main">
        <div class="content">
            <div class="form-content">
                <form method="post" class="registration-content" enctype="multipart/form-data">
                    <h1 class="error" id="status"><?= $error ?></h1>
                    <h1>Registreer</h1>
                    <div class="flex-column">
                        <label class="label-image">
                            <input type="file" id="file-selector" name="foto" accept="image/png, image/jpg, image/jpeg">
                            <img id="image" class="img-preview">
                        </label>
                        <input type="text" name="name" placeholder="Naam" required>
                        <input type="text" name="klas" placeholder="Klas" required>
                        <input type="text" name="telefoonnummer" placeholder="Telefoonnummer">
                        <input type="email" name="email" placeholder="E-mail" required>
                        <input type="password" name="password" placeholder="Wachtwoord" required>
                        <select name="rol" required>
                            <option value="medewerker">Medewerker</option>
                            <option value="beheerder">Beheerder</option>
                        </select>

                        <input type="submit" name="submit" value="Registreer">
                    </div>
                </form>
            </div>
        </div>
    </div>
    </form>
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
    </script>
</body>

</html>