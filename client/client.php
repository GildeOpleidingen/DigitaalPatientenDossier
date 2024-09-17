<?php
session_start();
include '../database/DatabaseConnection.php';
$items = DatabaseConnection::getConn()->query("SELECT id, naam, woonplaats, geboortedatum FROM client;")->fetch_all();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cliënten</title>
    <link rel="stylesheet" href="client.css">
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
                <form action="clientpagina/patientenzoeken.php" method="post">
                    <input type="text" placeholder="Search.." name="search">
                    <button type="submit"><i class="fa fa-search"></i></button>
                </form>
                <table class="table">
                    <tr>
                        <th>id</th>
                        <th>naam</th>
                        <th>woonplaats</th>
                        <th>geboortedatum</th>
                    </tr>
                    <?php
                    foreach ($items as $row) {
                        echo "<tr>";
                        echo "<td class='row1'>$row[0]</td>";
                        echo "<td class='row1'><a href=overzicht/overzicht.php?id=$row[0]>$row[1]</a></td>";
                        echo "<td class='row1'>$row[2]</td>";
                        echo "<td class='row1'>" . date_create($row[3])->format("d-m-Y") . "</td>";
                        echo "</tr>";
                    }
                    ?>
                </table>
            </div>
        </div>
    </div>
</body>

</html>