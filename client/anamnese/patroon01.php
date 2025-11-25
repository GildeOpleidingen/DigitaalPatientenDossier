<?php
session_start();
include '../../database/DatabaseConnection.php';
include_once '../../models/autoload.php';
$Main = new Main();

$clientId = $_SESSION['clientId'];
$antwoorden = $Main->getPatternAnswers($clientId, 1);

$boolArrayObservatie = isset($antwoorden['observatie']) ? str_split($antwoorden['observatie']) : [];

// HELPERS
function field($name) {
    return isset($_POST[$name]) && $_POST[$name] !== '' ? trim($_POST[$name]) : null;
}

$conn = DatabaseConnection::getConn();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    // 1 — Observaties omzetten naar 10-cijferige binaire string
    $observatieString = "";
    for ($i = 1; $i <= 10; $i++) {
        $observatieString .= isset($_POST["observatie$i"]) ? "1" : "0";
    }

    // 2 — Vragenlijst ID ophalen
    $stmt = $conn->prepare("
        SELECT vl.id
        FROM vragenlijst vl
        LEFT JOIN verzorgerregel vr ON vr.id = vl.verzorgerregelid
        WHERE vr.clientid = ?
    ");
    $stmt->bind_param("i", $clientId);
    $stmt->execute();
    $vragenlijst = $stmt->get_result()->fetch_assoc();

    if ($vragenlijst) {
        $vragenlijstId = $vragenlijst['id'];
    } else {
        // Geen vragenlijst? → Aanmaken
        $stmt2 = $conn->prepare("
            INSERT INTO vragenlijst (verzorgerregelid)
            VALUES (
                (SELECT id FROM verzorgerregel WHERE clientid = ? AND medewerkerid = ?)
            )
        ");
        $stmt2->bind_param("ii", $clientId, $_SESSION['loggedin_id']);
        $stmt2->execute();

        // Opnieuw ophalen
        $stmt = $conn->prepare("
            SELECT vl.id
            FROM vragenlijst vl
            LEFT JOIN verzorgerregel vr ON vr.id = vl.verzorgerregelid
            WHERE vr.clientid = ?
        ");
        $stmt->bind_param("i", $clientId);
        $stmt->execute();
        $vragenlijstId = $stmt->get_result()->fetch_assoc()['id'];
    }

    // 3 — Bestaat patroon01 al?
    $stmt = $conn->prepare("
        SELECT id FROM patroon01gezondheidsbeleving WHERE vragenlijstid = ?
    ");
    $stmt->bind_param("i", $vragenlijstId);
    $stmt->execute();
    $existing = $stmt->get_result()->fetch_assoc();

    // 4 — DATA ARRAY
    $data = [
        field('algemene_gezondheid'),
        field('gezondheids_bezigheid'),
        field('rookt') ?? 0,
        field('rookt_hoeveelheid'),
        field('drinkt') ?? 0,
        field('drinkt_hoeveelheid'),
        field('besmettelijke_aandoening') ?? 0,
        field('besmettelijke_aandoening_welke'),
        field('alergieen') ?? 0,
        field('alergieen_welke'),
        field('oorzaak_huidige_toestand'),
        field('oht_actie'),
        field('oht_hoe_effectief'),
        field('oht_wat_nodig'),
        field('oht_wat_belangrijk'),
        field('oht_reactie_op_advies'),
        field('preventie'),
        $observatieString,
        $vragenlijstId
    ];

    // 5 — UPDATE
    if ($existing) {

        $sql = "
            UPDATE patroon01gezondheidsbeleving SET
                algemene_gezondheid = ?,
                gezondheids_bezigheid = ?,
                rookt = ?,
                rookt_hoeveelheid = ?,
                drinkt = ?,
                drinkt_hoeveelheid = ?,
                besmettelijke_aandoening = ?,
                besmettelijke_aandoening_welke = ?,
                alergieen = ?,
                alergieen_welke = ?,
                oorzaak_huidige_toestand = ?,
                oht_actie = ?,
                oht_hoe_effectief = ?,
                oht_wat_nodig = ?,
                oht_wat_belangrijk = ?,
                oht_reactie_op_advies = ?,
                preventie = ?,
                observatie = ?
            WHERE vragenlijstid = ?
        ";

        $stmt = $conn->prepare($sql);
        $stmt->bind_param(
            "ssissississssssssss",
            ...$data
        );

    } else {
        // 6 — INSERT
        $sql = "
            INSERT INTO patroon01gezondheidsbeleving (
                algemene_gezondheid,
                gezondheids_bezigheid,
                rookt,
                rookt_hoeveelheid,
                drinkt,
                drinkt_hoeveelheid,
                besmettelijke_aandoening,
                besmettelijke_aandoening_welke,
                alergieen,
                alergieen_welke,
                oorzaak_huidige_toestand,
                oht_actie,
                oht_hoe_effectief,
                oht_wat_nodig,
                oht_wat_belangrijk,
                oht_reactie_op_advies,
                preventie,
                observatie,
                vragenlijstid
            ) VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)
        ";

        $stmt = $conn->prepare($sql);
        $stmt->bind_param(
            "ssissississssssssss",
            ...$data
        );
    }

    $stmt->execute();

    // 7 — NAVIGATIE
    if (isset($_POST['navbutton'])) {
        switch ($_POST['navbutton']) {
            case 'next':
                header("Location: patroon02.php");
                exit;
            case 'prev':
                header("Location: patroon11.php");
                exit;
        }
    }
}
?>


<!DOCTYPE html>
<html lang="nl">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Anamnese – Patroon 1</title>

    <link rel="stylesheet" href="../../assets/css/client/patronen.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css">
</head>

<body style="overflow: hidden;">
<form action="" method="post">

    <div class="main">
        <?php include '../../includes/n-header.php'; ?>
        <?php include '../../includes/n-sidebar.php'; ?>

        <div class="mt-5 pt-5 content">
            <div class="mt-4 mb-3 bg-white p-4" style="height: 90%; overflow: auto;">

                <h4 class="text-primary mb-4">1. Patroon van gezondheidsbeleving en -instandhouding</h4>

                <div class="form-content">
                    <div class="form">

                        <!-- ALGEMENE GEZONDHEID -->
                        <div class="question mb-3">
                            <p>Hoe is uw gezondheid in het algemeen?</p>
                            <textarea class="form-control" rows="1" name="algemene_gezondheid">
                                <?= htmlspecialchars($antwoorden['algemene_gezondheid'] ?? '') ?>
                            </textarea>
                        </div>

                        <!-- WAT DOET U OM GEZOND TE BLIJVEN -->
                        <div class="question mb-3">
                            <p>Wat doet u om gezond te blijven?</p>
                            <textarea class="form-control" rows="1" name="gezondheids_bezigheid">
                                <?= htmlspecialchars($antwoorden['gezondheids_bezigheid'] ?? '') ?>
                            </textarea>
                        </div>

                        <!-- ROOKT U? -->
                        <div class="question mb-3">
                            <p>Rookt u?</p>
                            <div class="checkboxes d-flex flex-column gap-2">

                                <label class="d-flex align-items-center gap-2">
                                    <input type="radio" name="rookt" value="1"
                                           <?= isset($antwoorden['rookt']) ? 'checked' : '' ?>>
                                    Ja
                                    <textarea name="rookt_hoeveelheid"
                                              class="form-control"
                                              style="max-width: 250px; height: 32px;"
                                              placeholder="Hoeveel?"><?= htmlspecialchars($antwoorden['rookt_hoeveelheid'] ?? '') ?></textarea>
                                </label>

                                <label>
                                    <input type="radio" name="rookt" value="0"
                                           <?= !isset($antwoorden['rookt']) ? 'checked' : '' ?>>
                                    Nee
                                </label>

                            </div>
                        </div>

                        <!-- DRINKT U? -->
                        <div class="question mb-3">
                            <p>Drinkt u?</p>
                            <div class="checkboxes d-flex flex-column gap-2">

                                <label class="d-flex align-items-center gap-2">
                                    <input type="radio" name="drinkt" value="1"
                                           <?= isset($antwoorden['drinkt']) ? 'checked' : '' ?>>
                                    Ja
                                    <textarea name="drinkt_hoeveelheid"
                                              class="form-control"
                                              style="max-width: 250px; height: 32px;"
                                              placeholder="Hoeveel?"><?= htmlspecialchars($antwoorden['drinkt_hoeveelheid'] ?? '') ?></textarea>
                                </label>

                                <label>
                                    <input type="radio" name="drinkt" value="0"
                                           <?= !isset($antwoorden['drinkt']) ? 'checked' : '' ?>>
                                    Nee
                                </label>

                            </div>
                        </div>

                        <!-- INFECTIES / BESMETTELIJKE AANDOENINGEN -->
                        <div class="question mb-3">
                            <p>Heeft u momenteel een infectie of overdraagbare besmettelijke aandoening?</p>
                            <div class="checkboxes d-flex flex-column gap-2">

                                <label class="d-flex align-items-center gap-2">
                                    <input type="radio" name="besmettelijke_aandoening" value="1"
                                           <?= isset($antwoorden['besmettelijke_aandoening']) ? 'checked' : '' ?>>
                                    Ja
                                    <textarea name="besmettelijke_aandoening_welke"
                                              class="form-control"
                                              style="max-width: 250px; height: 32px;"
                                              placeholder="En wel?"><?= htmlspecialchars($antwoorden['besmettelijke_aandoening_welke'] ?? '') ?></textarea>
                                </label>

                                <label>
                                    <input type="radio" name="besmettelijke_aandoening" value="0"
                                           <?= !isset($antwoorden['besmettelijke_aandoening']) ? 'checked' : '' ?>>
                                    Nee
                                </label>

                            </div>
                        </div>

                        <!-- ALLERGIEËN -->
                        <div class="question mb-3">
                            <p>Bent u ergens allergisch voor?</p>
                            <div class="checkboxes d-flex flex-column gap-2">

                                <label class="d-flex align-items-center gap-2">
                                    <input type="radio" name="alergieen" value="1"
                                           <?= isset($antwoorden['alergieen']) ? 'checked' : '' ?>>
                                    Ja
                                    <textarea name="alergieen_welke"
                                              class="form-control"
                                              style="max-width: 250px; height: 32px;"
                                              placeholder="En wel?"><?= htmlspecialchars($antwoorden['alergieen_welke'] ?? '') ?></textarea>
                                </label>

                                <label>
                                    <input type="radio" name="alergieen" value="0"
                                           <?= !isset($antwoorden['alergieen']) ? 'checked' : '' ?>>
                                    Nee
                                </label>

                            </div>
                        </div>

                        <!-- OORZAAK HUIDIGE SITUATIE -->
                        <div class="question mb-3">
                            <p>Wat denkt u dat de oorzaak is van uw huidige situatie/toestand?</p>
                            <textarea class="form-control" rows="1" name="oorzaak_huidige_toestand">
                                <?= htmlspecialchars($antwoorden['oorzaak_huidige_toestand'] ?? '') ?>
                            </textarea>
                        </div>

                        <!-- OHT VRAGEN -->
                        <?php
                        $ohtFields = [
                            'oht_actie' => 'Wat heeft u eraan gedaan?',
                            'oht_hoe_effectief' => 'Hoe effectief was dat?',
                            'oht_wat_nodig' => 'Hoe kunnen wij u helpen?',
                            'oht_wat_belangrijk' => 'Wat is voor u belangrijk tijdens het verblijf op deze afdeling?',
                            'oht_reactie_op_advies' => 'Vindt u het gemakkelijk om dingen te doen of te laten op advies van de arts of verpleegkundige?'
                        ];
                        ?>

                        <?php foreach ($ohtFields as $field => $label): ?>
                            <div class="question mb-3">
                                <p><?= $label ?></p>
                                <textarea class="form-control" rows="1" name="<?= $field ?>">
                                    <?= htmlspecialchars($antwoorden[$field] ?? '') ?>
                                </textarea>
                            </div>
                        <?php endforeach; ?>

                        <!-- PREVENTIE -->
                        <div class="question mb-3">
                            <p>Wat moet u in de toekomst doen ter voorkoming van het weer ziek worden?</p>
                            <textarea class="form-control" rows="1" name="preventie">
                                <?= htmlspecialchars($antwoorden['preventie'] ?? '') ?>
                            </textarea>
                        </div>

                        <!-- OBSERVATIES -->
                        <div class="observation mt-4">
                            <h5 class="text-secondary mb-3">Verpleegkundige observaties bij dit patroon</h5>

                            <?php
                            $observaties = [
                                "Gezondheidszoekend gedrag",
                                "Tekort in gezondheidsonderhoud",
                                "(Dreigende) inadequate opvolging van de behandeling",
                                "(Dreigend) tekort in gezondheidsinstandhouding",
                                "(Dreigende) therapieontrouw",
                                "Vergiftigingsgevaar",
                                "Infectiegevaar",
                                "Gevaar voor letsel (trauma)",
                                "Verstikkingsgevaar",
                                "Beschermingstekort"
                            ];
                            ?>

                            <?php foreach ($observaties as $index => $label): ?>
                                <div class="question d-flex align-items-center mb-2">
                                    <input type="checkbox"
                                           name="observatie<?= $index+1 ?>"
                                           value="1"
                                           <?= ($boolArrayObservatie[$index] ?? "0") === "1" ? "checked" : "" ?>
                                           class="me-2">
                                    <p class="m-0"><?= htmlspecialchars($label) ?></p>
                                </div>
                            <?php endforeach; ?>
                        </div>

                    </div>
                </div>

                <div class="submit mt-4 d-flex justify-content-between">
                    <button name="navbutton" value="prev" class="btn btn-secondary">Vorige</button>
                    <button name="navbutton" value="next" class="btn btn-primary">Volgende</button>
                </div>

            </div>
        </div>

    </div>

</form>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>
