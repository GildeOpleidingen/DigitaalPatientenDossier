<?php 
include '../../Database/DatabaseConnection.php';
$result = DatabaseConnection::getConn()->query("SELECT id, naam, woonplaats, geboortedatum FROM client;");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cliënten</title>
</head>
<body>
    <form action="patientenzoeken.php" method="post">
        Search <input type="text" name ="search">
        <input type ="submit">
    </form>
    <center>
        <table>
            <tr>
                <th>id</th>
                <th>naam</th>
                <th>woonplaats</th>
                <th>geboortedatum</th>
            </tr>
            <?php while($row1 = mysqli_fetch_array($result)):;?>
            <tr>
                <td class="row1"><?php echo $row1[0];?></td>
                <td class="row1"><?php echo $row1[1];?></td>
                <td class="row1"><?php echo $row1[2];?></td>
                <td class="row1"><?php echo $row1[3];?></td>
            </tr>
            <?php endwhile;?>
        </table>
</body>
</html>

<style> 
th {
  border: 1px solid black;
  border-radius: 10px;
  width: 150px;
}

.row1{
  border: 1px solid black;
  border-radius: 10px;
}
</style>
