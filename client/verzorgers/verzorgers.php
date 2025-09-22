<?php
session_start();
include_once '../../database/DatabaseConnection.php';
include_once '../../models/autoload.php';
$Main = new Main();

$clientId = $_SESSION['clientId'];
$client = $Main->getClientById($clientId);
if (!isset($clientId) || $client == null) {
    header("Location: ../../index.php");
}

$medewerkers = DatabaseConnection::getConn()->prepare("SELECT * FROM medewerker");
$medewerkers->execute();
$medewerkers = $medewerkers->get_result()->fetch_all(MYSQLI_ASSOC);

$stmt = DatabaseConnection::getConn()->prepare("SELECT * FROM verzorgerregel WHERE clientid = ?");
$stmt->bind_param("i", $clientId);
$stmt->execute();
$stmt = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
// Loop door alle medewerkers heen, als de medewerker al in de database staat, zet dan de checked variabele op true
foreach ($medewerkers as $key => $medewerker) {
    foreach ($stmt as $relation) {
        if ($medewerker['id'] == $relation['medewerkerid']) {
            $medewerkers[$key]['checked'] = true;
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="Stylesheet" href="../../assets/css/client/verzorgers.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" 
          rel="stylesheet" 
          integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" 
          crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css" 
          integrity="sha512-Kc323vGBEqzTmouAECnVceyQqyqdsSiqLQISBL29aUW4U/M7pSPA/gEUZQqv1cwx4OnYxTxve5UMg5GT6L4JJg==" 
          crossorigin="anonymous" referrerpolicy="no-referrer" />
    <title>Verzorgers van <?= $client['naam'] ?></title>
</head>

<body>
    <?php
    include_once '../../includes/n-header.php';
    ?>
    <div class="main">
        <?php
        include_once '../../includes/n-sidebar.php';
        ?>
        <div class="content">
            <div class="mt-4 mb-3 bg-white p-3 d-flex flex-column" style="height: 96%; overflow: auto;">
                <?php if (isset($_SESSION['succes'])) { ?>
                    <div class="mb-3 alert alert-success" role="alert">
                        <?php echo $_SESSION['succes']; ?>
                    </div>
                <?php unset($_SESSION['succes']);
                } ?>
                <a href='../patiëntgegevens/patiëntgegevens.php?id=<?= $clientId ?>' class='mb-3 text-decoration-none text-primary fw-bold'><i class='fa-xs fa-solid fa-arrow-left'></i> Teruggaan</a>
                <h3>Verzorgers van <?= $client['naam'] ?></h3>
                <form action="../overzicht/verzorger/verwerk.php" method="post" class="flex-grow-1 d-flex flex-column">
                    <input type="hidden" name="clientId" value="<?= $clientId ?>">
                    <div class="form-content flex-grow-1">
                        <div class="form">
                            <div class="mt-3 questionnaire">
                                <?php foreach ($medewerkers as $medewerker) { ?>
                                    <div class="row">
                                        <div class="col-lg-11 col-md-10 col-sm-9">
                                            <p><?= $medewerker['naam'] ?></p>
                                        </div>
                                        <div class="col-lg-1 col-md-2 col-sm-3">
                                            <input type="hidden" name="verzorgers[<?= $medewerker['id'] ?>]">
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox" name="verzorgers[<?= $medewerker['id'] ?>]" id="verzorger-<?= $medewerker['id'] ?>" <?php if (isset($medewerker['checked'])) {
                                                                                                                                                                                        echo "checked";
                                                                                                                                                                                    } ?>>
                                                <label class="form-check-label" for="verzorger-<?= $medewerker['id'] ?>"></label>
                                            </div>
                                        </div>
                                    </div>
                                <?php } ?>
                            </div>
                        </div>
                    </div>
                    <div class="mt-3">
                        <button type="submit" class="btn btn-secondary w-100">Opslaan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" 
            integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" 
            crossorigin="anonymous"></script> 
</body>

</html>