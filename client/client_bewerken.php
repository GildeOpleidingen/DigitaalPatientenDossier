<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require_once __DIR__ . '/../models/autoload.php';
Auth::requireAdmin();

// Zorg ervoor dat je het ID van de client hebt om te bewerken
if (!isset($_GET['id'])) {
    header("Location: client.php");
    exit;
}

$id = (int)$_GET['id'];
$clientModel = new ClientModel();
$targetClient = $clientModel->getById($id);

// Controleer of de client is gevonden
if (!$targetClient) {
    die("Client niet gevonden.");
}

$errors = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = [
        'naam'             => $_POST['naam'] ?? '',
        'geslacht'         => $_POST['geslacht'] ?? 'M',
        'adres'            => $_POST['adres'] ?? '',
        'postcode'         => $_POST['postcode'] ?? '',
        'woonplaats'       => $_POST['woonplaats'] ?? '',
        'telefoonnummer'   => $_POST['telefoonnummer'] ?? '',
        'email'            => $_POST['email'] ?? '',
        'geboortedatum'    => $_POST['geboortedatum'] ?? '',
        'reanimatiestatus' => isset($_POST['reanimatiestatus']) ? (int)$_POST['reanimatiestatus'] : 1,
        'nationaliteit'    => $_POST['nationaliteit'] ?? 'Nederlandse',
        'afdeling_id'      => !empty($_POST['afdeling']) ? (int)$_POST['afdeling'] : null,
        'burgelijkestaat'  => $_POST['burgelijkestaat'] ?? 'onghuwd',
    ];

    if (isset($_FILES['foto']) && $_FILES['foto']['error'] === UPLOAD_ERR_OK) {
        $data['foto'] = file_get_contents($_FILES['foto']['tmp_name']);
    }

    if ($clientModel->updateById($id, $data, $errors)) {
        header('Location: client.php');
        exit;
    } else {
        $targetClient = array_merge($targetClient, $data);
    }
}
?>

<!DOCTYPE html>
<html lang="nl">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cliënt Bewerken</title>
    <link rel="stylesheet" href="../assets/vendor/bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="../assets/css/client/client.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css"
        integrity="sha512-Kc323vGBEqzTmouAECnVceyQqyqdsSiqLQISBL29aUW4U/M7pSPA/gEUZQqv1cwx4OnYxTxve5UMg5GT6L4JJg=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="icon" type="image/x-icon" href="../assets/images/favicon.ico">
    <style>
        .form-select {
            height: 38px !important;
            min-height: 38px !important;
            line-height: 1.5 !important;
            padding: 0.375rem 2.25rem 0.375rem 0.75rem !important;
            vertical-align: middle !important;
            font-family: "Inter Tight", sans-serif !important;
        }
    </style>
</head>

