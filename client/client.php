<?php
session_start();
include '../database/DatabaseConnection.php';

if(!isset($_GET['q'])){
    $items = DatabaseConnection::getConn()->query("SELECT id, naam, woonplaats, geboortedatum FROM client;")->fetch_all();
} else {
    $items = DatabaseConnection::getConn()->query("SELECT id, naam, woonplaats, geboortedatum FROM client WHERE naam LIKE '%$_GET[q]%' OR woonplaats LIKE '%$_GET[q]%' OR geboortedatum LIKE '%$_GET[q]%';")->fetch_all();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cliënten</title>
    <link rel="stylesheet" href="client.css">
    <link rel="stylesheet" href="../assets/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css" integrity="sha512-Kc323vGBEqzTmouAECnVceyQqyqdsSiqLQISBL29aUW4U/M7pSPA/gEUZQqv1cwx4OnYxTxve5UMg5GT6L4JJg==" crossorigin="anonymous" referrerpolicy="no-referrer" />
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
                <?php
                if(isset($_GET['q'])){
                    echo "<a href='client.php' class='text-decoration-none text-white fw-bold'><i class='fa-xs fa-solid fa-arrow-left'></i> Terug naar overzicht</a>";
                }
                ?>
                <form action="" method="GET">
                    <div class="input-group mb-3">
                        <input type="text" name="q" class="form-control" placeholder="Zoeken...">
                        <span class="input-group-text bg-primary border-primary"><i class="fa fa-search text-white" aria-hidden="true"></i></span>
                    </div>
                </form>
                <table class="table table-hover">
                    <tr>
                        <th>#</th>
                        <th>Naam</th>
                        <th>Woonplaats</th>
                        <th>Geboortedatum</th>
                    </tr>
                    <?php
                    if(count($items) == 0){
                        echo "<tr><td colspan='4' class='text-center'>Geen resultaten gevonden.</td></tr>";
                    }
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