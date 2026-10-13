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
        if (!empty($antwoorden['vragenlijstid'])) {
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
                "ssisssssissssssssss",
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

require_once __DIR__ . '/../../models/PatternFormHelper.php';
$form = new PatternFormHelper();

$observatieLijst = [
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

$content = '';
$content .= $form->textarea("Hoe is uw gezondheid in het algemeen?", "algemene_gezondheid", $antwoorden['algemene_gezondheid'] ?? null);
$content .= $form->textarea("Wat doet u om gezond te blijven?", "gezondheids_bezigheid", $antwoorden['gezondheids_bezigheid'] ?? null);
$content .= $form->yesNoWithText("- Rookt u?", "rookt", "rookt_hoeveelheid", $antwoorden['rookt'] ?? null, $antwoorden['rookt_hoeveelheid'] ?? null, "hoeveel?");
$content .= $form->yesNoWithText("- Drinkt u?", "drinkt", "drinkt_hoeveelheid", $antwoorden['drinkt'] ?? null, $antwoorden['drinkt_hoeveelheid'] ?? null, "hoeveel?");
$content .= $form->yesNoWithText("Heeft u momenteel een infectie of overdraagbare besmettelijke aandoening?", "besmettelijke_aandoening", "besmettelijke_aandoening_welke", $antwoorden['besmettelijke_aandoening'] ?? null, $antwoorden['besmettelijke_aandoening_welke'] ?? null, "en wel?");
$content .= $form->yesNoWithText("Bent u ergens allergisch voor?", "alergieen", "alergieen_welke", $antwoorden['alergieen'] ?? null, $antwoorden['alergieen_welke'] ?? null, "en wel?");
$content .= $form->textarea("Wat denkt u dat de oorzaak is van uw huidige situatie/toestand?", "oorzaak_huidige_toestand", $antwoorden['oorzaak_huidige_toestand'] ?? null);
$content .= $form->textarea("- Wat heeft u eraan gedaan?", "oht_actie", $antwoorden['oht_actie'] ?? null);
$content .= $form->textarea("- Hoe effectief was dat?", "oht_hoe_effectief", $antwoorden['oht_hoe_effectief'] ?? null);
$content .= $form->textarea("- Hoe kunnen wij u helpen?", "oht_wat_nodig", $antwoorden['oht_wat_nodig'] ?? null);
$content .= $form->textarea("- Wat is voor u belangrijk tijdens het verblijf op deze afdeling?", "oht_wat_belangrijk", $antwoorden['oht_wat_belangrijk'] ?? null);
$content .= $form->textarea("- Vind u het gemakkelijk om dingen te doen of te laten op advies van de arts of verpleegkundige?", "oht_reactie_op_advies", $antwoorden['oht_reactie_op_advies'] ?? null);
$content .= $form->textarea("Wat moet u in de toekomst doen ter voorkoming van het weer ziek worden?", "preventie", $antwoorden['preventie'] ?? null);
$content .= $form->observationSection($observatieLijst, $antwoorden['observatie'] ?? '');

echo $form->renderLayout(
    "Anamnese – Patroon 1",
    "1. Patroon van gezondheidsbeleving en -instandhouding",
    $content,
    "patroon11.php",
    "patroon02.php"
);
