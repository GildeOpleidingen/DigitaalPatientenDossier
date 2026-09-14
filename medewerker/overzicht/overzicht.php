<?php
session_start();
include_once '../../database/DatabaseConnection.php';
include_once '../../models/autoload.php';

$Main = new Main();
$ClientModel = new ClientModel();

if (!isset($_GET['id']) || empty($_GET['id'])) {
    header("Location: ../../index.php");
    exit;
}

$medewerkerId = (int)$_GET['id'];
$_SESSION['medewerkerId'] = $medewerkerId;

// Haal medewerkergegevens op via Main (oude Medewerker trait)
$verzorger = $Main->getMedewerkerById($medewerkerId);
if (!$verzorger) {
    $stmt = DatabaseConnection::getConn()->prepare("SELECT * FROM medewerker WHERE id = ?");
    $stmt->bind_param("i", $medewerkerId);
    $stmt->execute();
    $verzorger = $stmt->get_result()->fetch_assoc() ?: [];
    $stmt->close();
}

// Haal gekoppelde cliënten op via verzorgerregel
$stmt = DatabaseConnection::getConn()->prepare("SELECT clientid FROM verzorgerregel WHERE medewerkerid = ?");
$stmt->bind_param("i", $medewerkerId);
$stmt->execute();
$clientRelations = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
$stmt->close();

// Vul cliëntgegevens aan met behulp van het nieuwe ClientModel (en Main)
$clients = [];
foreach ($clientRelations as $relation) {
    $clientId = (int)$relation['clientid'];

    // Gebruik het nieuwe ClientModel voor betrouwbare en getypeerde gegevens
    $client = $ClientModel->getById($clientId);

    // Als alternatief is ook de oude trait via $Main beschikbaar:
    // $clientOld = $Main->getClientById($clientId);

    if ($client) {
        $clients[] = $client;
    }
}
?>
<!DOCTYPE html>
<html lang="nl">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="Stylesheet" href="../../assets/css/medewerker/overzicht.css">
    <link rel="stylesheet" href="../../assets/css/bootstrap.min.css">
    <title>Overzicht van <?= htmlspecialchars($verzorger['naam'] ?? 'Medewerker') ?></title>
</head>

<body>
    <div class="main">
        <?php
        include '../../includes/n-header.php';
        ?>

        <?php
        include '../../includes/medewerker-sidebar.php';
        ?>

        <div class="content">
            <div class="mt-4 mb-3 bg-white p-4 rounded shadow-sm" style="height: 96%; overflow: auto;">
                <h2 class="text-primary mb-3">Medewerker Gegevens</h2>
                <div class="row mb-4">
                    <div class="col-md-6">
                        <p><strong>Naam:</strong> <?= htmlspecialchars($verzorger['naam'] ?? '-') ?></p>
                        <p><strong>Klas:</strong> <?= htmlspecialchars($verzorger['klas'] ?? '-') ?></p>
                    </div>
                    <div class="col-md-6">
                        <p><strong>E-mail:</strong> <?= htmlspecialchars($verzorger['email'] ?? '-') ?></p>
                        <p><strong>Telefoonnummer:</strong> <?= htmlspecialchars($verzorger['telefoonnummer'] ?? '-') ?></p>
                    </div>
                </div>

                <hr>

                <h3 class="text-primary mb-3">Gekoppelde Cliënten</h3>
                <?php if (!empty($clients)): ?>
                    <div class="table-responsive">
                        <table class="table table-striped table-hover align-middle">
                            <thead class="table-light">
                                <tr>
                                    <th>ID</th>
                                    <th>Naam</th>
                                    <th>Woonplaats</th>
                                    <th>Geboortedatum</th>
                                    <th>Afdeling</th>
                                    <th>Actie</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($clients as $c): ?>
                                    <tr>
                                        <td><?= htmlspecialchars($c['id']) ?></td>
                                        <td><strong><?= htmlspecialchars($c['naam']) ?></strong></td>
                                        <td><?= htmlspecialchars($c['woonplaats'] ?? '-') ?></td>
                                        <td>
                                            <?php
                                            if (!empty($c['geboortedatum'])) {
                                                echo date_create($c['geboortedatum'])->format('d-m-Y');
                                            } else {
                                                echo '-';
                                            }
                                            ?>
                                        </td>
                                        <td><?= htmlspecialchars($c['afdeling'] ?? '-') ?></td>
                                        <td>
                                            <a href="../../client/overzicht/overzicht.php?id=<?= $c['id'] ?>" class="btn btn-sm btn-primary">
                                                Bekijk Dossier
                                            </a>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                <?php else: ?>
                    <p class="text-muted">Er zijn momenteel geen cliënten gekoppeld aan deze medewerker.</p>
                <?php endif; ?>
            </div>
        </div>
    </div>
</body>

</html>