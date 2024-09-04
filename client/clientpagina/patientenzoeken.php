<?php
session_start();
$search = "%".$_POST['search']."%";
include '../../database/DatabaseConnection.php';

$items = DatabaseConnection::getConn()->prepare("SELECT id, naam, woonplaats, geboortedatum FROM client WHERE naam like ?");
$items->bind_param("s", $search);
$items->execute();
$items = $items->get_result()->fetch_all();

if (sizeof($items) > 0){
    
        ?>
       <!DOCTYPE html>
       <html lang="en">
       <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Cliënten</title>
        <link rel="stylesheet" href="patientenzoeken.css">
       </head>
       <body>
        <?php
        include '../../includes/header.php';
        
        ?>
        <div class="main">
        
    <div class="content">
        <div class="content2">
        <form>
    <center>
        <table>
            <tr>
                <th>id</th>
                <th>naam</th>
                <th>woonplaats</th>
                <th>geboortedatum</th>
            </tr>
            <?php foreach($items as $row){ ?>
            <tr>
                <td class="row1"><?php echo $row[0];?></td>
                <td class="row1"><a href="overzicht/overzicht.php?id=<?php echo $row[1];?>"><?php echo $row[1];?></a></td>
                <td class="row1"><?php echo $row[2];?></td>
                <td class="row1"><?php echo $row[3];?></td>
            </tr>
            <?php } ?>
        </table>
    </center>
        </form>
        <a href="../client.php">
            <button>terug naar overzicht</button>
        </a>
        </div>
        </div>
        </div> 
</body>
       </html>
       <?php 
    }else{
        echo "Cliënt niet gevonden! controleer of de naam goed ingevuld is!";
        echo "<br>";
        echo "<a href='../client.php'><button>terug naar overzicht</button></a>";
    }

?>
