<?php
require_once '../../includes/auth.php';
include '../../database/DatabaseConnection.php';
include_once '../../models/autoload.php';

$Main = new Main();
$ClientModel = new ClientModel();

// Haal clientId op uit URL of sessie
if (isset($_GET['id'])) {
    $_SESSION['clientId'] = (int)$_GET['id'];
}
$clientId = $_SESSION['clientId'] ?? null;

if (!$clientId) {
    header("Location: ../client.php");
    exit;
}

$client = $Main->getClientById($clientId);
if (!$client) {
    header("Location: ../client.php");
    exit;
}

$patroonTypes = $Main->getPatternTypes() ?? [];

// Als er een patroon is geselecteerd
$patroonId = isset($_GET['pt']) ? (int)$_GET['pt'] : null;
$patroonIndex = $patroonId !== null ? $patroonId - 1 : null;

if ($patroonId !== null) {
    // Valideer of het patroonId bestaat in de lijst
    if ($patroonId <= 0 || !isset($patroonTypes[$patroonIndex])) {
        header("Location: zorgplan.php");
        exit;
    }

    $patroonTypeId = (int)$patroonTypes[$patroonIndex][0];
    $patroonName = $patroonTypes[$patroonIndex][1] ?? "Patroon $patroonId";

    // Formulierverwerking (opslaan/bijwerken zorgplan)
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $p = trim($_POST['p'] ?? '');
        $e = trim($_POST['e'] ?? '');
        $s = trim($_POST['s'] ?? '');
        $doelen = trim($_POST['doelen'] ?? '');
        $interventies = trim($_POST['interventies'] ?? '');
        $evaluatiedoelen = trim($_POST['evaluatiedoelen'] ?? '');

        $saved = $Main->insertCarePlan(
            $clientId,
            date('Y-m-d H:i:s'),
            $patroonTypeId,
            $p,
            $e,
            $s,
            $doelen,
            $interventies,
            $evaluatiedoelen
        );

        if ($saved) {
            $_SESSION['succes'] = "Zorgplan voor {$patroonName} is succesvol opgeslagen.";
        } else {
            $_SESSION['error'] = "Er is een fout opgetreden bij het opslaan van het zorgplan.";
        }

        header("Location: zorgplan.php?pt={$patroonId}");
        exit;
    }

    // Haal bestaande zorgplangegevens op voor deze cliënt en dit patroon
    $patroonType = $Main->getPatternType($clientId, $patroonTypeId);
}
?>
<!DOCTYPE html>
<html lang="nl">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Zorgplan - <?= htmlspecialchars($client['naam'] ?? 'Cliënt') ?></title>
    <link rel="stylesheet" href="../../assets/css/client/zorgplan.css">
    <link rel="stylesheet" href="../../assets/vendor/bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css"
        integrity="sha512-Kc323vGBEqzTmouAECnVceyQqyqdsSiqLQISBL29aUW4U/M7pSPA/gEUZQqv1cwx4OnYxTxve5UMg5GT6L4JJg=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="icon" type="image/x-icon" href="../../assets/images/favicon.ico">
</head>

