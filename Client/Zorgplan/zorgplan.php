<?php
session_start();
include '../../Database/DatabaseConnection.php';
include '../../Functions/ClientFunctions.php';
// $id = $_GET['id'];

$patroonTypes = getPatternTypes();

if (isset($_GET['pt'])) {
    $patroonId = $_GET['pt'];
    $patroonType = getPatternType($patroonId);
    if($_SERVER['REQUEST_METHOD'] == 'POST'){
        insertCarePlan($_SESSION['clientId'], date('Y-m-d h:i:s'), $patroonTypes[$patroonId-1][0], $_POST['p'], $_POST['e'], $_POST['s'], $_POST['doelen'], $_POST['interventies'], $_POST['evaluatiedoelen']);
        header("Location: zorgplan.php?pt=$patroonId");
    }
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
                <?php if (!isset($patroonId)) { ?>
                    <?php if(getMedischOverzichtByClientId($_SESSION['clientId'])){ ?>
                        <div class="header" style="padding: 20px; background-color: rgb(240, 240, 240);">
                            <a href='../Clientverhaal/clientverhaal.php?id=<?=$_SESSION['clientId']?>'><h1 style="color: #00365E"> Clientverhaal invullen </h1></a>
                        </div>
                        <?php }?>
                    <div class="content">
                        <?php foreach (getPatternTypes() as $patroonType) { ?>
                            <a href="?pt=<?= $patroonType[0] ?>" class="patroon title"><?= $patroonType[1] ?></a>
                        <?php } ?>
                    </div>
                    <?php } else { ?>
                        <div class="header">
                            <a href="zorgplan.php" class="title">Terug</a>
                            <h1 class="title"><?= $patroonTypes[$patroonId-1][1] ?></h1>
                        </div>
                        <form class="form" method="POST">
                            <div class="question">
                                <p>P</p>
                                <input type="text" name="p"><?= $patroonType["P"] ?? "" ?>
                            </div>
                            <div class="question">
                                <p>E</p>
                                <input type="text" name="e"><?= $patroonType["E"] ?? "" ?>
                            </div>
                            <div class="question">
                                <p>S</p>
                                <input type="text" name="s"><?= $patroonType["S"] ?? "" ?>
                            </div>
                            <div class="question">
                                <p>Doelen (SMART)</p>
                                <textarea name="doelen"><?= $patroonType["doelen"] ?? "" ?></textarea>
                            </div>
                            <div class="question">
                                <p>Interventies</p>
                                <textarea name="interventies"><?= $patroonType["interventies"] ?? "" ?></textarea>
                            </div>
                            <div class="question">
                                <p>Evaluatiedoelen</p>
                                <textarea name="evaluatiedoelen"><?= $patroonType["evaluatiedoelen"] ?? "" ?></textarea>
                            </div>
                            <input class="submit" type="submit" name="submit" value="Opslaan">
                        </form>
                    <?php } ?>
            </div>
        </div>
    </div>
</body>

</html>