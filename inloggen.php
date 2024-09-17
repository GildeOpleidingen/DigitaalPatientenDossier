<?php
session_start();

//Tom: removing this seems to fix the "id in url" problem, but do I break other things by removing this????
    // if (isset($_SESSION['loggedin_id'])) {
    //     header("Location: dashboard.php?id={$_SESSION['loggedin_id']}");
    // }

include 'database/DatabaseConnection.php';

if(isset($_POST['inloggen'])){
    if (!empty($_POST['email']) && !empty($_POST['wachtwoord'])) {
        $email = $_POST['email'];
        $wachtwoord = $_POST['wachtwoord'];

        $result = DatabaseConnection::getConn()->prepare("SELECT id, naam, wachtwoord, rol FROM medewerker WHERE email = ?");
        $result->bind_param("s", $email);
        $result->execute();
        $result = $result->get_result();

        if ($result->num_rows > 0) {
            $row = mysqli_fetch_array($result);
            if ($row['wachtwoord'] == password_verify($wachtwoord, $row['wachtwoord'])) {
                $_SESSION['loggedin_id'] = $row['id'];
                $_SESSION['loggedin_naam'] = $row['naam'];
                $_SESSION['rol'] = $row['rol'];
                header("Location: index.php");
            } else {
                $_SESSION['error'] = "Het wachtwoord of e-mailadres is onjuist.";
            }
        } else {
            $_SESSION['error'] = "Er bestaat geen account met dit e-mailadres.";
        }
    } else {
        $_SESSION['error'] = "Vul alle velden in.";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
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
                                <?php if(isset($_SESSION['error'])) { ?>
                                    <div class="alert alert-danger" role="alert">
                                        <?= $_SESSION['error'] ?>
                                    </div>
                                    <?php session_unset(); ?>
                                <?php } ?>
                                <div class="row gy-2 overflow-hidden">
                                    <div class="col-12">
                                        <div class="form-floating mb-3">
                                            <input type="email" class="form-control" name="email" placeholder="name@example.com" required>
                                            <label for="email" class="form-label">E-mailadres</label>
                                            <div class="invalid-feedback">
                                                Voer een e-mailadres in.
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-12">
                                        <div class="form-floating mb-3">
                                            <input type="password" class="form-control" name="wachtwoord" value="" placeholder="Wachtwoord" required>
                                            <label for="wachtwoord" class="form-label">Wachtwoord</label>
                                            <div class="invalid-feedback">
                                                Voer een wachtwoord in.
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-12">
                                        <div class="d-grid my-3">
                                            <button class="btn btn-primary btn-lg w-100" name="inloggen" type="submit">Inloggen</button>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <script src="assets/js/validatie.js"></script>
    <script src="assets/js/bootstrap.bundle.min.js"></script>
</body>

</html>
