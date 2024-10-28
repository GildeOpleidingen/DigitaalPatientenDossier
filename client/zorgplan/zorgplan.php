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
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" integrity="sha384-tViUnnbYAV00FLIhhi3v/dWt3Jxw4gZQcNoSCxCIFNJVCx7/D55/wXsrNIRANwdD" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css" integrity="sha512-Kc323vGBEqzTmouAECnVceyQqyqdsSiqLQISBL29aUW4U/M7pSPA/gEUZQqv1cwx4OnYxTxve5UMg5GT6L4JJg==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <title>Zorgplan</title>
</head>

<body>
    <?php include_once '../../includes/n-header.php'; ?>

    <div class="main">

        <?php include_once '../../includes/n-sidebar.php'; ?>

        <div class="content">
            <div class="mt-4 mb-3 bg-white p-3" style="height: 96%; overflow: auto;">
                <p class="card-text">
                    <?php if (!isset($patroonId)) { ?>
                        <?php if($Main->getMedischOverzichtByClientId($_SESSION['clientId'])){ ?>
                        <div class="header">
                            <a href='../clientverhaal/clientverhaal.php?id=<?=$_SESSION['clientId']?>' class="h1 fw-bold text-decoration-none text-primary">Clientverhaal invullen <i class="h3 bi bi-plus-square-dotted"></i></a>

                            <div class="mt-3 row">
                            <?php foreach ($Main->getPatternTypes() as $patroonType) { ?>
                            <div class="col-lg-6 col-md-6 col-sm-12">
                                <a href="?pt=<?= $patroonType[0] ?>" class="card text-decoration-none mb-1">
                                    <div class="card-text fw-light h4 ps-3 p-2">
                                        <div class="row">
                                            <div class="col-lg-11 col-md-10 col-sm-9">
                                                <?= $patroonType[1] ?>
                                            </div>
                                            <div class="col-lg-1 col-md-2 col-sm-3">
                                                <i class="bi bi-chevron-right" style="font-size: 20px;"></i>
                                            </div>
                                        </div>    
                                    </div>
                                </a>
                            </div>
                            <?php } ?>
                            </div>
                        </div>
                        <?php }?>
                    <?php } else { ?>
                        <div class="header">
                        <a href='zorgplan.php' class='mb-3 text-decoration-none text-primary fw-bold'><i class='fa-xs fa-solid fa-arrow-left'></i> Teruggaan</a>
                            <h1 class="title"><?= $patroonTypes[$patroonId-1][1] ?></h1>
                        </div>
                        <form class="form" method="POST">
                            <div class="mt-3">
                                <label for="p">P</label>
                                <input type="text" class="pes" name="p" value="<?= $patroonType["P"] ?? "" ?>" class="form-control">
                            </div>
                            
                            <div class="mt-3">
                                <label for="e">E</label>
                                <input type="text" class="pes" name="e" value="<?= $patroonType["E"] ?? "" ?>" class="form-control">
                            </div>
                            <div class="mt-3">
                                <label for="s">S</label>
                                <input type="text" class="pes" name="s" value="<?= $patroonType["S"] ?? "" ?>" class="form-control">
                            </div>
                            <div class="mt-3">
                                <label for="doelen">Doel (SMART)</label>
                                <textarea name="doelen" class="form-control"><?= $patroonType["doelen"] ?? "" ?></textarea>
                            </div>
                            <div class="mt-3">
                                <label for="interventies">Interventies</label>
                                <textarea name="interventies" class="form-control"><?= $patroonType["interventies"] ?? "" ?></textarea>
                            </div>
                            <div class="mt-3">
                                <label for="evaluatiedoelen">Evaluatiedoelen</label>
                                <textarea name="evaluatiedoelen" class="form-control"><?= $patroonType["evaluatiedoelen"] ?? "" ?></textarea>
                            </div>
                            <button class="mt-3 btn btn-secondary w-100" type="submit" name="submit">Opslaan</button>
                        </form>
                    <?php } ?>
                </p>
            </div>
        </div>
    </div>
</body>

</html>