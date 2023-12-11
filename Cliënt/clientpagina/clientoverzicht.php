<?php 
session_start();

$dbhost = "localhost";
$dbuser = "root";
$dbpass = "";
$dbname = "dpd";


$conn=mysqli_connect($dbhost,$dbuser,$dbpass,"$dbname");
  if(!$conn){
      die('Could not Connect MySql Server:' .mysqli_connect_error());
    }

    $result = mysqli_query($conn, "SELECT id, naam, woonplaats, geboortedatum FROM client;");

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cliënten</title>
</head>
<body>
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
                <td><?php echo $row1[0];?></td>
                <td><?php echo $row1[1];?></td>
                <td><?php echo $row1[2];?></td>
                <td><?php echo $row1[3];?></td>
            </tr>
            <?php endwhile;?>
        </table>
    </center>
</body>
</html>