<body>
    <?php include_once '../../includes/n-header.php'; ?>

    <div class="main">
        <?php include_once '../../includes/n-sidebar.php'; ?>

        <div class="content">
            <div class="mt-4 mb-3 bg-white p-4 rounded-3 shadow-sm" style="height: 96%; overflow-y: auto;">

                <?php if (isset($_SESSION['succes'])): ?>
                    <div class="alert alert-success alert-dismissible fade show mb-3" role="alert">
                        <i class="fa fa-check-circle me-2"></i><?= htmlspecialchars($_SESSION['succes']); ?>
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Sluiten"></button>
                    </div>
                    <?php unset($_SESSION['succes']); ?>
                <?php endif; ?>

                <?php if (isset($_SESSION['error'])): ?>
                    <div class="alert alert-danger alert-dismissible fade show mb-3" role="alert">
                        <i class="fa fa-exclamation-triangle me-2"></i><?= htmlspecialchars($_SESSION['error']); ?>
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Sluiten"></button>
                    </div>
                    <?php unset($_SESSION['error']); ?>
                <?php endif; ?>

                <?php if ($patroonId === null): ?>
                    <!-- Overzicht: Selecteer een gezondheidspatroon -->
                    <div class="header mb-4">
                        <h2 class="fw-bold text-primary mb-1">Zorgplan: <?= htmlspecialchars($client['naam']) ?></h2>
                        <p class="text-muted">Selecteer een van de 11 gezondheidspatronen van Gordon om het bijbehorende zorgplan (PES, SMART-doelen en interventies) in te vullen of aan te passen.</p>
                    </div>

                    <div class="row g-3">
                        <?php foreach ($patroonTypes as $patroon): ?>
                            <div class="col-lg-6 col-md-6 col-sm-12">
                                <a href="zorgplan.php?pt=<?= (int)$patroon[0] ?>" class="card card-patroon text-decoration-none h-100 rounded-3 shadow-sm">
                                    <div class="card-body d-flex justify-content-between align-items-center p-3">
                                        <span class="fs-6 fw-semibold text-dark"><?= htmlspecialchars($patroon[1]) ?></span>
                                        <i class="bi bi-chevron-right text-muted fs-5"></i>
                                    </div>
                                </a>
                            </div>
                        <?php endforeach; ?>
                    </div>

                <?php else: ?>
                    <!-- Formulier: Bewerk PES en Zorgplan voor geselecteerd patroon -->
                    <div class="header mb-3">
                        <a href="zorgplan.php" class="text-decoration-none text-primary fw-bold d-inline-block mb-2">
                            <i class="fa-solid fa-arrow-left me-1"></i> Terug naar patronenoverzicht
                        </a>
                        <h2 class="fw-bold text-primary mb-1"><?= htmlspecialchars($patroonName) ?></h2>
                        <p class="text-muted">Cliënt: <strong><?= htmlspecialchars($client['naam']) ?></strong></p>
                    </div>

                    <form class="form" method="POST">
                        <div class="card border border-light-subtle rounded-3 p-3 mb-4 bg-light">
                            <h5 class="fw-bold text-secondary mb-3">1. PES-structuur</h5>
                            <div class="mb-3">
                                <label for="p" class="form-label fw-semibold">P (Probleem)</label>
                                <input type="text" id="p" name="p" value="<?= htmlspecialchars($patroonType['P'] ?? '') ?>" class="form-control" placeholder="Wat is het verpleegkundig probleem?">
                            </div>

                            <div class="mb-3">
                                <label for="e" class="form-label fw-semibold">E (Etiologie / Oorzaak)</label>
                                <input type="text" id="e" name="e" value="<?= htmlspecialchars($patroonType['E'] ?? '') ?>" class="form-control" placeholder="Wat is de oorzaak of de samenhangende factor?">
                            </div>

                            <div class="mb-3">
                                <label for="s" class="form-label fw-semibold">S (Symptomen / Verschijnselen)</label>
                                <input type="text" id="s" name="s" value="<?= htmlspecialchars($patroonType['S'] ?? '') ?>" class="form-control" placeholder="Wat zijn de waarneembare kenmerken en klachten?">
                            </div>
                        </div>

                        <div class="card border border-light-subtle rounded-3 p-3 mb-4 bg-light">
                            <h5 class="fw-bold text-secondary mb-3">2. Doelen en Interventies</h5>
                            <div class="mb-3">
                                <label for="doelen" class="form-label fw-semibold">Doel (SMART)</label>
                                <textarea id="doelen" name="doelen" class="form-control" rows="3" placeholder="Specifiek, Meetbaar, Acceptabel, Realistisch en Tijdgebonden doel"><?= htmlspecialchars($patroonType['doelen'] ?? '') ?></textarea>
                            </div>

                            <div class="mb-3">
                                <label for="interventies" class="form-label fw-semibold">Interventies</label>
                                <textarea id="interventies" name="interventies" class="form-control" rows="3" placeholder="Welke specifieke verpleegkundige acties worden ondernomen?"><?= htmlspecialchars($patroonType['interventies'] ?? '') ?></textarea>
                            </div>

                            <div class="mb-3">
                                <label for="evaluatiedoelen" class="form-label fw-semibold">Evaluatiedoelen</label>
                                <textarea id="evaluatiedoelen" name="evaluatiedoelen" class="form-control" rows="3" placeholder="Hoe en wanneer wordt het resultaat geëvalueerd?"><?= htmlspecialchars($patroonType['evaluatiedoelen'] ?? '') ?></textarea>
                            </div>
                        </div>

                        <div class="d-flex gap-2">
                            <button class="btn btn-primary px-4 py-2" type="submit">Opslaan</button>
                            <a href="zorgplan.php" class="btn btn-outline-secondary px-4 py-2">Annuleren</a>
                        </div>
                    </form>
                <?php endif; ?>

            </div>
        </div>
    </div>

</body>

</html>