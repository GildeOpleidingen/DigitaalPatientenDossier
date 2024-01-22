<?php 
$search = $_POST['search'];
$search = "%".$search."%";

include '../Database/DatabaseConnection.php';

$result = DatabaseConnection::getConn()->prepare("SELECT naam, klas, email, telefoonnummer, foto FROM medewerker WHERE naam like ?;");

$result->bind_param("s",$search);
$result->execute();
$result = $result->get_result()->fetch_all(MYSQLI_ASSOC);
if (count($result) > 0){ ?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Medewerker</title>
</head>

<body>
    <form>
        <center>
            <table>
                <tr>
                    <th>Naam</th>
                    <th>Klas</th>
                    <th>E-mail</th>
                    <th>Telefoonnummer</th>
                </tr>
                <?php foreach($result as $row) {?>
                <tr>
                    <td class="row1"><?php echo $row['naam'];?></td>
                    <td class="row1"><?php echo $row['klas'];?></td>
                    <td class="row1"><?php echo $row['email'];?></td>
                    <td class="row1"><?php echo $row['telefoonnummer'];?></td>
                </tr>
                <?php } ?>
            </table>
        </center>
    </form>
    <a href="medewerker.php">
        <button>Terug naar overzicht</button>
    </a>
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
