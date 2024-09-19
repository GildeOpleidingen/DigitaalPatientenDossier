<?php
session_start();
require_once('../includes/auth.php');

include '../database/DatabaseConnection.php';

$items = DatabaseConnection::getConn()->query("SELECT id, naam, klas, email, telefoonnummer, foto FROM medewerker;")->fetch_all(MYSQLI_ASSOC);
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Medewerker</title>
    <link rel="stylesheet" href="medewerker.css">
    <link rel="stylesheet" href="../assets/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/assets/css/font-awesome.min.css">
    <link rel="icon" type="image/x-icon" href="../assets/images/favicon.ico">
</head>

<body>
    <?php
    include '../includes/n-header.php';
    ?>

    <div class="main">
        <div class="content">
            <div class="mt-4 mb-3 p-3">
                <p class="card-text">
                <form action="medewerkeropzoeken.php" method="post">
                    <input type="text" placeholder="Search..." name="search">
                    <button type="submit"><i class="fa fa-search"></i></button>
                    <a href="register/register.php" class="link-right">Voeg medewerker toe</a>
                </form>
                <table class="table">
                    <tr>
                        <th>Naam</th>
                        <th>Klas</th>
                        <th>E-mail</th>
                        <th>Telefoonnummer</th>
                    </tr>
                    <?php
                    foreach ($items as $row) {
                        echo "<tr>";
                        echo "<td class='row1'><a href=overzicht/overzicht.php?id=" . $row['id'] . ">" . $row['naam'] . "</a></td>";
                        echo "<td class='row1'>" . $row['klas'] . "</td>";
                        echo "<td class='row1'>" . $row['email'] . "</td>";
                        echo "<td class='row1'>" . $row['telefoonnummer'] . "</td>";
                        echo "</tr>";
                    }
                    ?>
                </table>
            </div>
        </div>
    </div>
</body>

</html>