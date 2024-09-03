<?php
session_start();
$search = "%".$_POST['search']."%";
include '../database/DatabaseConnection.php';

$items = DatabaseConnection::getConn()->prepare("SELECT naam, klas, email, telefoonnummer, foto FROM medewerker WHERE naam like ?;");
$items->bind_param("s",$search);
$items->execute();
$items = $items->get_result()->fetch_all();

if (sizeof($items) > 0){ ?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Medewerker</title>
    <link rel="stylesheet" href="medewerkeropzoeken.css">
</head>

<body>
<?php
include '../includes/header.php';

?>
<div class="main">

    <div class="content">
        <div class="content2">
            <form>
                <center>
                    <table>
                        <tr>
                            <th>Naam</th>
                            <th>Klas</th>
                            <th>E-mail</th>
                            <th>Telefoonnummer</th>
                        </tr>
                        <?php foreach($items as $row) {?>
                        <tr>
                            <td class="row1"><?php echo $row[0];?></td>
                            <td class="row1"><?php echo $row[1];?></td>
                            <td class="row1"><?php echo $row[2];?></td>
                            <td class="row1"><?php echo $row[3];?></td>
                        </tr>
                        <?php } ?>
                    </table>
                </center>
            </form>
            <a href="medewerker.php">
                <button>Terug naar overzicht</button>
            </a>
        </div>
    </div>
</div>
</body>

</html>
<?php
    }else{
        echo "Medewerker niet gevonden! Controleer of de naam goed ingevuld is!";
        echo "<br>";
        echo "<a href='medewerker.php'><button>Terug naar overzicht</button></a>";
    }

?>
<style>
th {
    border: 1px solid black;
    border-radius: 10px;
    width: 150px;
}

.row1 {
    border: 1px solid black;
    border-radius: 10px;
}
</style>
