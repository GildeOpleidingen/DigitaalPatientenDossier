<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require_once __DIR__ . '/../includes/auth.php';
require_once __DIR__ . '/../database/DatabaseConnection.php';
require_once __DIR__ . '/../models/autoload.php';

Auth::requireLogin();

$medewerkerModel = new MedewerkerModel();
$errors = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $naam = trim($_POST['naam'] ?? '');
    $klas = trim($_POST['klas'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $telefoonnummer = trim($_POST['telefoonnummer'] ?? '');
    $password = $_POST['password'] ?? '';
    $confirmPassword = $_POST['confirmPassword'] ?? '';
    $rol = $_POST['rol'] ?? 'medewerker';

    // Alleen beheerder mag de beheerder-rol toekennen
    if ($rol === 'beheerder' && !Auth::isAdmin()) {
        $rol = 'medewerker';
    }

    if (empty($password)) {
        $errors['password'] = "Wachtwoord is verplicht.";
    } elseif ($password !== $confirmPassword) {
        $errors['password'] = "Wachtwoorden komen niet overeen.";
    }

    // Verwerk foto indien geüpload
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
        $data = [
            'naam'           => $naam,
            'klas'           => $klas,
            'email'          => $email,
            'telefoonnummer' => $telefoonnummer,
            'wachtwoord'     => $password,
            'rol'            => $rol,
            'foto'           => $foto,
        ];

        $newId = $medewerkerModel->create($data, $errors);

        if ($newId) {
            $_SESSION['success'] = "Medewerker '" . htmlspecialchars($naam) . "' is succesvol toegevoegd.";
            header("Location: /medewerker/medewerker.php");
            exit;
        }
    }
}
?>
<!DOCTYPE html>
<html lang="nl">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nieuwe Medewerker Toevoegen</title>
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
                        <h2 class="h4 text-primary mb-1">Nieuwe Medewerker Toevoegen</h2>
                        <p class="text-muted small mb-0">Vul de onderstaande gegevens in om een nieuw medewerkersaccount aan te maken.</p>
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

                <form action="" method="POST" enctype="multipart/form-data">
                    <!-- 1. Persoonlijke- en contactgegevens -->
                    <h5 class="text-primary border-bottom pb-2 mt-4 mb-3">1. Persoonlijke- en contactgegevens</h5>
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label for="naam" class="form-label fw-bold small">Volledige Naam <span class="text-danger">*</span></label>
                            <input type="text" id="naam" name="naam" class="form-control" placeholder="Bijv. Jan Jansen" value="<?= htmlspecialchars($naam ?? '') ?>" required>
                        </div>
                        <div class="col-md-6">
                            <label for="klas" class="form-label fw-bold small">Klas <span class="text-danger">*</span></label>
                            <input type="text" id="klas" name="klas" class="form-control" placeholder="Bijv. ZP41AVP00H5YH" value="<?= htmlspecialchars($klas ?? '') ?>" required>
                        </div>
                        <div class="col-md-6">
                            <label for="email" class="form-label fw-bold small">E-mailadres <span class="text-danger">*</span></label>
                            <input type="email" id="email" name="email" class="form-control" placeholder="naam@voorbeeld.nl" value="<?= htmlspecialchars($email ?? '') ?>" required>
                        </div>
                        <div class="col-md-6">
                            <label for="telefoonnummer" class="form-label fw-bold small">Telefoonnummer (exact 9 cijfers) <span class="text-danger">*</span></label>
                            <input type="tel" id="telefoonnummer" name="telefoonnummer" class="form-control" placeholder="Bijv. 123456789" maxlength="9" pattern="\d{9}" value="<?= htmlspecialchars($telefoonnummer ?? '') ?>" required>
                            <div class="form-text">Voer exact 9 cijfers in zodat dit geen echt telefoonnummer kan zijn.</div>
                        </div>
                    </div>

                    <!-- 2. Account- en inloggegevens -->
                    <h5 class="text-primary border-bottom pb-2 mt-4 mb-3">2. Account- en inloggegevens</h5>
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label for="rol" class="form-label fw-bold small">Rol / Functie <span class="text-danger">*</span></label>
                            <select id="rol" name="rol" class="form-select" required>
                                <option value="medewerker" <?= (($rol ?? '') === 'medewerker') ? 'selected' : '' ?>>Medewerker</option>
                                <?php if (Auth::isAdmin()): ?>
                                    <option value="beheerder" <?= (($rol ?? '') === 'beheerder') ? 'selected' : '' ?>>Beheerder</option>
                                <?php endif; ?>
                            </select>
                        </div>
                        <div class="col-md-6 d-none d-md-block"></div>
                        <div class="col-md-6">
                            <label for="password" class="form-label fw-bold small">Wachtwoord <span class="text-danger">*</span></label>
                            <input type="password" id="password" name="password" class="form-control" placeholder="Wachtwoord" required>
                        </div>
                        <div class="col-md-6">
                            <label for="confirmPassword" class="form-label fw-bold small">Bevestig Wachtwoord <span class="text-danger">*</span></label>
                            <input type="password" id="confirmPassword" name="confirmPassword" class="form-control" placeholder="Herhaal wachtwoord" required>
                            <div id="pwMismatch" class="form-text text-danger d-none">Wachtwoorden komen niet overeen.</div>
                        </div>
                    </div>

                    <!-- 3. Pasfoto (optioneel) -->
                    <h5 class="text-primary border-bottom pb-2 mt-4 mb-3">3. Pasfoto (optioneel)</h5>
                    <div class="row g-3">
                        <div class="col-md-8">
                            <label for="fotoInput" class="form-label fw-bold small">Selecteer bestand</label>
                            <input type="file" name="foto" class="form-control" accept="image/png, image/jpeg, image/jpg, image/webp" id="fotoInput">
                            <div class="form-text">Toegestane formaten: PNG, JPG, JPEG of WEBP (maximaal 16MB).</div>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label fw-bold small d-block">Voorbeeld:</label>
                            <img id="preview" src="" alt="Voorbeeld" style="max-width: 120px; max-height: 120px; display: none; border: 1px solid #dee2e6; border-radius: 4px; padding: 4px; object-fit: cover;">
                            <span id="noPhoto" class="text-muted small">Geen foto geselecteerd</span>
                        </div>
                    </div>

                    <!-- Knoppen -->
                    <div class="mt-4 pt-3 border-top">
                        <button type="submit" name="submit" class="btn btn-primary" id="submitBtn">Medewerker toevoegen</button>
                        <a href="/medewerker/medewerker.php" class="btn btn-secondary ms-2">Annuleren</a>
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

        const password = document.getElementById('password');
        const confirmPassword = document.getElementById('confirmPassword');
        const pwMismatch = document.getElementById('pwMismatch');

        function checkPasswords() {
            if (confirmPassword.value.length > 0 && password.value !== confirmPassword.value) {
                confirmPassword.classList.add('is-invalid');
                confirmPassword.classList.remove('is-valid');
                pwMismatch.classList.remove('d-none');
            } else if (confirmPassword.value.length > 0 && password.value === confirmPassword.value) {
                confirmPassword.classList.remove('is-invalid');
                confirmPassword.classList.add('is-valid');
                pwMismatch.classList.add('d-none');
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
