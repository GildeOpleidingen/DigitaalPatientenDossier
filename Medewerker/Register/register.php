<?php
session_start();

if (!isset($_SESSION['loggedin_id']) || $_SESSION['rol'] != "beheerder") {
    header("Location: ../index.php");
}

include '../../Database/DatabaseConnection.php';
$error = "";

if (isset($_POST['submit'])) {
    $hashedPassword = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $email = $_POST['email'];
    $name = $_POST['name'];

    // Check of de medewerker data al bestaat
    $result = DatabaseConnection::getConn()->query("SELECT `id`, `email`, `naam` FROM `medewerker`");
    $medewerkers = $result->fetch_all(MYSQLI_ASSOC);

    foreach ($medewerkers as $medewerker) {
        if ($medewerker['email'] == $email || $medewerker['naam'] == $name) {
            $error = "Medewerker bestaat al!";
        }
    }

    if (!isset($error)) {
        echo "Medewerker toegevoegd";
        $result = DatabaseConnection::getConn()->prepare("INSERT INTO medewerker (email, naam, wachtwoord) VALUES (?, ?, ?)");
        $result->bind_param("sss", $email, $name, $hashedPassword);
        $result->execute();
    }
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="register.css">
    <title>Registreerpagina</title>
</head>

<body>
    <?php
    include '../../Includes/header.php';
    ?>
    <div class="main">
        <div class="content">
            <div class="form-content">
                <form method="post">
                    <h1 class="error"><?= $error ?></h1>
                    <h1>Registreer</h1>
                    <div>
                        <input type="text" name="name" placeholder="Naam" required>
                        <input type="email" name="email" placeholder="E-mail" required>
                        <input type="password" name="password" placeholder="Wachtwoord" required>
                        <input type="submit" name="submit" value="Registreer">
                    </div>
                </form>
            </div>
        </div>
    </div>
    </form>
</body>

</html>