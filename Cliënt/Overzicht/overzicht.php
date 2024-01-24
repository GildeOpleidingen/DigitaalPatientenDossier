<?php
session_start();
include_once '../../Database/DatabaseConnection.php';

if(!isset($_GET['id'])) {
    header("Location: ../cliënt.php");
}

$id = $_GET['id'];
$_SESSION['clientId'] = $_GET['id'];

$client = DatabaseConnection::getConn()->prepare("SELECT * FROM client WHERE id = ?");
$client->bind_param("i", $id);
$client->execute();
$client = $client->get_result()->fetch_assoc();

$clientRelations = DatabaseConnection::getConn()->prepare("SELECT * FROM verzorgerregel WHERE clientid = ?");
$clientRelations->bind_param("i", $id);
$clientRelations->execute();
$clientRelations = $clientRelations->get_result()->fetch_all(MYSQLI_ASSOC);

$verzorgers = [];
foreach ($clientRelations as $relation) {
    $verzorger = DatabaseConnection::getConn()->prepare("SELECT * FROM medewerker WHERE id = ?");
    $verzorger->bind_param("i", $relation['medewerkerid']);
    $verzorger->execute();
    $verzorger = $verzorger->get_result()->fetch_assoc();
    array_push($verzorgers, $verzorger);
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="overzicht.css">
    <title>Overzicht van <?= $client['naam']; ?></title>
</head>
<body>
<?php
include_once '../../Includes/header.php';

?>
<div class="main">
    <?php
    include_once '../../Includes/sidebar.php';
    ?>
    <div class="main2">
        <div class="client">
            <?php
            echo "<div class='data'><pre class='datakey'>Geslacht</pre><div class='datavalue'>${client['geslacht']}</div></div>";
            echo "<div class='data'><pre class='datakey'>Geboortedatum</pre><div class='datavalue'>${client['geboortedatum']}</div></div>";
            echo "<div class='data'><pre class='datakey'>Adres</pre><div class='datavalue'>${client['adres']}</div></div>";
            echo "<div class='data'><pre class='datakey'>Postcode</pre><div class='datavalue'>${client['postcode']}</div></div>";
            echo "<div class='data'><pre class='datakey'>Woonplaats</pre><div class='datavalue'>${client['woonplaats']}</div></div>";
            echo "<div class='data'><pre class='datakey'>Telefoonnummer</pre><div class='datavalue'>${client['telefoonnummer']}</div></div>";
            echo "<div class='data'><pre class='datakey'>Email</pre><div class='datavalue'>${client['email']}</div></div>";
            echo "<div class='data'><pre class='datakey'>Afdeling</pre><div class='datavalue'>${client['afdeling']}</div></div>";
            echo "<div class='data'><pre class='datakey'>Nationaliteit</pre><div class='datavalue'>${client['nationaliteit']}</div></div>";
            echo "<div class='data'><pre class='datakey'>Burgerlijke staat</pre><div class='datavalue'>${client['burgelijkestaat']}</div></div>";
            echo "<a href='verzorgers.php?id=${_GET['id']}'><div class='data'><pre class='datakey'>Verzorger(s)</pre><div class='datavalue'>";
            foreach ($verzorgers as $verzorger) {
                echo $verzorger['naam'] . "<br>";
            }
            echo "</div></div></a>";
            ?>
        </div>
    </div>
</div>
</div>
</body>
</html>