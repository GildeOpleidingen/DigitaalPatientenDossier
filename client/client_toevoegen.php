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

    // Query om de gegevens in de database in te voegen
    $sql = "INSERT INTO client (naam, geslacht, adres, postcode, woonplaats, telefoonnummer, email, geboortedatum, reanimatiestatus, nationaliteit, afdeling, burgelijkestaat)
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

    $stmt = DatabaseConnection::getConn()->prepare($sql);
    $stmt->bind_param("ssssssssssss", $naam, $geslacht, $adres, $postcode, $woonplaats, $telefoonnummer, $email, $geboortedatum, $reanimatiestatus, $nationaliteit, $afdeling, $burgelijkestaat);

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
        <form action="client_toevoegen.php" method="POST">
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
            <button type="submit" class="btn btn-primary mt-3">Toevoegen</button>
        </form>
    </div>
</body>

</html>
