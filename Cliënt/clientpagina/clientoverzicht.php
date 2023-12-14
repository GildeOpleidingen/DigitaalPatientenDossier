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
    <link rel="stylesheet" href="clientoverzicht.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
</head>
<body>
<div  class="main">
    <?php
        include '../../Includes/header.php';
        include '../../Includes/sidebar.php';
    ?>
<div class="content">
    <form action="patientenzoeken.php" method="post">
        <input type="text" placeholder="Search.." name="search">
        <button type="submit"><i class="fa fa-search"></i></button>
    </form>
    <center>
    <div class="backdrop">
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
    </div>
    </center>
</div>

</div>
</body>
</html>
