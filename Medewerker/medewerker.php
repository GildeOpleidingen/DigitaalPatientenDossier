<?php

include '../Database/DatabaseConnection.php';

$result = DatabaseConnection::getConn()->query("SELECT naam, klas, email, telefoonnummer, foto FROM medewerker;")->fetch_all(MYSQLI_ASSOC);
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
        <table>
            <tr>
                <th>Naam</th>
                <th>Klas</th>
                <th>E-mail</th>
                <th>Telefoonnummer</th>
            </tr>
            <?php foreach($result as $row) {?>
            <tr>
                <td><?php echo $row['naam'];?></td>
                <td><?php echo $row['klas'];?></td>
                <td><?php echo $row['email'];?></td>
                <td><?php echo $row['telefoonnummer'];?></td>
            </tr>
            <?php } ?>
        </table>
</body>

</html>