<?php
session_start();
include '../database/DatabaseConnection.php';

// Zorg ervoor dat je de ID van de client hebt om te bewerken
if (!isset($_GET['id'])) {
    die("Client ID is niet gespecificeerd.");
}

$id = $_GET['id'];

// Verkrijg de huidige gegevens van de client
$sql = "SELECT * FROM client WHERE id = ?";
$stmt = DatabaseConnection::getConn()->prepare($sql);
$stmt->bind_param("i", $id); // Bind de ID als een integer
$stmt->execute();
$result = $stmt->get_result();
$client = $result->fetch_assoc();

// Controleer of de client is gevonden
if (!$client) {
    die("Client niet gevonden.");
}

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

    // Update query met of zonder foto
    if ($foto) {
        // Update query met nieuwe foto
        $sql = "UPDATE client SET naam = ?, geslacht = ?, adres = ?, postcode = ?, woonplaats = ?, telefoonnummer = ?, email = ?, geboortedatum = ?, reanimatiestatus = ?, nationaliteit = ?, afdeling = ?, burgelijkestaat = ?, foto = ? WHERE id = ?";
        $stmt = DatabaseConnection::getConn()->prepare($sql);
        $stmt->bind_param("sssssssssssssi", $naam, $geslacht, $adres, $postcode, $woonplaats, $telefoonnummer, $email, $geboortedatum, $reanimatiestatus, $nationaliteit, $afdeling, $burgelijkestaat, $foto, $id);
        $stmt->send_long_data(12, $foto); // Stuur de foto als BLOB
    } else {
        // Update query zonder nieuwe foto (oud blijft)
        $sql = "UPDATE client SET naam = ?, geslacht = ?, adres = ?, postcode = ?, woonplaats = ?, telefoonnummer = ?, email = ?, geboortedatum = ?, reanimatiestatus = ?, nationaliteit = ?, afdeling = ?, burgelijkestaat = ? WHERE id = ?";
        $stmt = DatabaseConnection::getConn()->prepare($sql);
        $stmt->bind_param("ssssssssssssi", $naam, $geslacht, $adres, $postcode, $woonplaats, $telefoonnummer, $email, $geboortedatum, $reanimatiestatus, $nationaliteit, $afdeling, $burgelijkestaat, $id);
    }

    // Controleer of het bewerken is gelukt
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
    <style>
        #preview {
            max-width: 150px;
            margin-top: 10px;
            display: none; /* Verberg de preview in eerste instantie */
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Client Bewerken</h2>
        <form action="client_bewerken.php?id=<?php echo $id; ?>" method="POST" enctype="multipart/form-data">
            <div class="form-group">
                <label for="naam">Naam</label>
                <input type="text" name="naam" class="form-control" value="<?php echo htmlspecialchars($client['naam']); ?>" required>
            </div>
            <div class="form-group">
                <label for="geslacht">Geslacht</label>
                <select name="geslacht" class="form-control" required>
                    <option value="M" <?php echo $client['geslacht'] == 'M' ? 'selected' : ''; ?>>Man</option>
                    <option value="V" <?php echo $client['geslacht'] == 'V' ? 'selected' : ''; ?>>Vrouw</option>
                </select>
            </div>
            <div class="form-group">
                <label for="adres">Adres</label>
                <input type="text" name="adres" class="form-control" value="<?php echo htmlspecialchars($client['adres']); ?>" required>
            </div>
            <div class="form-group">
                <label for="postcode">Postcode</label>
                <input type="text" name="postcode" class="form-control" value="<?php echo htmlspecialchars($client['postcode']); ?>" required>
            </div>
            <div class="form-group">
                <label for="woonplaats">Woonplaats</label>
                <input type="text" name="woonplaats" class="form-control" value="<?php echo htmlspecialchars($client['woonplaats']); ?>" required>
            </div>
            <div class="form-group">
                <label for="telefoonnummer">Telefoonnummer</label>
                <input type="text" name="telefoonnummer" class="form-control" value="<?php echo htmlspecialchars($client['telefoonnummer']); ?>" required>
            </div>
            <div class="form-group">
                <label for="email">Email</label>
                <input type="email" name="email" class="form-control" value="<?php echo htmlspecialchars($client['email']); ?>" required>
            </div>
            <div class="form-group">
                <label for="geboortedatum">Geboortedatum</label>
                <input type="date" name="geboortedatum" class="form-control" value="<?php echo htmlspecialchars($client['geboortedatum']); ?>" required>
            </div>
            <div class="form-group">
                <label for="reanimatiestatus">Reanimatiestatus</label>
                <select name="reanimatiestatus" class="form-control" required>
                    <option value="1" <?php echo $client['reanimatiestatus'] == 1 ? 'selected' : ''; ?>>Actief</option>
                    <option value="0" <?php echo $client['reanimatiestatus'] == 0 ? 'selected' : ''; ?>>Niet Actief</option>
                </select>
            </div>
            <div class="form-group">
                <label for="nationaliteit">Nationaliteit</label>
                <input type="text" name="nationaliteit" class="form-control" value="<?php echo htmlspecialchars($client['nationaliteit']); ?>" required>
            </div>
          
           <div class="form-group">

    <label for="afdeling">Afdeling</label>
    <select name="afdeling" class="form-control" required>
        <?php
        // Maak een nieuwe query voor alle afdelingen uit de database
        $conn = DatabaseConnection::getConn();
        $sqlAfdelingen = "SELECT naam FROM afdelingen"; 
        $resultAfdelingen = $conn->query($sqlAfdelingen);

        if ($resultAfdelingen && $resultAfdelingen->num_rows > 0) {
            while ($row = $resultAfdelingen->fetch_assoc()) {
                // Kijk of dit de huidige afdeling van de client is
                $selected = ($row['naam'] === $client['afdeling']) ? 'selected' : '';
                echo "<option value='" . htmlspecialchars($row['naam']) . "' $selected>" . htmlspecialchars($row['naam']) . "</option>";
            }
        } else {
            echo "<option value=''>Geen afdelingen gevonden</option>";
        }
        ?>
    </select>
