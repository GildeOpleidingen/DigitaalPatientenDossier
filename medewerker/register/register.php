<?php
session_start();

if (!isset($_SESSION['loggedin_id'])) {
    header("Location: index.php");
}

include '../../database/DatabaseConnection.php';
$_SESSION['error'] = "";
$success = "";

if (isset($_POST['submit'])) {
    $hashedPassword = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $email = $_POST['email'];
    $name = $_POST['name'];
    $rol = $_POST['rol'];
    $klas = $_POST['klas'];
    $telefoonnummer = $_POST['telefoonnummer'];
    
    if($_POST['password'] != $_POST['confirmPassword']){
        $_SESSION['error'] = "Wachtwoorden komen niet overeen!";
    }

    // Deze values zijn required en die mogen dus niet empty zijn
    if(empty($email) || empty($name) || empty($rol) || empty($_POST['password']) || empty($klas)){
        $_SESSION['error'] = "Vul alle velden in!";
    }
    
    // Check of de medewerker data al bestaat
    $result = DatabaseConnection::getConn()->prepare("SELECT `email` FROM `medewerker` WHERE email = ?;");
    $result->bind_param('s', $email);
    $result->execute();
    if ($result->get_result()->num_rows > 0) {
        $_SESSION['error'] = "Deze medewerker bestaat al!";
    }

    if ($_SESSION['error'] == "") {
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
        $success = "Medewerker toegevoegd!";
    }
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../../assets/css/medewerker/register.css">
    <title>Registreerpagina</title>
</head>

<body>
    <?php
    include '../../includes/header.php';
    ?>
    <div class="main">
        <div class="content">
            <div class="form-content">
                <form method="post" class="registration-content" enctype="multipart/form-data">
                    <h1 class="error" id="status"><?= $_SESSION['error'] ?></h1>
                    <h1 class="success" id="status"><?= $success ?></h1>
                    <h1>Voeg een nieuwe medewerker toe</h1>
                    <div class="flex-column">
                        <label for="file-selector">Medewerkers foto</label>
                        <label class="label-image">
                            <input type="file" id="file-selector" name="foto" accept="image/png, image/jpg, image/jpeg" placeholder="foto">
                            <img id="image" class="img-preview">
                        </label>
                        <label for="name"><p style="color:red">*</p>Naam</label>
                        <input type="text" id="name" name="name" placeholder="Naam" required>
                        <label for="klas"><p style="color:red">*</p>Klas</label>
                        <input type="text" id="klas" name="klas" placeholder="Klas" required>
                        <label for="telefoonnummer">Telefoonnummer</label>
                        <input type="text" id="telefoonnummer" name="telefoonnummer" placeholder="+31123456789">
                        <label for="email"><p style="color:red">*</p>E-mail</label>
                        <input type="email" id="email" name="email" placeholder="E-mail" required>
                        <label for="password"><p style="color:red">*</p>Wachtwoord</label>
                        <input type="password" id="password" name="password" placeholder="Wachtwoord" required>
                        <label for="password"><p style="color:red">*</p>Bevestig wachtwoord</label>
                        <input type="password" id="confirmPassword" name="confirmPassword" placeholder="Wachtwoord" required>
                        <label for="rol"><p style="color:red">*</p>Rol</label>
                        <select name="rol" required>
                            <option value="medewerker">Medewerker</option>
                            <?php $_SESSION['rol'] != "beheerder" ? '<option value="beheerder">Beheerder</option>' : '' ?>
                            
                        </select>

                        <input class="submit" id="submit" type="submit" name="submit" value="Voeg medewerker toe" disabled>
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

        const password = document.getElementById('password');
        const confirmPassword = document.getElementById('confirmPassword');
        const submit = document.getElementById('submit');
        confirmPassword.addEventListener('input', () => {
            if (password.value != confirmPassword.value) {
                confirmPassword.style.border = "3px solid red";
                submit.disabled = true;
            } else {
                confirmPassword.style.border = "3px solid #4CAF50";
                submit.disabled = false;
            }
        });
    </script>
</body>

</html>