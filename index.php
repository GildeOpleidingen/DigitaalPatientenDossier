<?php
session_start();
if (isset($_SESSION['loggedin_id'])) {
    header("Location: Dashboard/dashboard.php?id={$_SESSION['loggedin_id']}");
}
include 'Database/DatabaseConnection.php';
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (!empty($_POST['e-mail']) && !empty($_POST['password'])) {
        $email = $_POST['e-mail'];
        $password = $_POST['password'];

        $result = DatabaseConnection::getConn()->prepare("SELECT id, naam, wachtwoord FROM medewerker WHERE email = ?");
        $result->bind_param("s", $email);
        $result->execute();
        $result = $result->get_result();

        if ($result->num_rows > 0) {
            $row = mysqli_fetch_array($result);
            if ($row['wachtwoord'] == $password) {
                $_SESSION['loggedin_id'] = $row['id'];
                $_SESSION['loggedin_naam'] = $row['naam'];
                header("Location: Dashboard/dashboard.php?id={$row['id']}");
            } else {
                $error = "Het wachtwoord is onjuist.";
            }
        } else {
            $error = "Er bestaat geen account met dit e-mailadres.";
        }
    } else {
        $error = "Vul alle velden in.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<link rel="stylesheet" href="Css/login.css">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
</head>

<body style="background: #00365E">
    <center>
        <div class="loginbox">
            <img src="Images/gildezorgcollege.png" alt="gildezorgcollege">
            <?php if (isset($error)) { ?>
                <p style="color: red;"><?php echo $error; ?></p>
            <?php } ?>
            <form method="post">
                <h1>E-mail</h1>
                <input type="text" name="e-mail">
                <h1>Password</h1>
                <input type="password" name="password">
                <br>
                <button>Login</button>
            </form>
        </div>
    </center>
</body>
</html>