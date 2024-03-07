<?php
session_start();

if (!isset($_SESSION['loggedin_id'])) {
    header("Location: index.php");
}

include '../../Database/DatabaseConnection.php';

if (isset($_POST['submit'])) {
    $hashedPassword = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $email = $_POST['email'];
    $name = $_POST['name'];

    $result = DatabaseConnection::getConn()->prepare("INSERT INTO medewerker (email, naam, wachtwoord) VALUES (?, ?, ?)");
    $result->bind_param("sss", $email, $name, $hashedPassword);
    $result->execute();
    echo "ja";
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
                    <h1>Registreer</h1>
                    <input type="text" name="name" placeholder="Naam" required>
                    <input type="email" name="email" placeholder="E-mail" required>
                    <input type="password" name="password" placeholder="Wachtwoord" required>
                    <input type="submit" name="submit" value="Registreer">
                </form>
            </div>
        </div>
    </div>
    </form>
</body>

</html>