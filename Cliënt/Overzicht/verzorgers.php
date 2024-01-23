<?php
session_start();
include_once '../../Database/DatabaseConnection.php';

if(!isset($_GET['id'])) {
    header("Location: ../cliënt.php");
}

$id = $_GET['id'];
$_SESSION['clientId'] = $_GET['id'];

$client = DatabaseConnection::getConn()->prepare("SELECT * FROM client WHERE id = ?");
$client->bind_param("s", $id);
$client->execute();
$client = $client->get_result()->fetch_assoc();

$clientRelaties = DatabaseConnection::getConn()->prepare("SELECT * FROM verzorgerregel WHERE clientid = ?");
$clientRelaties->bind_param("s", $id);
$clientRelaties->execute();
$clientRelaties = $clientRelaties->get_result()->fetch_all(MYSQLI_ASSOC);

$verzorgers = [];
foreach ($clientRelaties as $relation) {
    $verzorger = DatabaseConnection::getConn()->prepare("SELECT * FROM medewerker WHERE id = ?");
    $verzorger->bind_param("s", $relation['medewerkerid']);
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
    <title>Verzorgers van <?= $client['naam']; ?></title>
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
            <form method="POST">
                <input type="hidden" value="<?= $client['id']; ?>" name="clientId">
                <?php
                foreach ($verzorgers as $verzorger) {
                    echo "<div class='data'><pre class='datakey'>" . $verzorger['naam'] . "</pre><div class='datavalue'><input type='checkbox' name='verwijder[${verzorger["id"]}]'></div></div>";
                }
                ?>

                <select name="medewerkerId">
                    <option value="" disabled selected>Selecteer een verzorger</option>
                    <?php
                    $result = DatabaseConnection::getConn()->query("SELECT id, naam FROM medewerker;");
                    while ($row = $result->fetch_array()) {
                        if (!in_array($row[0], array_column($verzorgers, 'id'))) {
                            echo "<option value='$row[0]'>$row[1]</option>";
                        }
                    }

                    ?>
                </select>

                <button type="submit" formaction="Verzorger/voegtoe.php">Voeg toe aan client</button>
                <button type="submit" formaction="Verzorger/verwijder.php">Verwijder van client</button>
            </form>
        </div>


    </div>
</div>
</div>
</body>
</html>