<?php
session_start();
include '../../includes/auth.php';
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
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_REQUEST['navbutton'])) {

    // 1 — Observaties omzetten naar 10-cijferige binaire string
    $observatieString = "";
    for ($i = 1; $i <= 10; $i++) {
        $observatieString .= isset($_POST["observatie$i"]) && $_POST["observatie$i"] == 1 ? "1" : "0";
    }

    $vragenlijstId = $Main->getVragenlijstId($_SESSION['clientId'], $_SESSION['loggedin_id']);

    try {
        // 4 — DATA ARRAY
        $data = [
            field('algemene_gezondheid'),
            field('gezondheids_bezigheid'),
            field('rookt') !== null && field('rookt') == 1 ? 1 : 0,
            field('rookt_hoeveelheid'),
            field('drinkt') !== null && field('drinkt') == 1 ? 1 : 0,
            field('drinkt_hoeveelheid'),
            field('besmettelijke_aandoening') !== null && field('besmettelijke_aandoening') == 1 ? 1 : 0,
            field('besmettelijke_aandoening_welke'),
            field('alergieen') !== null && field('alergieen') == 1 ? 1 : 0,
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
        if ($antwoorden) {
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
    } catch (Exception $e) {
        echo "Fout bij opslaan: " . $e->getMessage();
    }

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
    <form method="POST">
        <div class="main">
            <?php
            include '../../includes/n-header.php';
            include '../../includes/n-sidebar.php';
            ?>
            <div class="mt-5 pt-5 content">
                <div class="mt-4 mb-3 bg-white p-3" style="height: 90%; overflow: auto;">
                    <p class="card-text">
                    <div class="form-content">
                        <div class="h4 text-primary">1. Patroon van gezondheidsbeleving en -instandhouding</div>
                        <div class="form">
                            <div class="questionnaire">
                                <div class="question">
                                    <p>Hoe is uw gezondheid in het algemeen?</p><textarea rows="1" cols="25" type="text" name="algemene_gezondheid"><?= isset($antwoorden['algemene_gezondheid']) ? $antwoorden['algemene_gezondheid'] : '' ?></textarea>
                                </div>
                                <div class="question">
                                    <p>Wat doet u om gezond te blijven?</p><textarea rows="1" cols="25" type="text" name="gezondheids_bezigheid"><?= isset($antwoorden['gezondheids_bezigheid']) ? $antwoorden['gezondheids_bezigheid'] : '' ?></textarea>
                                </div>
                                <div class="question">
                                    <p>- Rookt u?</p>
                                    <div class="checkboxes">
                                        <div class="question-answer">
                                            <input id="radio" type="radio" name="rookt" <?= $antwoorden['rookt'] == 1 ? "checked" : "" ?>>
                                            <label>Ja</label>
                                            <textarea rows="1" cols="25" id="checkfield" type="text" placeholder="hoeveel?" name="rookt_hoeveelheid"><?= isset($antwoorden['rookt_hoeveelheid']) ? $antwoorden['rookt_hoeveelheid'] : '' ?></textarea>
                                        </div>
                                        <p>
                                            <input type="radio" name="rookt" <?= $antwoorden['rookt'] == 0 ? "checked" : "" ?>>
                                            <label>Nee</label>
                                        </p>
                                    </div>
                                </div>
                                <div class="question">
                                    <p>- Drinkt u?</p>
                                    <div class="checkboxes">
                                        <div class="question-answer">
                                            <input id="radio" type="radio" name="drinkt" <?= $antwoorden['drinkt'] == 1 ? "checked" : "" ?> value="1">
                                            <label>Ja</label>
                                            <textarea rows="1" cols="25" id="checkfield" type="text" placeholder="hoeveel?" name="drinkt_hoeveelheid"><?= isset($antwoorden['drinkt_hoeveelheid']) ? $antwoorden['drinkt_hoeveelheid'] : '' ?></textarea>
                                        </div>
                                        <p>
                                            <input type="radio" name="drinkt" <?= $antwoorden['drinkt'] == 0 ? "checked" : "" ?> value="0">
                                            <label>Nee</label>
                                        </p>
                                    </div>
                                </div>
                                <div class="question">
                                    <p>Heeft u momenteel een infectie of overdraagbare besmettelijke aandoening?</p>
                                    <div class="checkboxes">
                                        <div class="question-answer">
                                            <input id="radio" type="radio" name="besmettelijke_aandoening" <?= $antwoorden['besmettelijke_aandoening'] == 1 ? "checked" : "" ?> value="1">
                                            <label>Ja</label>
                                            <textarea rows="1" cols="25" id="checkfield" type="text" placeholder="en wel?" name="besmettelijke_aandoening_welke"><?= isset($antwoorden['besmettelijke_aandoening_welke']) ? $antwoorden['besmettelijke_aandoening_welke'] : '' ?></textarea>
                                        </div>
                                        <p>
                                            <input type="radio" name="besmettelijke_aandoening" <?= $antwoorden['besmettelijke_aandoening'] != 1 ? "checked" : "" ?>  value="0">
                                            <label>Nee</label>
                                        </p>
                                    </div>
                                </div>
                                <div class="question">
                                    <p>Bent u ergens allergisch voor?</p>
                                    <div class="checkboxes">
                                        <div class="question-answer">
                                            <input id="radio" type="radio" name="alergieen" <?= $antwoorden['alergieen'] == 1 ? "checked" : "" ?> value="1">
                                            <label>Ja</label>
                                            <textarea rows="1" cols="25" id="checkfield" type="text" placeholder="en wel?" name="alergieen_welke"><?= isset($antwoorden['alergieen_welke']) ? $antwoorden['alergieen_welke'] : '' ?></textarea>
                                        </div>
                                        <p>
                                            <input type="radio" name="alergieen" <?= $antwoorden['alergieen'] == 0 ? "checked" : "" ?> value="0">
                                            <label>Nee</label>
                                        </p>
                                    </div>
                                </div>
                                <div class="question">
                                    <p>Wat denkt u dat de oorzaak is van uw huidige situatie/toestand?</p><textarea rows="1" cols="25" type="text" name="oorzaak_huidige_toestand"><?= isset($antwoorden['oorzaak_huidige_toestand']) ? $antwoorden['oorzaak_huidige_toestand'] : '' ?></textarea>
                                </div>
                                <div class="question">
                                    <p>- Wat heeft u eraan gedaan?</p><textarea rows="1" cols="25" type="text" name="oht_actie"><?= isset($antwoorden['oht_actie']) ? $antwoorden['oht_actie'] : '' ?></textarea>
                                </div>
                                <div class="question">
                                    <p>- Hoe effectief was dat?</p><textarea rows="1" cols="25" type="text" name="oht_hoe_effectief"><?= isset($antwoorden['oht_hoe_effectief']) ? $antwoorden['oht_hoe_effectief'] : '' ?></textarea>
                                </div>
                                <div class="question">
                                    <p>- Hoe kunnen wij u helpen?</p><textarea rows="1" cols="25" type="text" name="oht_wat_nodig"><?= isset($antwoorden['oht_wat_nodig']) ? $antwoorden['oht_wat_nodig'] : '' ?></textarea>
                                </div>
                                <div class="question">
                                    <p>- Wat is voor u belangrijk tijdens het verblijf op deze afdeling?</p><textarea rows="1" cols="25" type="text" name="oht_wat_belangrijk"><?= isset($antwoorden['oht_wat_belangrijk']) ? $antwoorden['oht_wat_belangrijk'] : '' ?></textarea>
                                </div>
                                <div class="question">
                                    <p>- Vind u het gemakkelijk om dingen te doen of te laten op advies van de arts of verpleegkundige?</p><textarea rows="1" cols="25" type="text" name="oht_reactie_op_advies"><?= isset($antwoorden['oht_reactie_op_advies']) ? $antwoorden['oht_reactie_op_advies'] : '' ?></textarea>
                                </div>
                                <div class="question">
                                    <p>Wat moet u in de toekomst doen ter voorkoming van het weer ziek worden?</p><textarea rows="1" cols="25" type="text" name="preventie"><?= isset($antwoorden['preventie']) ? $antwoorden['preventie'] : '' ?></textarea>
                                </div>


                                <div class="observation">
                                    <h2>Verpleegkundige observatie bij dit patroon</h2>
                                    <div class="question">
                                        <div class="observe"><input type="checkbox" <?= isset($boolArrayObservatie[0]) && $boolArrayObservatie[0] == 1 ? "checked" : "" ?> value="1" name="observatie1">
                                            <p>Gezondheidszoekend gedrag</p>
                                        </div>
                                    </div>
                                    <div class="question">
                                        <div class="observe"><input type="checkbox" <?= isset($boolArrayObservatie[1]) && $boolArrayObservatie[1] == 1 ? "checked" : "" ?> value="1" name="observatie2">
                                            <p>Tekort in gezondheidsonderhoud</p>
                                        </div>
                                    </div>
                                    <div class="question">
                                        <div class="observe"><input type="checkbox" <?= isset($boolArrayObservatie[2]) && $boolArrayObservatie[2] == 1 ? "checked" : "" ?> value="1" name="observatie3">
                                            <p>(Dreigende) inadequate opvolging van de behandeling</p>
                                        </div>
                                    </div>
                                    <div class="question">
                                        <div class="observe"><input type="checkbox" <?= isset($boolArrayObservatie[3]) && $boolArrayObservatie[3] == 1 ? "checked" : "" ?> value="1" name="observatie4">
                                            <p>(Dreigend) tekort in gezondheidsinstandhouding</p>
                                        </div>
                                    </div>
                                    <div class="question">
                                        <div class="observe"><input type="checkbox" <?= isset($boolArrayObservatie[4]) && $boolArrayObservatie[4] == 1 ? "checked" : "" ?> value="1" name="observatie5">
                                            <p>(Dreigende) therapieontrouw</p>
                                        </div>
                                    </div>
                                    <div class="question">
                                        <div class="observe"><input type="checkbox" <?= isset($boolArrayObservatie[5]) && $boolArrayObservatie[5] == 1 ? "checked" : "" ?> value="1" name="observatie6">
                                            <p>Vergiftigingsgevaar</p>
                                        </div>
                                    </div>
                                    <div class="question">
                                        <div class="observe"><input type="checkbox" <?= isset($boolArrayObservatie[6]) && $boolArrayObservatie[6] == 1 ? "checked" : "" ?> value="1" name="observatie7">
                                            <p>Infectiegevaar</p>
                                        </div>
                                    </div>
                                    <div class="question">
                                        <div class="observe"><input type="checkbox" <?= isset($boolArrayObservatie[7]) && $boolArrayObservatie[7] == 1 ? "checked" : "" ?> value="1" name="observatie8">
                                            <p>Gevaar voor letsel (trauma)</p>
                                        </div>
                                    </div>
                                    <div class="question">
                                        <div class="observe"><input type="checkbox" <?= isset($boolArrayObservatie[8]) && $boolArrayObservatie[8] == 1 ? "checked" : "" ?> value="1" name="observatie9">
                                            <p>Verstikkingsgevaar</p>
                                        </div>
                                    </div>
                                    <div class="question">
                                        <div class="observe"><input type="checkbox" <?= isset($boolArrayObservatie[9]) && $boolArrayObservatie[9] == 1 ? "checked" : "" ?> value="1" name="observatie10">
                                            <p>Beschermingstekort</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="submit">
                            <button name="navbutton" class="btn btn-secondary" type="submit" value="prev">Vorige</button>
                            <button name="navbutton" class="btn btn-secondary" type="submit" value="next">Volgende</button>
                        </div>
                    </div>
                </div>
            </div>
    </form>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>
