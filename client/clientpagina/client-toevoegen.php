<?php
include '../../database/DatabaseConnection.php';
include_once '../../classes/autoload.php';
$Main = new Main();
$result = DatabaseConnection::getConn()->query("SELECT id, naam, woonplaats, geboortedatum FROM client;");
session_start();
?>
<?php
if(isset($_POST['naam'])){
    $naam = $_POST['naam'];
    $geslacht = $_POST['Geslacht'];
    $adres = $_POST['Adres'];
    $postcode = $_POST['Postcode'];
    $woonplaats = $_POST['Woonplaats'];
    $telefoonnummer = $_POST['Telefoonnummer'];
    $email = $_POST['Email'];
    $reanimatiestatus = $_POST['Reanimatiestatus'];
    $nationaliteit = $_POST['Nationaliteit'];
    $afdeling = $_POST['Afdeling'];
    $burgelijkestaat = $_POST['Burgelijkestaat'];
    $foto = null;
    $result = $Main->updateClient($naam, $geslacht, $adres, $postcode, $woonplaats, $telefoonnummer, $email, $reanimatiestatus, $nationaliteit, $afdeling, $burgelijkestaat, $foto);

}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cliënten</title>
    <link rel="stylesheet" href="../../assets/css/client/clientpagina.css">
    <link rel="stylesheet" href="../../assets/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css" integrity="sha512-Kc323vGBEqzTmouAECnVceyQqyqdsSiqLQISBL29aUW4U/M7pSPA/gEUZQqv1cwx4OnYxTxve5UMg5GT6L4JJg==" crossorigin="anonymous" referrerpolicy="no-referrer" />
</head>
<body>
<?php
include '../../includes/header.php';
?>


<div  class="main">

    <div class="content">
        <div class="content2">
            <p class="title">Cliënt toevoegen</p>

            <?php

            if(isset($result)){
                if($result === 1){
                    echo "Client toegevoegd";
                echo "<br>";
                echo "<a href='clientoverzicht.php' class='btn btn-sm btn-secondary mb-2'>Terug naar overzicht</a>";
                }
            }
            ?>


            <form action="client-toevoegen.php" method="post">
                <label for="naam">Naam</label><br>
                <input required type="text" placeholder="Naam" name="naam"><br>
                <label for="geslacht">Geslacht</label><br>
                <input required type="string" maxlength="1" placeholder="Geslacht" name="Geslacht"><br>
                <label for="Adres">Adresgegevens</label><br>
                <input required type="text" placeholder="Adres" name="Adres">
                <input required type="text" placeholder="Postcode" name="Postcode">
                <input required type="text" placeholder="Woonplaats" name="Woonplaats"><br>
                <label for="telefoonnummer">Telefoonnummer</label><br>
                <input required type="text" placeholder="Telefoonnummer" name="Telefoonnummer"><br>
                <label for="email">Email</label><br>
                <input required type="text" placeholder="Email" name="Email"><br>
                <label for="reanimatiestatus">Reanimatiestatus</label><br>
<!--                two checkboxes-->
                <input type="checkbox" name="Reanimatiestatus" value="1"> Wel reanimeren<br>
                <input type="checkbox" name="Reanimatiestatus" value="0"> Niet reanimeren<br>
                <label for="nationaliteit">Nationaliteit</label><br>
                <input type="text" placeholder="Nationaliteit" name="Nationaliteit"><br>
                <label for="afdeling">Afdeling</label><br>
                <input required  type="text" placeholder="Afdeling" name="Afdeling"><br>
                <label for="burgelijkestaat">Burgelijke staat</label><br>
                <input required  type="text" placeholder="Burgelijke staat" name="Burgelijkestaat"><br>

                <button type="submit">Toevoegen</button>
            </form>
        </div>
    </div>
</div>
</body>
</html>
