<?php
include_once '../../database/DatabaseConnection.php';
include_once '../../models/autoload.php';

Auth::startSession();

Auth::requireLogin();

$clientId = isset($_GET['id']) ? (int)$_GET['id'] : ($_SESSION['clientId'] ?? null);
if (!$clientId) {
    header("Location: ../client.php");
    exit;
}
$_SESSION['clientId'] = (int)$clientId;

$ClientModel = new ClientModel();
$client = $ClientModel->getById($clientId);

if (!$client) {
    header("Location: ../client.php");
    exit;
}

// Controleer of de gebruiker beheerder is met de statische methode
if (!Auth::isAdmin()) {
    header("Location: ../patiëntgegevens/patiëntgegevens.php?id=$clientId");
    exit;
}

$pdo = DatabaseConnection::getPdo();

// Medewerkers ophalen
$medewerkers = [];
try {
    $medStmt = $pdo->prepare("SELECT * FROM medewerker WHERE is_deleted = 0 ORDER BY naam ASC");
    $medStmt->execute();
    $medewerkers = $medStmt->fetchAll();
} catch (Throwable $e) {
    $medStmt = $pdo->prepare("SELECT * FROM medewerker ORDER BY naam ASC");
    $medStmt->execute();
    $medewerkers = $medStmt->fetchAll();
}

// Actieve verzorgers ophalen (met fallback indien is_deleted nog niet aanwezig is)
$actieveVerzorgers = [];
try {
    $stmt = $pdo->prepare("
        SELECT medewerkerid 
        FROM verzorgerregel 
        WHERE clientid = :clientId AND is_deleted = 0
    ");
    $stmt->execute(['clientId' => $clientId]);
    $actieveVerzorgers = $stmt->fetchAll(PDO::FETCH_COLUMN);
} catch (Throwable $e) {
    $stmt = $pdo->prepare("
        SELECT medewerkerid 
        FROM verzorgerregel 
        WHERE clientid = :clientId
    ");
    $stmt->execute(['clientId' => $clientId]);
    $actieveVerzorgers = $stmt->fetchAll(PDO::FETCH_COLUMN);
}

// Vink alleen actief gekoppelde verzorgers aan
foreach ($medewerkers as $key => $medewerker) {
    if (in_array($medewerker['id'], $actieveVerzorgers)) {
        $medewerkers[$key]['checked'] = true;
    }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="Stylesheet" href="../../assets/css/client/verzorgers.css">
    <link rel="stylesheet" href="../../assets/vendor/bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css"
        integrity="sha512-Kc323vGBEqzTmouAECnVceyQqyqdsSiqLQISBL29aUW4U/M7pSPA/gEUZQqv1cwx4OnYxTxve5UMg5GT6L4JJg=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />
    <title>Verzorgers van <?= $client['naam'] ?></title>
    <link rel="icon" type="image/x-icon" href="../../assets/images/favicon.ico">
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
                <?php if (isset($_SESSION['error'])) { ?>
                    <div class="mb-3 alert alert-danger" role="alert">
                        <?php echo $_SESSION['error']; ?>
                    </div>
                <?php unset($_SESSION['error']);
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
</body>

</html>
