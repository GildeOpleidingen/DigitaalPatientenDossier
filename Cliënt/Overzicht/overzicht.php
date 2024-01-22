<?php
include_once '../../Database/DatabaseConnection.php';

if(!isset($_GET['id'])) {
   header("Location: ../client.php");
}


$id = $_GET['id'];
$_SESSION['clientId'] = $_GET['id'];

$result = DatabaseConnection::getConn()->prepare("SELECT * FROM client WHERE id = ?");
$result->bind_param("s", $id);
$result->execute();
$client = $result->get_result()->fetch_assoc();
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
        <div class  ="client">
            <?php
            echo "<div class='data'><pre class='datakey'>Geslacht</pre><div class='datavalue'</div>${client['geslacht']}</div></div>";
            echo "<div class='data'><pre class='datakey'>Geboortedatum</pre><div class='datavalue'</div>${client['geboortedatum']}</div></div>";
            echo "<div class='data'><pre class='datakey'>Adres</pre><div class='datavalue'</div>${client['adres']}</div></div>";
            echo "<div class='data'><pre class='datakey'>Postcode</pre><div class='datavalue'</div>${client['postcode']}</div></div>";
            echo "<div class='data'><pre class='datakey'>Woonplaats</pre><div class='datavalue'</div>${client['woonplaats']}</div></div>";
            echo "<div class='data'><pre class='datakey'>Telefoonnummer</pre><div class='datavalue'</div>${client['telefoonnummer']}</div></div>";
            echo "<div class='data'><pre class='datakey'>Email</pre><div class='datavalue'</div>${client['email']}</div></div>";
            echo "<div class='data'><pre class='datakey'>Afdeling</pre><div class='datavalue'</div>${client['afdeling']}</div></div>";
            echo "<div class='data'><pre class='datakey'>Nationaliteit</pre><div class='datavalue'</div>${client['nationaliteit']}</div></div>";
            echo "<div class='data'><pre class='datakey'>Burgerlijke staat</pre><div class='datavalue'</div>${client['burgelijkestaat']}</div></div>";
            ?>
        </div>
    </div>
</div>
</div>
</body>
</html>