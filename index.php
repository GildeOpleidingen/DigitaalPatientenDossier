<?php
session_start();
include 'database/DatabaseConnection.php';

// Als gebruiker al is ingelogd, stuur naar dashboard
if (isset($_SESSION['loggedin_id'])) {
    header("Location: dashboard.php");
    exit;
}

// Controleer of formulier is verzonden
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!empty($_POST['email']) && !empty($_POST['wachtwoord'])) {

        $email = $_POST['email'];
        $wachtwoord = $_POST['wachtwoord'];

        // Bereid query voor
        $stmt = DatabaseConnection::getConn()->prepare("SELECT id, naam, wachtwoord, rol FROM medewerker WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();

            // Controleer wachtwoord correct
            if (password_verify($wachtwoord, $row['wachtwoord'])) {

                // Zet sessiegegevens
                $_SESSION['loggedin_id'] = $row['id'];
                $_SESSION['loggedin_naam'] = $row['naam'];
                $_SESSION['isAdmin'] = ($row['rol'] === 'beheerder');
                $_SESSION['rol'] = $row['rol'];

                // 🔧 Tijdelijk: zet vaste cliënt-ID voor test
                // (verwijder dit later als je cliënt-selectiepagina maakt)
                $_SESSION['clientId'] = 1;

                header("Location: dashboard.php");
                exit;
            } else {
                $_SESSION['error'] = "Het wachtwoord of e-mailadres is onjuist.";
            }
        } else {
            $_SESSION['error'] = "Er bestaat geen account met dit e-mailadres.";
        }

        $stmt->close();
    } else {
        $_SESSION['error'] = "Vul alle velden in.";
    }
}
?>
<!DOCTYPE html>
<html lang="nl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="assets/css/login.css">
    <link rel="stylesheet" href="assets/css/bootstrap.min.css">
    <link rel="icon" type="image/x-icon" href="assets/images/favicon.ico">
    <title>Inloggen - DPD</title>
</head>

<body style="background-color: #00365E;">
    <section>
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-12 col-sm-10 col-md-8 col-lg-6 col-xl-5 col-xxl-4">
                    <div class="card border border-light-subtle rounded-3 shadow-sm">
                        <div class="card-body p-3 p-md-4 p-xl-5">
                            <div class="text-center mb-3">
                                <a href="#!" class="mb-3">
                                    <img src="assets/images/logo.png" alt="Logo" class="img-fluid">
                                </a>
                            </div>
                            <form action="" method="POST" class="needs-validation" novalidate>
                                <?php if(isset($_SESSION['error'])): ?>
                                    <div class="alert alert-danger" role="alert">
                                        <?= htmlspecialchars($_SESSION['error']); ?>
                                    </div>
                                    <?php unset($_SESSION['error']); ?>
                                <?php endif; ?>

                                <div class="form-floating mb-3">
                                    <input type="email" class="form-control" name="email" placeholder="name@example.com" required>
                                    <label for="email">E-mailadres</label>
                                    <div class="invalid-feedback">Voer een e-mailadres in.</div>
                                </div>

                                <div class="form-floating mb-3">
                                    <input type="password" class="form-control" name="wachtwoord" placeholder="Wachtwoord" required>
                                    <label for="wachtwoord">Wachtwoord</label>
                                    <div class="invalid-feedback">Voer een wachtwoord in.</div>
                                </div>

                                <div class="d-grid my-3">
                                    <button class="btn btn-primary btn-lg w-100" type="submit">Inloggen</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <script src="assets/js/bootstrap.bundle.min.js"></script>
</body>
</html>
