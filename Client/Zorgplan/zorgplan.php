<?php
session_start();
include '../../Database/DatabaseConnection.php';
include '../../Functions/ClientFunctions.php';
// $id = $_GET['id'];
// if(getMedischOverzichtByClientId($id)){
//     echo "<a href='../Clientverhaal/clientverhaal.php?id=$id'> Clientverhaal invullen </a>";
// }

if (isset($_GET['pt'])) {
    $pt = $_GET['pt'];
    $patroonTypes = getPatroonTypes();
    $_SESSION['patroonType'] = $patroonTypes[$pt];
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="zorgplan.css">
    <title>Zorgplan</title>
</head>

<body>
    <?php include_once '../../Includes/header.php'; ?>

    <div class="main">

        <?php include_once '../../Includes/sidebar.php'; ?>

        <div class="main2">
            <div class="main3">
                <?php if (!isset($pt)) { ?>
                    <div class="header">
                        <p class="title">Zorgplan</p>
                    </div>
                    <div class="content">
                        <?php foreach (getPatroonTypes() as $patroonType) { ?>
                            <a href="?id=<?= $_SESSION['clientId'] ?>&pt=<?= $patroonType[0] ?>" class="title"><?= $patroonType[1] ?></a>
                        <?php } ?>
                    </div>
                    <?php } else { ?>
                        <div class="header">
                            <a href="?id=<?= $_SESSION['clientId'] ?>" class="title">Terug</a>
                            <h1 class="title"><?= $patroonTypes[$pt-1][1] ?></h1>
                        </div>
                        <form class="form">
                            <div class="question">
                                <p>P</p>
                                <textarea name="p"></textarea>
                            </div>
                            <div class="question">
                                <p>E</p>
                                <textarea name="e"></textarea>
                            </div>
                            <div class="question">
                                <p>S</p>
                                <textarea name="s"></textarea>
                            </div>
                            <div class="question">
                                <p>Doelen (SMART)</p>
                                <textarea name="test"></textarea>
                            </div>
                            <div class="question">
                                <p>Interventies</p>
                                <textarea name="test"></textarea>
                            </div>
                            <div class="question">
                                <p>Evaluatiedoelen</p>
                                <textarea name="test"></textarea>
                            </div>
                        <input type="submit" value="Opslaan">
                        </form>
                    <?php } ?>
            </div>
        </div>
    </div>
</body>

</html>