</div>

            <div class="form-group">
                <label for="burgelijkestaat">Burgelijke Staat</label>
                <select name="burgelijkestaat" class="form-control" required>
                    <option value="onghuwd" <?php echo $client['burgelijkestaat'] == 'onghuwd' ? 'selected' : ''; ?>>Onghuwd</option>
                    <option value="gehuwd" <?php echo $client['burgelijkestaat'] == 'gehuwd' ? 'selected' : ''; ?>>Gehuwd</option>
                </select>
            </div>
            <div class="form-group">
                <label for="foto">Foto (optioneel)</label>
                <input type="file" name="foto" class="form-control" accept="image/*" id="fotoInput">
                <div class="mt-2">
                    <label for="currentPhoto">Huidige Foto:</label><br>
                    <?php if ($client['foto']): ?>
                        <img src="data:image/jpeg;base64,<?php echo base64_encode($client['foto']); ?>" alt="Client Foto" style="max-width: 150px;">
                    <?php else: ?>
                        <p id="noPhoto">Geen foto beschikbaar</p>
                    <?php endif; ?>
                    <img id="preview" src="" alt="Nieuwe Client Foto" style="max-width: 150px; display: none;">
                </div>
            </div>
                        <button type="submit" class="btn btn-primary mt-3">Bewerken</button>
            <a href="client.php" class="btn btn-secondary mt-3">Terug</a>
        </form>
    </div>
</body>

<script>
    // Get the file input and the preview image element
    const fotoInput = document.getElementById('fotoInput');
    const preview = document.getElementById('preview');

    // Listen for changes on the file input
    fotoInput.addEventListener('change', function() {
        const file = fotoInput.files[0]; // Get the selected file
        if (file) {
            const reader = new FileReader();
            reader.onload = function(e) {
                preview.src = e.target.result; // Set the src of the image to the file's data URL
                preview.style.display = 'block'; // Show the preview image
            };
        } else {
            preview.style.display = 'none'; // Hide the preview image if no file is selected
        }
    });
</script>
</html>