<body>
    <?php include __DIR__ . '/../includes/n-header.php'; ?>

    <div class="main">
        <div class="content">
            <div class="mt-4 mb-4 bg-white p-4 rounded shadow-sm" style="max-width: 950px; margin: 0 auto;">
                <div class="d-flex justify-content-between align-items-center mb-3 pb-2 border-bottom">
                    <div>
                        <h2 class="h4 text-primary mb-1">Cliënt Bewerken</h2>
                        <p class="text-muted small mb-0">Pas de onderstaande gegevens van de cliënt aan.</p>
                    </div>
                    <a href="client.php" class="btn btn-outline-secondary btn-sm">Terug naar overzicht</a>
                </div>

                <?php if (!empty($errors)): ?>
                    <div class="alert alert-danger" role="alert">
                        <ul class="mb-0">
                            <?php foreach ($errors as $err): ?>
                                <li><?= htmlspecialchars($err) ?></li>
                            <?php endforeach; ?>
                        </ul>
                    </div>
                <?php endif; ?>

                <form action="client_bewerken.php?id=<?= $id ?>" method="POST" enctype="multipart/form-data">
                    <!-- 1. Persoonlijke gegevens -->
                    <h5 class="text-primary border-bottom pb-2 mt-4 mb-3">1. Persoonlijke gegevens</h5>
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label for="naam" class="form-label fw-bold small">Volledige Naam <span class="text-danger">*</span></label>
                            <input type="text" id="naam" name="naam" class="form-control" value="<?= htmlspecialchars($targetClient['naam'] ?? '') ?>" required>
                        </div>
                        <div class="col-md-3">
                            <label for="geboortedatum" class="form-label fw-bold small">Geboortedatum <span class="text-danger">*</span></label>
                            <input type="date" id="geboortedatum" name="geboortedatum" class="form-control" value="<?= htmlspecialchars($targetClient['geboortedatum'] ?? '') ?>" required>
                        </div>
                        <div class="col-md-3">
                            <label for="geslacht" class="form-label fw-bold small">Geslacht <span class="text-danger">*</span></label>
                            <select id="geslacht" name="geslacht" class="form-select" required>
                                <option value="M" <?= (($targetClient['geslacht'] ?? 'M') === 'M') ? 'selected' : '' ?>>Man</option>
                                <option value="V" <?= (($targetClient['geslacht'] ?? '') === 'V') ? 'selected' : '' ?>>Vrouw</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label for="nationaliteit" class="form-label fw-bold small">Nationaliteit <span class="text-danger">*</span></label>
                            <input type="text" id="nationaliteit" name="nationaliteit" class="form-control" value="<?= htmlspecialchars($targetClient['nationaliteit'] ?? 'Nederlandse') ?>" required>
                        </div>
                        <div class="col-md-6">
                            <label for="burgelijkestaat" class="form-label fw-bold small">Burgerlijke Staat <span class="text-danger">*</span></label>
                            <select id="burgelijkestaat" name="burgelijkestaat" class="form-select" required>
                                <option value="onghuwd" <?= (($targetClient['burgelijkestaat'] ?? 'onghuwd') === 'onghuwd' || ($targetClient['burgelijkestaat'] ?? '') === 'ongehuwd') ? 'selected' : '' ?>>Ongehuwd</option>
                                <option value="gehuwd" <?= (($targetClient['burgelijkestaat'] ?? '') === 'gehuwd') ? 'selected' : '' ?>>Gehuwd</option>
                            </select>
                        </div>
                    </div>

                    <!-- 2. Contact- en adresgegevens -->
                    <h5 class="text-primary border-bottom pb-2 mt-4 mb-3">2. Contact- en adresgegevens</h5>
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label for="adres" class="form-label fw-bold small">Straat en Huisnummer <span class="text-danger">*</span></label>
                            <input type="text" id="adres" name="adres" class="form-control" value="<?= htmlspecialchars($targetClient['adres'] ?? '') ?>" required>
                        </div>
                        <div class="col-md-3">
                            <label for="postcode" class="form-label fw-bold small">Postcode <span class="text-danger">*</span></label>
                            <input type="text" id="postcode" name="postcode" class="form-control" value="<?= htmlspecialchars($targetClient['postcode'] ?? '') ?>" required>
                        </div>
                        <div class="col-md-3">
                            <label for="woonplaats" class="form-label fw-bold small">Woonplaats <span class="text-danger">*</span></label>
                            <input type="text" id="woonplaats" name="woonplaats" class="form-control" value="<?= htmlspecialchars($targetClient['woonplaats'] ?? '') ?>" required>
                        </div>
                        <div class="col-md-6">
                            <label for="telefoonnummer" class="form-label fw-bold small">Telefoonnummer (exact 9 cijfers) <span class="text-danger">*</span></label>
                            <input type="tel" id="telefoonnummer" name="telefoonnummer" class="form-control" maxlength="9" pattern="\d{9}" placeholder="Bijv. 123456789" value="<?= htmlspecialchars($targetClient['telefoonnummer'] ?? '') ?>" required>
                            <div class="form-text">Voer exact 9 cijfers in zodat dit geen echt telefoonnummer kan zijn.</div>
                        </div>
                        <div class="col-md-6">
                            <label for="email" class="form-label fw-bold small">E-mailadres</label>
                            <input type="email" id="email" name="email" class="form-control" value="<?= htmlspecialchars($targetClient['email'] ?? '') ?>">
                        </div>
                    </div>

                    <!-- 3. Zorg- en afdelingsgegevens -->
                    <h5 class="text-primary border-bottom pb-2 mt-4 mb-3">3. Zorg- en afdelingsgegevens</h5>
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label for="afdeling" class="form-label fw-bold small">Afdeling <span class="text-danger">*</span></label>
                            <select id="afdeling" name="afdeling" class="form-select" required>
                                <?php
                                $conn = DatabaseConnection::getConn();
                                $sqlAfdelingen = "SELECT id, naam FROM afdelingen ORDER BY naam ASC";
                                $resultAfdelingen = $conn->query($sqlAfdelingen);

                                if ($resultAfdelingen && $resultAfdelingen->num_rows > 0) {
                                    while ($row = $resultAfdelingen->fetch_assoc()) {
                                        $selected = ((int)$row['id'] === (int)($targetClient['afdeling_id'] ?? 0)) ? 'selected' : '';
                                        echo "<option value='" . htmlspecialchars($row['id']) . "' $selected>" . htmlspecialchars($row['naam']) . "</option>";
                                    }
                                } else {
                                    echo "<option value=''>Geen afdelingen gevonden</option>";
                                }
                                ?>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label for="reanimatiestatus" class="form-label fw-bold small">Reanimatiestatus <span class="text-danger">*</span></label>
                            <select id="reanimatiestatus" name="reanimatiestatus" class="form-select" required>
                                <option value="1" <?= ((int)($targetClient['reanimatiestatus'] ?? 1) === 1) ? 'selected' : '' ?>>Actief (Wel reanimeren)</option>
                                <option value="0" <?= ((int)($targetClient['reanimatiestatus'] ?? 1) === 0) ? 'selected' : '' ?>>Niet Actief (Niet reanimeren)</option>
                            </select>
                        </div>
                    </div>

                    <!-- 4. Pasfoto (optioneel) -->
                    <h5 class="text-primary border-bottom pb-2 mt-4 mb-3">4. Pasfoto</h5>
                    <div class="row g-3">
                        <div class="col-md-8">
                            <label for="fotoInput" class="form-label fw-bold small">Nieuwe pasfoto uploaden (optioneel)</label>
                            <input type="file" name="foto" class="form-control" accept="image/*" id="fotoInput">
                            <div class="form-text">Toegestane formaten: PNG, JPG, JPEG of WEBP. Laat leeg om de huidige foto te behouden.</div>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label fw-bold small d-block">Foto weergave:</label>
                            <?php if (!empty($targetClient['foto'])): ?>
                                <img id="currentPhoto" src="data:image/jpeg;base64,<?= base64_encode($targetClient['foto']) ?>" alt="Huidige foto" style="max-width: 120px; max-height: 120px; border: 1px solid #dee2e6; border-radius: 4px; padding: 4px; object-fit: cover;">
                            <?php else: ?>
                                <span id="currentPhotoNone" class="text-muted small d-block mb-2">Geen huidige foto aanwezig</span>
                            <?php endif; ?>
                            <img id="preview" src="" alt="Nieuwe preview" style="max-width: 120px; max-height: 120px; display: none; border: 2px solid #00365E; border-radius: 4px; padding: 4px; object-fit: cover;">
                        </div>
                    </div>

                    <!-- Knoppen -->
                    <div class="mt-4 pt-3 border-top">
                        <button type="submit" class="btn btn-primary">Wijzigingen opslaan</button>
                        <a href="client.php" class="btn btn-secondary ms-2">Annuleren</a>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        const fotoInput = document.getElementById('fotoInput');
        const preview = document.getElementById('preview');
        const currentPhoto = document.getElementById('currentPhoto');
        const currentPhotoNone = document.getElementById('currentPhotoNone');

        fotoInput.addEventListener('change', function() {
            const file = fotoInput.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    preview.src = e.target.result;
                    preview.style.display = 'inline-block';
                    if (currentPhoto) currentPhoto.style.display = 'none';
                    if (currentPhotoNone) currentPhotoNone.style.display = 'none';
                };
                reader.readAsDataURL(file);
            } else {
                preview.style.display = 'none';
                if (currentPhoto) currentPhoto.style.display = 'inline-block';
                if (currentPhotoNone) currentPhotoNone.style.display = 'block';
            }
        });
    </script>
</body>

</html>
