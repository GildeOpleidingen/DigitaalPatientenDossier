<?php
session_start();
require_once('../../includes/auth.php');
include '../../database/DatabaseConnection.php';
include_once '../../models/autoload.php';
$Main = new Main();

$patroonTypes = $Main->getPatternTypes();

if (isset($_GET['pt'])) {
    $patroonId = $_GET['pt'];
    $patroonType = $Main->getPatternType($patroonId);
    if($_SERVER['REQUEST_METHOD'] == 'POST'){
        $Main->insertCarePlan($_SESSION['clientId'], date('Y-m-d h:i:s'), $patroonTypes[$patroonId-1][0], $_POST['p'], $_POST['e'], $_POST['s'], $_POST['doelen'], $_POST['interventies'], $_POST['evaluatiedoelen']);
        header("Location: zorgplan.php?pt=$patroonId");
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../../assets/css/client/zorgplan.css">
    <link rel="stylesheet" href="../../assets/css/bootstrap.min.css">
    <title>Zorgplan</title>
</head>

<body>
    <?php include_once '../../includes/n-header.php'; ?>

    <div class="main">

        <?php include_once '../../includes/n-sidebar.php'; ?>

        <div class="content">
            <div class="mt-4 mb-3 bg-white p-3" style="height: 96%; overflow: auto;">
                <div class="card-text">
                <?php if (!isset($patroonId)) { ?>
                    <?php if($Main->getMedischOverzichtByClientId($_SESSION['clientId'])){ ?>
                        <div class="header" style="padding: 20px; background-color: rgb(240, 240, 240);">
                            <a href='../clientverhaal/clientverhaal.php?id=<?=$_SESSION['clientId']?>'><h1 style="color: #00365E">Clientverhaal invullen</h1></a>
                        </div>
                        <?php }?>
                    <div class="content">
                        <?php foreach ($Main->getPatternTypes() as $patroonType) { ?>
                            <div class="row">
                                <div class="col-lg-6 col-md-6 col-sm-12">
                                <a href="?pt=<?= $patroonType[0] ?>" class=""><?= $patroonType[1] ?></a>
                                </div>
                            </div>
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
                                <input type="text" class="pes" name="p" value="<?= $patroonType["P"] ?? "" ?>">
                            </div>
                            <div class="question">
                                <p>E</p>
                                <input type="text" class="pes" name="e" value="<?= $patroonType["E"] ?? "" ?>">
                            </div>
                            <div class="question">
                                <p>S</p>
                                <input type="text" class="pes" name="s" value="<?= $patroonType["S"] ?? "" ?>">
                            </div>
                            <div class="question">
                                <p>Doel (SMART)</p>
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
    </div>
</body>

</html>