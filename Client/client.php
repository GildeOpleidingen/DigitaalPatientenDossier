<?php
session_start();
include '../Database/DatabaseConnection.php';
$items = DatabaseConnection::getConn()->query("SELECT id, naam, woonplaats, geboortedatum FROM client;")->fetch_all();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cliënten</title>
    <link rel="stylesheet" href="client.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
</head>
<body>
<?php
    include '../Includes/header.php';
?>

<div  class="main">
        <div class="content">
        <div class="content2">
            <form action="clientpagina/patientenzoeken.php" method="post">
                <input type="text" placeholder="Search.." name="search">
                <button type="submit"><i class="fa fa-search"></i></button> 
            </form>
                <table>
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
                            echo "<td class='row1'><a href=Overzicht/overzicht.php?id=$row[0]>$row[1]</a></td>";
                            echo "<td class='row1'>$row[2]</td>";
                            echo "<td class='row1'>$row[3]</td>";
                            echo "</tr>";
                        }
                    ?>
                </table>
        </div>
        </div>
</div>
</body>
</html>
