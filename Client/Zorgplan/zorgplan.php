<?php
session_start();
require_once('../../includes/auth.php');
include '../../database/DatabaseConnection.php';
include_once '../../classes/Main.php';
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
    <link rel="stylesheet" href="zorgplan.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" integrity="sha384-tViUnnbYAV00FLIhhi3v/dWt3Jxw4gZQcNoSCxCIFNJVCx7/D55/wXsrNIRANwdD" crossorigin="anonymous">
    <link rel="stylesheet" href="../../assets/css/bootstrap.min.css">
    <title>Zorgplan</title>
</head>

<body>
    <?php include_once '../../includes/n-header.php'; ?>

    <div class="main">

        <?php include_once '../../includes/n-sidebar.php'; ?>

        <div class="content">
            <div class="mt-5 mb-3 bg-white p-3">
                <p class="card-text">
                    <?php if (!isset($patroonId)) { ?>
                        <?php if($Main->getMedischOverzichtByClientId($_SESSION['clientId'])){ ?>
                        <div class="header">
                            <a href='../clientverhaal/clientverhaal.php?id=<?=$_SESSION['clientId']?>' class="h1 fw-bold text-decoration-none text-primary">Clientverhaal invullen <i class="h3 bi bi-plus-square-dotted"></i></a>

                            <div class="row">
                            <?php foreach ($Main->getPatternTypes() as $patroonType) { ?>
                            <div class="col-lg-6 col-md-6 col-sm-12">
                            <a href="?pt=<?= $patroonType[0] ?>" class=""><?= $patroonType[1] ?></a>
                            </div>
                            <?php } ?>
                            </div>
                        </div>
                        <?php }?>
                    <?php } else { ?>
                    <?php } ?>
                </p>
            </div>
        </div>
    </div>
</body>

</html>