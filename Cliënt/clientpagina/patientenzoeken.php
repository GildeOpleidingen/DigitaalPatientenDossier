<?php 
$search = $_POST['search'];


include '../../Database/DatabaseConnection.php';

$result = DatabaseConnection::getConn()->query("SELECT id, naam, woonplaats, geboortedatum FROM client WHERE naam like '%$search%';");

if ($result ->num_rows > 0){
    while($row = mysqli_fetch_array($result) ){
        //echo $row["id"]."  ".$row["naam"]."  ".$row["woonplaats"]."  ".$row["geboortedatum"]."<br>";
        ?>
       <!DOCTYPE html>
       <html lang="en">
       <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Cliënten</title>
       </head>
       <body>
        <form>
    <center>
        <table>
            <tr>
                <th>id</th>
                <th>naam</th>
                <th>woonplaats</th>
                <th>geboortedatum</th>
            </tr>
            <tr>
                <td class="row1"><?php echo $row[0];?></td>
                <td class="row1"><?php echo $row[1];?></td>
                <td class="row1"><?php echo $row[2];?></td>
                <td class="row1"><?php echo $row[3];?></td>
            </tr>
            
        </table>
    </center>
        </form>
        <a href="clientoverzicht.php">
            <button>terug naar overzicht</button>
        </a>
</body>
       </html>
       <?php
    }
    }else{
        echo "Cliënt niet gevonden! controleer of de naam goed ingevuld is!";
        echo "<br>";
        echo "<a href='clientoverzicht.php'><button>terug naar overzicht</button></a>";
    }

?>
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