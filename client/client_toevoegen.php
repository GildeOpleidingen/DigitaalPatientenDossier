<?php
session_start();
include '../database/DatabaseConnection.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Verkrijg de gegevens van het formulier
    $naam = $_POST['naam'];
    $geslacht = $_POST['geslacht'];
    $adres = $_POST['adres'];
    $postcode = $_POST['postcode'];
    $woonplaats = $_POST['woonplaats'];
    $telefoonnummer = $_POST['telefoonnummer'];
    $email = $_POST['email'];
    $geboortedatum = $_POST['geboortedatum'];
    $reanimatiestatus = $_POST['reanimatiestatus'];
    $nationaliteit = $_POST['nationaliteit'];
    $afdeling = $_POST['afdeling'];
    $burgelijkestaat = $_POST['burgelijkestaat'];

    // Verwerk de foto, als die aanwezig is
    $foto = NULL;
    if (isset($_FILES['foto']) && $_FILES['foto']['error'] == 0) {
        $foto = file_get_contents($_FILES['foto']['tmp_name']); // Lees de inhoud van het bestand
    }

    // Query om de gegevens in de database in te voegen, inclusief de optionele foto
    $sql = "INSERT INTO client (naam, geslacht, adres, postcode, woonplaats, telefoonnummer, email, geboortedatum, reanimatiestatus, nationaliteit, afdeling, burgelijkestaat, foto)
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

    $stmt = DatabaseConnection::getConn()->prepare($sql);

    // Bind de parameters, de foto wordt als blob gebonden
    $stmt->bind_param("sssssssssssss", $naam, $geslacht, $adres, $postcode, $woonplaats, $telefoonnummer, $email, $geboortedatum, $reanimatiestatus, $nationaliteit, $afdeling, $burgelijkestaat, $foto);

    // Controleer of het toevoegen is gelukt
    if ($stmt->execute()) {
        header('Location: client.php');
        exit;
    } else {
        echo "Er is een fout opgetreden: " . $stmt->error;
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nieuwe Client Toevoegen</title>
    <link rel="stylesheet" href="../assets/css/bootstrap.min.css">
</head>

<body>
    <div class="container">
        <h2>Nieuwe Client Toevoegen</h2>
        <form action="client_toevoegen.php" method="POST" enctype="multipart/form-data">
            <div class="form-group">
                <label for="naam">Naam</label>
                <input type="text" name="naam" class="form-control" required>
            </div>
            <div class="form-group">
                <label for="geslacht">Geslacht</label>
                <select name="geslacht" class="form-control" required>
                    <option value="M">Man</option>
                    <option value="V">Vrouw</option>
                </select>
            </div>
            <div class="form-group">
                <label for="adres">Adres</label>
                <input type="text" name="adres" class="form-control" required>
            </div>
            <div class="form-group">
                <label for="postcode">Postcode</label>
                <input type="text" name="postcode" class="form-control" required>
            </div>
            <div class="form-group">
                <label for="woonplaats">Woonplaats</label>
                <input type="text" name="woonplaats" class="form-control" required>
            </div>
            <div class="form-group">
                <label for="telefoonnummer">Telefoonnummer</label>
                <input type="text" name="telefoonnummer" class="form-control" required>
            </div>
            <div class="form-group">
                <label for="email">Email</label>
                <input type="email" name="email" class="form-control" required>
            </div>
            <div class="form-group">
                <label for="geboortedatum">Geboortedatum</label>
                <input type="date" name="geboortedatum" class="form-control" required>
            </div>
            <div class="form-group">
                <label for="reanimatiestatus">Reanimatiestatus</label>
                <select name="reanimatiestatus" class="form-control" required>
                    <option value="1">Actief</option>
                    <option value="0">Niet Actief</option>
                </select>
            </div>
            <div class="form-group">
                <label for="nationaliteit">Nationaliteit</label>
                <input type="text" name="nationaliteit" class="form-control" required>
            </div>
            <div class="form-group">
                <label for="afdeling">Afdeling</label>
                <input type="text" name="afdeling" class="form-control" required>
            </div>
            <div class="form-group">
                <label for="burgelijkestaat">Burgelijke Staat</label>
                <select name="burgelijkestaat" class="form-control" required>
                    <option value="onghuwd">Onghuwd</option>
                    <option value="gehuwd">Gehuwd</option>
                </select>
            </div>
            <div class="form-group">
                <label for="foto">Foto (optioneel)</label>
                <input type="file" name="foto" class="form-control" accept="image/*" id="fotoInput">
                <div class="mt-2">
                        <label for="currentPhoto">Huidige Foto:</label><br>
                        <img id="preview" src="" alt="Client Foto" style="max-width: 150px; display: none;">
                        <p id="noPhoto" style="display: none;">Geen foto beschikbaar</p>
                    </div>
                </div>
            <button type="submit" class="btn btn-primary mt-3">Toevoegen</button>
        </form>
    </div>
</body>
<script>
    // Get the file input and the preview image element
    const fotoInput = document.getElementById('fotoInput');
    const preview = document.getElementById('preview');
    const noPhoto = document.getElementById('noPhoto');

    // Listen for changes on the file input
    fotoInput.addEventListener('change', function() {
        const file = fotoInput.files[0]; // Get the selected file
        if (file) {
            const reader = new FileReader();
            reader.onload = function(e) {
                preview.src = e.target.result; // Set the src of the image to the file's data URL
                preview.style.display = 'block'; // Show the preview image
                noPhoto.style.display = 'none'; // Hide the "no photo" message
            };
            reader.readAsDataURL(file); // Read the file as a data URL
        } else {
            preview.style.display = 'none'; // Hide the preview image if no file is selected
            noPhoto.style.display = 'block'; // Show the "no photo" message
        }
    });
</script>
</html>
