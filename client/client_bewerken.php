<?php
session_start();
include '../database/DatabaseConnection.php';

// Haal de clientgegevens op voor bewerken
if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $stmt = DatabaseConnection::getConn()->prepare("SELECT * FROM client WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    $client = $result->fetch_assoc();

    if (!$client) {
        echo "Client niet gevonden.";
        exit;
    }
}

// Verwerk het bewerkingsformulier
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id = $_POST['id'];
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

    // Verwerk de foto-upload
    if (!empty($_FILES['foto']['tmp_name'])) {
        $foto = file_get_contents($_FILES['foto']['tmp_name']);
        $sql = "UPDATE client SET naam = ?, geslacht = ?, adres = ?, postcode = ?, woonplaats = ?, telefoonnummer = ?, email = ?, geboortedatum = ?, reanimatiestatus = ?, nationaliteit = ?, afdeling = ?, burgelijkestaat = ?, foto = ? WHERE id = ?";
        $stmt = DatabaseConnection::getConn()->prepare($sql);
        $stmt->bind_param("sssssssssssssib", $naam, $geslacht, $adres, $postcode, $woonplaats, $telefoonnummer, $email, $geboortedatum, $reanimatiestatus, $nationaliteit, $afdeling, $burgelijkestaat, $foto, $id);
    } else {
        $sql = "UPDATE client SET naam = ?, geslacht = ?, adres = ?, postcode = ?, woonplaats = ?, telefoonnummer = ?, email = ?, geboortedatum = ?, reanimatiestatus = ?, nationaliteit = ?, afdeling = ?, burgelijkestaat = ? WHERE id = ?";
        $stmt = DatabaseConnection::getConn()->prepare($sql);
        $stmt->bind_param("ssssssssssssi", $naam, $geslacht, $adres, $postcode, $woonplaats, $telefoonnummer, $email, $geboortedatum, $reanimatiestatus, $nationaliteit, $afdeling, $burgelijkestaat, $id);
    }

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
    <title>Client Bewerken</title>
    <link rel="stylesheet" href="../assets/css/bootstrap.min.css">
</head>
<body>
    <div class="container">
        <h2>Client Bewerken</h2>
        <form action="client_bewerken.php" method="POST" enctype="multipart/form-data">
            <input type="hidden" name="id" value="<?php echo $client['id']; ?>">

            <div class="form-group">
                <label for="naam">Naam</label>
                <input type="text" name="naam" class="form-control" value="<?php echo $client['naam']; ?>" required>
            </div>

            <div class="form-group">
                <label for="geslacht">Geslacht</label>
                <select name="geslacht" class="form-control" required>
                    <option value="M" <?php if ($client['geslacht'] == 'M') echo 'selected'; ?>>Man</option>
                    <option value="V" <?php if ($client['geslacht'] == 'V') echo 'selected'; ?>>Vrouw</option>
                </select>
            </div>

            <div class="form-group">
                <label for="adres">Adres</label>
                <input type="text" name="adres" class="form-control" value="<?php echo $client['adres']; ?>" required>
            </div>

            <div class="form-group">
                <label for="postcode">Postcode</label>
                <input type="text" name="postcode" class="form-control" value="<?php echo $client['postcode']; ?>" required>
            </div>

            <div class="form-group">
                <label for="woonplaats">Woonplaats</label>
                <input type="text" name="woonplaats" class="form-control" value="<?php echo $client['woonplaats']; ?>" required>
            </div>

            <div class="form-group">
                <label for="telefoonnummer">Telefoonnummer</label>
                <input type="text" name="telefoonnummer" class="form-control" value="<?php echo $client['telefoonnummer']; ?>" required>
            </div>

            <div class="form-group">
                <label for="email">Email</label>
                <input type="email" name="email" class="form-control" value="<?php echo $client['email']; ?>" required>
            </div>

            <div class="form-group">
                <label for="geboortedatum">Geboortedatum</label>
                <input type="date" name="geboortedatum" class="form-control" value="<?php echo $client['geboortedatum']; ?>" required>
            </div>

            <div class="form-group">
                <label for="reanimatiestatus">Reanimatiestatus</label>
                <select name="reanimatiestatus" class="form-control" required>
                    <option value="1" <?php if ($client['reanimatiestatus'] == '1') echo 'selected'; ?>>Ja</option>
                    <option value="0" <?php if ($client['reanimatiestatus'] == '0') echo 'selected'; ?>>Nee</option>
                </select>
            </div>

            <div class="form-group">
                <label for="nationaliteit">Nationaliteit</label>
                <input type="text" name="nationaliteit" class="form-control" value="<?php echo $client['nationaliteit']; ?>" required>
            </div>

            <div class="form-group">
                <label for="afdeling">Afdeling</label>
                <input type="text" name="afdeling" class="form-control" value="<?php echo $client['afdeling']; ?>" required>
            </div>

            <div class="form-group">
                <label for="burgelijkestaat">Burgerlijke Staat</label>
                <select name="burgelijkestaat" class="form-control" required>
                    <option value="gehuwd" <?php if ($client['burgelijkestaat'] == 'gehuwd') echo 'selected'; ?>>Gehuwd</option>
                    <option value="onghuwd" <?php if ($client['burgelijkestaat'] == 'onghuwd') echo 'selected'; ?>>Onghuwd</option>
                    <option value="gescheiden" <?php if ($client['burgelijkestaat'] == 'gescheiden') echo 'selected'; ?>>Gescheiden</option>
                    <option value="weduwe" <?php if ($client['burgelijkestaat'] == 'weduwe') echo 'selected'; ?>>Weduwe</option>
                </select>
            </div>

            

            <button type="submit" class="btn btn-primary mt-3">Opslaan</button>
        </form>
    </div>
</body>
</html>
