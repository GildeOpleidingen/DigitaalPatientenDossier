<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require_once __DIR__ . '/../database/DatabaseConnection.php';
require_once __DIR__ . '/../models/autoload.php';
Auth::requireLogin();
$Main = new Main();

$clientModel = new ClientModel();
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

    $caregiverId = isset($_SESSION['loggedin_id']) ? (int)$_SESSION['loggedin_id'] : null;
    $newClientId = $clientModel->create($data, $caregiverId, $errors);

    if ($newClientId) {
        header('Location: client.php');
        exit;
    }
}
?>

<!DOCTYPE html>
<html lang="nl">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nieuwe Cliënt Toevoegen</title>
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
                        <h2 class="h4 text-primary mb-1">Nieuwe Cliënt Toevoegen</h2>
                        <p class="text-muted small mb-0">Vul de onderstaande gegevens in om een nieuwe cliënt in te schrijven.</p>
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

                <form action="client_toevoegen.php" method="POST" enctype="multipart/form-data">
                    <!-- 1. Persoonlijke gegevens -->
                    <h5 class="text-primary border-bottom pb-2 mt-4 mb-3">1. Persoonlijke gegevens</h5>
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label for="naam" class="form-label fw-bold small">Volledige Naam <span class="text-danger">*</span></label>
                            <input type="text" id="naam" name="naam" class="form-control" value="<?= htmlspecialchars($_POST['naam'] ?? '') ?>" required>
                        </div>
                        <div class="col-md-3">
                            <label for="geboortedatum" class="form-label fw-bold small">Geboortedatum <span class="text-danger">*</span></label>
                            <input type="date" id="geboortedatum" name="geboortedatum" class="form-control" value="<?= htmlspecialchars($_POST['geboortedatum'] ?? '') ?>" required>
                        </div>
                        <div class="col-md-3">
                            <label for="geslacht" class="form-label fw-bold small">Geslacht <span class="text-danger">*</span></label>
                            <select id="geslacht" name="geslacht" class="form-select" required>
                                <option value="M" <?= (($_POST['geslacht'] ?? 'M') === 'M') ? 'selected' : '' ?>>Man</option>
                                <option value="V" <?= (($_POST['geslacht'] ?? '') === 'V') ? 'selected' : '' ?>>Vrouw</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label for="nationaliteit" class="form-label fw-bold small">Nationaliteit <span class="text-danger">*</span></label>
                            <input type="text" id="nationaliteit" name="nationaliteit" class="form-control" value="<?= htmlspecialchars($_POST['nationaliteit'] ?? 'Nederlandse') ?>" required>
                        </div>
                        <div class="col-md-6">
                            <label for="burgelijkestaat" class="form-label fw-bold small">Burgerlijke Staat <span class="text-danger">*</span></label>
                            <select id="burgelijkestaat" name="burgelijkestaat" class="form-select" required>
                                <option value="onghuwd" <?= (($_POST['burgelijkestaat'] ?? 'onghuwd') === 'onghuwd') ? 'selected' : '' ?>>Ongehuwd</option>
                                <option value="gehuwd" <?= (($_POST['burgelijkestaat'] ?? '') === 'gehuwd') ? 'selected' : '' ?>>Gehuwd</option>
                            </select>
                        </div>
                    </div>

                    <!-- 2. Contact- en adresgegevens -->
                    <h5 class="text-primary border-bottom pb-2 mt-4 mb-3">2. Contact- en adresgegevens</h5>
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label for="adres" class="form-label fw-bold small">Straat en Huisnummer <span class="text-danger">*</span></label>
                            <input type="text" id="adres" name="adres" class="form-control" value="<?= htmlspecialchars($_POST['adres'] ?? '') ?>" required>
                        </div>
                        <div class="col-md-3">
                            <label for="postcode" class="form-label fw-bold small">Postcode <span class="text-danger">*</span></label>
                            <input type="text" id="postcode" name="postcode" class="form-control" value="<?= htmlspecialchars($_POST['postcode'] ?? '') ?>" required>
                        </div>
                        <div class="col-md-3">
                            <label for="woonplaats" class="form-label fw-bold small">Woonplaats <span class="text-danger">*</span></label>
                            <input type="text" id="woonplaats" name="woonplaats" class="form-control" value="<?= htmlspecialchars($_POST['woonplaats'] ?? '') ?>" required>
                        </div>
                        <div class="col-md-6">
                            <label for="telefoonnummer" class="form-label fw-bold small">Telefoonnummer (exact 9 cijfers) <span class="text-danger">*</span></label>
                            <input type="tel" id="telefoonnummer" name="telefoonnummer" class="form-control" maxlength="9" pattern="\d{9}" placeholder="Bijv. 123456789" value="<?= htmlspecialchars($_POST['telefoonnummer'] ?? '') ?>" required>
                            <div class="form-text">Voer exact 9 cijfers in zodat dit geen echt telefoonnummer kan zijn.</div>
                        </div>
                        <div class="col-md-6">
                            <label for="email" class="form-label fw-bold small">E-mailadres <span class="text-danger">*</span></label>
                            <input type="email" id="email" name="email" class="form-control" value="<?= htmlspecialchars($_POST['email'] ?? '') ?>" required>
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
                                        $selected = (isset($_POST['afdeling']) && (int)$_POST['afdeling'] === (int)$row['id']) ? 'selected' : '';
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
                                <option value="1" <?= (!isset($_POST['reanimatiestatus']) || (int)$_POST['reanimatiestatus'] === 1) ? 'selected' : '' ?>>Actief (Wel reanimeren)</option>
                                <option value="0" <?= (isset($_POST['reanimatiestatus']) && (int)$_POST['reanimatiestatus'] === 0) ? 'selected' : '' ?>>Niet Actief (Niet reanimeren)</option>
                            </select>
                        </div>
                    </div>

                    <!-- 4. Pasfoto (optioneel) -->
                    <h5 class="text-primary border-bottom pb-2 mt-4 mb-3">4. Pasfoto (optioneel)</h5>
                    <div class="row g-3">
                        <div class="col-md-8">
                            <label for="fotoInput" class="form-label fw-bold small">Selecteer bestand</label>
                            <input type="file" name="foto" class="form-control" accept="image/*" id="fotoInput">
                            <div class="form-text">Toegestane formaten: PNG, JPG, JPEG of WEBP.</div>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label fw-bold small d-block">Voorbeeld:</label>
                            <img id="preview" src="" alt="Voorbeeld" style="max-width: 120px; max-height: 120px; display: none; border: 1px solid #dee2e6; border-radius: 4px; padding: 4px;">
                            <span id="noPhoto" class="text-muted small">Geen foto geselecteerd</span>
                        </div>
                    </div>

                    <!-- Knoppen -->
                    <div class="mt-4 pt-3 border-top">
                        <button type="submit" class="btn btn-primary">Cliënt toevoegen</button>
                        <a href="client.php" class="btn btn-secondary ms-2">Annuleren</a>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        const fotoInput = document.getElementById('fotoInput');
        const preview = document.getElementById('preview');
        const noPhoto = document.getElementById('noPhoto');

        fotoInput.addEventListener('change', function() {
            const file = fotoInput.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    preview.src = e.target.result;
                    preview.style.display = 'block';
                    noPhoto.style.display = 'none';
                };
                reader.readAsDataURL(file);
            } else {
                preview.style.display = 'none';
                noPhoto.style.display = 'inline';
            }
        });
    </script>
</body>

</html>
