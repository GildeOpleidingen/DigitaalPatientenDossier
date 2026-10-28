<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require_once __DIR__ . '/../includes/auth.php';
require_once __DIR__ . '/../database/DatabaseConnection.php';
require_once __DIR__ . '/../models/autoload.php';

// Alleen beheerders mogen medewerkers bewerken
Auth::requireAdmin();

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
if ($id <= 0) {
    $_SESSION['error'] = "Geen geldige medewerker opgegeven.";
    header("Location: /medewerker/medewerker.php");
    exit;
}

$medewerkerModel = new MedewerkerModel();
$targetMedewerker = MedewerkerModel::findById($id);

if (!$targetMedewerker || (int)($targetMedewerker['is_deleted'] ?? 0) === 1) {
    $_SESSION['error'] = "Medewerker niet gevonden of is reeds verwijderd.";
    header("Location: /medewerker/medewerker.php");
    exit;
}

$errors = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $naam = trim($_POST['naam'] ?? '');
    $klas = trim($_POST['klas'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $telefoonnummer = trim($_POST['telefoonnummer'] ?? '');
    $password = $_POST['password'] ?? '';
    $confirmPassword = $_POST['confirmPassword'] ?? '';
    $rol = $_POST['rol'] ?? $targetMedewerker['rol'];

    // Beveiliging: alleen beheerder mag de beheerder-rol instellen
    if ($rol === 'beheerder' && !Auth::isAdmin()) {
        $rol = 'medewerker';
    }

    if (!empty($password) && $password !== $confirmPassword) {
        $errors['password'] = "De ingevulde wachtwoorden komen niet overeen.";
    }

    $foto = null;
    if (isset($_FILES['foto']) && $_FILES['foto']['error'] === UPLOAD_ERR_OK) {
        if ($_FILES['foto']['size'] > 16000000) {
            $errors['foto'] = "Het fotobestand is te groot (maximaal 16MB).";
        } else {
            $fileTmp = $_FILES['foto']['tmp_name'];
            $imageInfo = @getimagesize($fileTmp);
            if ($imageInfo === false) {
                $errors['foto'] = "Het geüploade bestand is geen geldige afbeelding.";
            } else {
                $foto = file_get_contents($fileTmp);
            }
        }
    }

    if (empty($errors)) {
        $updateData = [
            'naam'           => $naam,
            'klas'           => $klas,
            'email'          => $email,
            'telefoonnummer' => $telefoonnummer,
            'rol'            => $rol,
        ];

        if (!empty($password)) {
            $updateData['wachtwoord'] = $password;
        }

        if ($foto !== null) {
            $updateData['foto'] = $foto;
        }

        if ($medewerkerModel->updateById($id, $updateData, $errors)) {
            $_SESSION['success'] = "Medewerker '" . htmlspecialchars($naam) . "' is succesvol bijgewerkt.";
            header("Location: /medewerker/medewerker.php");
            exit;
        } else {
            // Update lokale array voor het formulier
            $targetMedewerker = array_merge($targetMedewerker, $updateData);
        }
    } else {
        $targetMedewerker['naam'] = $naam;
        $targetMedewerker['klas'] = $klas;
        $targetMedewerker['email'] = $email;
        $targetMedewerker['telefoonnummer'] = $telefoonnummer;
        $targetMedewerker['rol'] = $rol;
    }
}
?>
<!DOCTYPE html>
<html lang="nl">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Medewerker Bewerken</title>
    <link rel="stylesheet" href="../assets/vendor/bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="../assets/css/medewerker/medewerker.css">
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
                        <h2 class="h4 text-primary mb-1">Medewerker Bewerken</h2>
                        <p class="text-muted small mb-0">Pas de gegevens van de medewerker aan.</p>
                    </div>
                    <a href="/medewerker/medewerker.php" class="btn btn-outline-secondary btn-sm">Terug naar overzicht</a>
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

                <form action="medewerker_bewerken.php?id=<?= $id ?>" method="POST" enctype="multipart/form-data">
                    <!-- 1. Persoonlijke- en contactgegevens -->
                    <h5 class="text-primary border-bottom pb-2 mt-4 mb-3">1. Persoonlijke- en contactgegevens</h5>
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label for="naam" class="form-label fw-bold small">Volledige Naam <span class="text-danger">*</span></label>
                            <input type="text" id="naam" name="naam" class="form-control" placeholder="Bijv. Jan Jansen" value="<?= htmlspecialchars($targetMedewerker['naam'] ?? '') ?>" required>
                        </div>
                        <div class="col-md-6">
                            <label for="klas" class="form-label fw-bold small">Klas <span class="text-danger">*</span></label>
                            <input type="text" id="klas" name="klas" class="form-control" placeholder="Bijv. ZP41AVP00H5YH" value="<?= htmlspecialchars($targetMedewerker['klas'] ?? '') ?>" required>
                        </div>
                        <div class="col-md-6">
                            <label for="email" class="form-label fw-bold small">E-mailadres <span class="text-danger">*</span></label>
                            <input type="email" id="email" name="email" class="form-control" placeholder="naam@voorbeeld.nl" value="<?= htmlspecialchars($targetMedewerker['email'] ?? '') ?>" required>
                        </div>
                        <div class="col-md-6">
                            <label for="telefoonnummer" class="form-label fw-bold small">Telefoonnummer (exact 9 cijfers) <span class="text-danger">*</span></label>
                            <input type="tel" id="telefoonnummer" name="telefoonnummer" class="form-control" maxlength="9" pattern="\d{9}" placeholder="Bijv. 123456789" value="<?= htmlspecialchars($targetMedewerker['telefoonnummer'] ?? '') ?>" required>
                            <div class="form-text">Voer exact 9 cijfers in zodat dit geen echt telefoonnummer kan zijn.</div>
                        </div>
                    </div>

                    <!-- 2. Account- en inloggegevens -->
                    <h5 class="text-primary border-bottom pb-2 mt-4 mb-3">2. Account- en inloggegevens</h5>
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label for="rol" class="form-label fw-bold small">Rol / Functie <span class="text-danger">*</span></label>
                            <select id="rol" name="rol" class="form-select" required>
                                <option value="medewerker" <?= (($targetMedewerker['rol'] ?? '') === 'medewerker') ? 'selected' : '' ?>>Medewerker</option>
                                <option value="beheerder" <?= (($targetMedewerker['rol'] ?? '') === 'beheerder') ? 'selected' : '' ?>>Beheerder</option>
                            </select>
                        </div>
                        <div class="col-md-6 d-none d-md-block"></div>
                        <div class="col-md-6">
                            <label for="password" class="form-label fw-bold small">Nieuw Wachtwoord (optioneel)</label>
                            <input type="password" id="password" name="password" class="form-control" placeholder="Laat leeg om huidig wachtwoord te behouden">
                            <div class="form-text">Laat dit veld leeg als je het huidige wachtwoord niet wilt wijzigen.</div>
                        </div>
                        <div class="col-md-6">
                            <label for="confirmPassword" class="form-label fw-bold small">Bevestig Nieuw Wachtwoord</label>
                            <input type="password" id="confirmPassword" name="confirmPassword" class="form-control" placeholder="Herhaal nieuw wachtwoord">
                            <div id="pwMismatch" class="form-text text-danger d-none">Wachtwoorden komen niet overeen.</div>
                        </div>
                    </div>

                    <!-- 3. Pasfoto (optioneel) -->
                    <h5 class="text-primary border-bottom pb-2 mt-4 mb-3">3. Pasfoto</h5>
                    <div class="row g-3">
                        <div class="col-md-8">
                            <label for="fotoInput" class="form-label fw-bold small">Nieuwe pasfoto uploaden (optioneel)</label>
                            <input type="file" name="foto" class="form-control" accept="image/png, image/jpeg, image/jpg, image/webp" id="fotoInput">
                            <div class="form-text">Toegestane formaten: PNG, JPG, JPEG of WEBP (maximaal 16MB). Laat leeg om de huidige foto te behouden.</div>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label fw-bold small d-block">Foto weergave:</label>
                            <?php if (!empty($targetMedewerker['foto'])): ?>
                                <img id="currentPhoto" src="data:image/jpeg;base64,<?= base64_encode($targetMedewerker['foto']) ?>" alt="Huidige foto" style="max-width: 120px; max-height: 120px; border: 1px solid #dee2e6; border-radius: 4px; padding: 4px; object-fit: cover;">
                            <?php else: ?>
                                <span id="currentPhotoNone" class="text-muted small d-block mb-2">Geen huidige foto aanwezig</span>
                            <?php endif; ?>
                            <img id="preview" src="" alt="Nieuwe preview" style="max-width: 120px; max-height: 120px; display: none; border: 2px solid #00365E; border-radius: 4px; padding: 4px; object-fit: cover;">
                        </div>
                    </div>

                    <!-- Knoppen -->
                    <div class="mt-4 pt-3 border-top">
                        <button type="submit" name="submit" class="btn btn-primary" id="submitBtn">Wijzigingen opslaan</button>
                        <a href="/medewerker/medewerker.php" class="btn btn-secondary ms-2">Annuleren</a>
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

        const password = document.getElementById('password');
        const confirmPassword = document.getElementById('confirmPassword');
        const pwMismatch = document.getElementById('pwMismatch');

        function checkPasswords() {
            if (password.value.length > 0 || confirmPassword.value.length > 0) {
                if (password.value !== confirmPassword.value) {
                    confirmPassword.classList.add('is-invalid');
                    confirmPassword.classList.remove('is-valid');
                    pwMismatch.classList.remove('d-none');
                } else {
                    confirmPassword.classList.remove('is-invalid');
                    confirmPassword.classList.add('is-valid');
                    pwMismatch.classList.add('d-none');
                }
            } else {
                confirmPassword.classList.remove('is-invalid', 'is-valid');
                pwMismatch.classList.add('d-none');
            }
        }

        password.addEventListener('input', checkPasswords);
        confirmPassword.addEventListener('input', checkPasswords);
    </script>
</body>

</html>

