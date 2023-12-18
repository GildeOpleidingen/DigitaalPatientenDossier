<?php

// TODO: Medewerker hoofdpagina
include '../Database/DatabaseConnection.php';

$result = DatabaseConnection::getConn()->query("SELECT naam, klas, email, telefoonnummer, foto FROM medewerker");
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Medewerker</title>
</head>

<body>
    <form action="medewerkeropzoeken.php" method="post">
        Search <input type="text" name="search">
        <input type="submit">
    </form>
    <center>
        <table>
            <tr>
                <th>Naam</th>
                <th>Klas</th>
                <th>E-mail</th>
                <th>Telefoonnummer</th>
            </tr>
            <?php while($row1 = mysqli_fetch_array($result)):;?>
            <tr>
                <td><?php echo $row1[0];?></td>
                <td><?php echo $row1[1];?></td>
                <td><?php echo $row1[2];?></td>
                <td><?php echo $row1[3];?></td>
                <!-- // <td><?php echo $row1[4];?></td> //Foto -->
            </tr>
            <?php endwhile;?>
        </table>
    </center>
</body>

</html>