<?php
session_start();
include '../../includes/auth.php';
require '../../database/DatabaseConnection.php';
require_once '../../models/autoload.php';

$Main = new Main();
$db = DatabaseConnection::getConn();

$antwoorden = $Main->getPatternAnswers($_SESSION['clientId'], 6);
$boolArrayObservatie = isset($antwoorden['observatie'])
    ? str_split($antwoorden['observatie'])
    : array_fill(0, 12, 0);

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['navbutton'])) {

    $moeilijk_horen = intval($_POST['moeilijk_horen'] ?? 0);
    $hoort_stemmen = intval($_POST['hoort_stemmen'] ?? 0);
    $hoort_stemmen_wat = trim($_POST['hoort_stemmen_wat'] ?? "");
    $moeite_met_zien = intval($_POST['moeite_met_zien'] ?? 0);
    $ziet_dingen = intval($_POST['ziet_dingen'] ?? 0);
    $ziet_dingen_wat = trim($_POST['ziet_dingen_wat'] ?? "");
    $ruikt_iets_onverklaarbaar = intval($_POST['ruikt_iets_onverklaarbaar'] ?? 0);
    $ruikt_iets_onverklaarbaar_wat = trim($_POST['ruikt_iets_onverklaarbaar_wat'] ?? "");
    $verandering_denken = intval($_POST['verandering_denken'] ?? 0);
    $moeite_spreken = intval($_POST['moeite_spreken'] ?? 0);
    $taal_thuis = trim($_POST['taal_thuis'] ?? "");
    $verandering_concentratievermogen = intval($_POST['verandering_concentratievermogen'] ?? 0);
    $moeilijker_beslissen = intval($_POST['moeilijker_beslissen'] ?? 0);
    $verandering_geheugen = intval($_POST['verandering_geheugen'] ?? 0);
    $verandering_orientatie = intval($_POST['verandering_orientatie'] ?? 0);
    $invloed_medicatie = intval($_POST['invloed_medicatie'] ?? 0);
    $invloed_medicatie_welke = trim($_POST['invloed_medicatie_welke'] ?? "");
    $gebruikt_middelen = intval($_POST['gebruikt_middelen'] ?? 0);
    $gebruikt_middelen_softdrugs = intval($_POST['gebruikt_middelen_softdrugs'] ?? 0);
    $gebruikt_middelen_softdrugs_welke = trim($_POST['gebruikt_middelen_softdrugs_welke'] ?? "");
    $gebruikt_middelen_harddrugs = intval($_POST['gebruikt_middelen_harddrugs'] ?? 0);
    $gebruikt_middelen_harddrugs_welke = trim($_POST['gebruikt_middelen_harddrugs_welke'] ?? "");
    $gebruikt_middelen_alcohol = intval($_POST['gebruikt_middelen_alcohol'] ?? 0);
    $gebruikt_middelen_alcohol_welke = trim($_POST['gebruikt_middelen_alcohol_welke'] ?? "");
    $gebruikt_middelen_anders = intval($_POST['gebruikt_middelen_anders'] ?? 0);
    $gebruikt_middelen_anders_welke = trim($_POST['gebruikt_middelen_anders_welke'] ?? "");
    $pijnklachten = intval($_POST['pijnklachten'] ?? 0);
    $pijnklachten_waar_wanneer_soort = trim($_POST['pijnklachten_waar_wanneer_soort'] ?? "");
    $pijnklachten_tegengaan_pijn = trim($_POST['pijnklachten_tegengaan_pijn'] ?? "");
    $pijnklachten_preventie = trim($_POST['pijnklachten_preventie'] ?? "");

    $observatieArray = [];
    for ($i = 1; $i <= 12; $i++) {
        $observatieArray[] = isset($_POST["observatie$i"]) && $_POST["observatie$i"] == 1 ? "1" : "0";
    }
    $observatie = implode("", $observatieArray);

    $medewerkerId = $_SESSION['loggedin_id'];

    try {
        $stmt = $db->prepare("
            SELECT vl.id 
            FROM vragenlijst vl 
            JOIN verzorgerregel vr ON vr.id = vl.verzorgerregelid
            WHERE vr.clientid = ?
        ");
        $stmt->bind_param("i", $_SESSION['clientId']);
        $stmt->execute();
        $row = $stmt->get_result()->fetch_assoc();

        $vragenlijstId = $Main->getVragenlijstId($_SESSION['clientId'], $_SESSION['loggedin_id']);

        if (!empty($antwoorden['vragenlijstid'])) {
            $stmt = $db->prepare("
                UPDATE patroon06cognitiewaarneming
                SET 
                    moeilijk_horen=?,
                    hoort_stemmen=?,
                    hoort_stemmen_wat=?,
                    moeite_met_zien=?,
                    ziet_dingen=?,
                    ziet_dingen_wat=?,
                    ruikt_iets_onverklaarbaar=?,
                    ruikt_iets_onverklaarbaar_wat=?,
                    verandering_denken=?,
                    moeite_spreken=?,
                    taal_thuis=?,
                    verandering_concentratievermogen=?,
                    moeilijker_beslissen=?,
                    verandering_geheugen=?,
                    verandering_orientatie=?,
                    invloed_medicatie=?,
                    invloed_medicatie_welke=?,
                    gebruikt_middelen=?,
                    gebruikt_middelen_softdrugs=?,
                    gebruikt_middelen_softdrugs_welke=?,
                    gebruikt_middelen_harddrugs=?,
                    gebruikt_middelen_harddrugs_welke=?,
                    gebruikt_middelen_alcohol=?,
                    gebruikt_middelen_alcohol_welke=?,
                    gebruikt_middelen_anders=?,
                    gebruikt_middelen_anders_welke=?,
                    pijnklachten=?,
                    pijnklachten_waar_wanneer_soort=?,
                    pijnklachten_tegengaan_pijn=?,
                    pijnklachten_preventie=?,
                    observatie=?
                WHERE vragenlijstid=?
            ");
            $types = str_repeat("i", 18) . str_repeat("s", 13) . "i";
            $stmt->bind_param(
                $types,
                $moeilijk_horen,
                $hoort_stemmen,
                $hoort_stemmen_wat,
                $moeite_met_zien,
                $ziet_dingen,
                $ziet_dingen_wat,
                $ruikt_iets_onverklaarbaar,
                $ruikt_iets_onverklaarbaar_wat,
                $verandering_denken,
                $moeite_spreken,
                $taal_thuis,
                $verandering_concentratievermogen,
                $moeilijker_beslissen,
                $verandering_geheugen,
                $verandering_orientatie,
                $invloed_medicatie,
                $invloed_medicatie_welke,
                $gebruikt_middelen,
                $gebruikt_middelen_softdrugs,
                $gebruikt_middelen_softdrugs_welke,
                $gebruikt_middelen_harddrugs,
                $gebruikt_middelen_harddrugs_welke,
                $gebruikt_middelen_alcohol,
                $gebruikt_middelen_alcohol_welke,
                $gebruikt_middelen_anders,
                $gebruikt_middelen_anders_welke,
                $pijnklachten,
                $pijnklachten_waar_wanneer_soort,
                $pijnklachten_tegengaan_pijn,
                $pijnklachten_preventie,
                $observatie,
                $vragenlijstId
            );
        } else {
            $stmt = $db->prepare("
                INSERT INTO patroon06cognitiewaarneming (
                    vragenlijstid,
                    moeilijk_horen,
                    hoort_stemmen,
                    hoort_stemmen_wat,
                    moeite_met_zien,
                    ziet_dingen,
                    ziet_dingen_wat,
                    ruikt_iets_onverklaarbaar,
                    ruikt_iets_onverklaarbaar_wat,
                    verandering_denken,
                    moeite_spreken,
                    taal_thuis,
                    verandering_concentratievermogen,
                    moeilijker_beslissen,
                    verandering_geheugen,
                    verandering_orientatie,
                    invloed_medicatie,
                    invloed_medicatie_welke,
                    gebruikt_middelen,
                    gebruikt_middelen_softdrugs,
                    gebruikt_middelen_softdrugs_welke,
                    gebruikt_middelen_harddrugs,
                    gebruikt_middelen_harddrugs_welke,
                    gebruikt_middelen_alcohol,
                    gebruikt_middelen_alcohol_welke,
                    gebruikt_middelen_anders,
                    gebruikt_middelen_anders_welke,
                    pijnklachten,
                    pijnklachten_waar_wanneer_soort,
                    pijnklachten_tegengaan_pijn,
                    pijnklachten_preventie,
                    observatie
                ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
            ");
            $types = "i" . str_repeat("i", 18) . str_repeat("s", 13);
            $stmt->bind_param(
                $types,
                $vragenlijstId,
                $moeilijk_horen,
                $hoort_stemmen,
                $hoort_stemmen_wat,
                $moeite_met_zien,
                $ziet_dingen,
                $ziet_dingen_wat,
                $ruikt_iets_onverklaarbaar,
                $ruikt_iets_onverklaarbaar_wat,
                $verandering_denken,
                $moeite_spreken,
                $taal_thuis,
                $verandering_concentratievermogen,
                $moeilijker_beslissen,
                $verandering_geheugen,
                $verandering_orientatie,
                $invloed_medicatie,
                $invloed_medicatie_welke,
                $gebruikt_middelen,
                $gebruikt_middelen_softdrugs,
                $gebruikt_middelen_softdrugs_welke,
                $gebruikt_middelen_harddrugs,
                $gebruikt_middelen_harddrugs_welke,
                $gebruikt_middelen_alcohol,
                $gebruikt_middelen_alcohol_welke,
                $gebruikt_middelen_anders,
                $gebruikt_middelen_anders_welke,
                $pijnklachten,
                $pijnklachten_waar_wanneer_soort,
                $pijnklachten_tegengaan_pijn,
                $pijnklachten_preventie,
                $observatie
            );
        }

        $stmt->execute();

        if ($_POST['navbutton'] === "next") {
            header("Location: patroon07.php");
        } else {
            header("Location: patroon05.php");
        }
        exit;
    } catch (Exception $e) {
        echo "Fout bij opslaan: " . $e->getMessage();
    }
}

function e($v)
{
    return htmlspecialchars($v ?? "", ENT_QUOTES, "UTF-8");
}
?>
<!DOCTYPE html>
<html lang="nl">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Anamnese — Cognitie- en waarnemingspatroon</title>
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
                    <div class="form-content">
                        <div class="h4 text-primary">6. Cognitie- en waarnemingspatroon</div>
                        <div class="form">
                            <div class="questionnaire">
                                <div class="question">
                                    <p>Heeft u moeite met horen?</p>
                                    <div class="checkboxes">
                                        <p>
                                            <input type="radio" value="1" name="moeilijk_horen"
                                                <?= isset($antwoorden['moeilijk_horen']) && $antwoorden['moeilijk_horen'] == 1 ? "checked" : "" ?>>
                                            <label>Ja</label>
                                        </p>
                                        <p>
                                            <input type="radio" value="0" name="moeilijk_horen"
                                                <?= isset($antwoorden['moeilijk_horen']) && $antwoorden['moeilijk_horen'] == 0 ? "checked" : "" ?>>
                                            <label>Nee</label>
                                        </p>
                                    </div>
                                </div>

                                <div class="question">
                                    <p>- Hoort u stemmen die op dat moment door personen in uw omgeving niet gehoord
                                        (kunnen) worden?</p>
                                    <div class="checkboxes">
                                        <div class="question-answer">
                                            <input type="radio" value="1" name="hoort_stemmen"
                                                <?= isset($antwoorden['hoort_stemmen']) && $antwoorden['hoort_stemmen'] == 1 ? "checked" : "" ?>>
                                            <label>Ja</label>
                                            <textarea rows="1" cols="25" id="checkfield" name="hoort_stemmen_wat"
                                                placeholder="wat hoort u?"><?= e($antwoorden['hoort_stemmen_wat'] ?? "") ?></textarea>
                                        </div>
                                        <p>
                                            <input type="radio" value="0" name="hoort_stemmen"
                                                <?= isset($antwoorden['hoort_stemmen']) && $antwoorden['hoort_stemmen'] == 0 ? "checked" : "" ?>>
                                            <label>Nee</label>
                                        </p>
                                    </div>
                                </div>

                                <div class="question">
                                    <p>Heeft u moeite met zien?</p>
                                    <div class="checkboxes">
                                        <p>
                                            <input type="radio" value="1" name="moeite_met_zien"
                                                <?= isset($antwoorden['moeite_met_zien']) && $antwoorden['moeite_met_zien'] == 1 ? "checked" : "" ?>>
                                            <label>Ja</label>
                                        </p>
                                        <p>
                                            <input type="radio" value="0" name="moeite_met_zien"
                                                <?= isset($antwoorden['moeite_met_zien']) && $antwoorden['moeite_met_zien'] == 0 ? "checked" : "" ?>>
                                            <label>Nee</label>
                                        </p>
                                    </div>
                                </div>

                                <div class="question">
                                    <p>- Ziet u personen, dieren, objecten die op dat moment door personen in uw
                                        omgeving niet gezien (kunnen) worden?</p>
                                    <div class="checkboxes">
                                        <div class="question-answer">
                                            <input type="radio" value="1" name="ziet_dingen"
                                                <?= isset($antwoorden['ziet_dingen']) && $antwoorden['ziet_dingen'] == 1 ? "checked" : "" ?>>
                                            <label>Ja</label>
                                            <textarea rows="1" cols="25" id="checkfield" name="ziet_dingen_wat"
                                                placeholder="wat ziet u?"><?= e($antwoorden['ziet_dingen_wat'] ?? "") ?></textarea>
                                        </div>
                                        <p>
                                            <input type="radio" value="0" name="ziet_dingen"
                                                <?= isset($antwoorden['ziet_dingen']) && $antwoorden['ziet_dingen'] == 0 ? "checked" : "" ?>>
                                            <label>Nee</label>
                                        </p>
                                    </div>
                                </div>

                                <div class="question">
                                    <p>Ruikt u iets dat op dat moment door personen in uw omgeving niet geroken (kan)
                                        worden?</p>
                                    <div class="checkboxes">
                                        <div class="question-answer">
                                            <input type="radio" value="1" name="ruikt_iets_onverklaarbaar"
                                                <?= isset($antwoorden['ruikt_iets_onverklaarbaar']) && $antwoorden['ruikt_iets_onverklaarbaar'] == 1 ? "checked" : "" ?>>
                                            <label>Ja</label>
                                            <textarea rows="1" cols="25" id="checkfield"
                                                name="ruikt_iets_onverklaarbaar_wat"
                                                placeholder="wat ruikt u?"><?= e($antwoorden['ruikt_iets_onverklaarbaar_wat'] ?? "") ?></textarea>
                                        </div>
                                        <p>
                                            <input type="radio" value="0" name="ruikt_iets_onverklaarbaar"
                                                <?= isset($antwoorden['ruikt_iets_onverklaarbaar']) && $antwoorden['ruikt_iets_onverklaarbaar'] == 0 ? "checked" : "" ?>>
                                            <label>Nee</label>
                                        </p>
                                    </div>
                                </div>

                                <div class="question">
                                    <p>Zijn er de afgelopen tijd veranderingen opgetreden in uw denken?</p>
                                    <div class="checkboxes">
                                        <p>
                                            <input type="radio" value="1" name="verandering_denken"
                                                <?= isset($antwoorden['verandering_denken']) && $antwoorden['verandering_denken'] == 1 ? "checked" : "" ?>>
                                            <label>Ja</label>
                                        </p>
                                        <p>
                                            <input type="radio" value="0" name="verandering_denken"
                                                <?= isset($antwoorden['verandering_denken']) && $antwoorden['verandering_denken'] == 0 ? "checked" : "" ?>>
                                            <label>Nee</label>
                                        </p>
                                    </div>
                                </div>

                                <div class="question">
                                    <p>Heeft u moeite met spreken?</p>
                                    <div class="checkboxes">
                                        <p>
                                            <input type="radio" value="1" name="moeite_spreken"
                                                <?= isset($antwoorden['moeite_spreken']) && $antwoorden['moeite_spreken'] == 1 ? "checked" : "" ?>>
                                            <label>Ja</label>
                                        </p>
                                        <p>
                                            <input type="radio" value="0" name="moeite_spreken"
                                                <?= isset($antwoorden['moeite_spreken']) && $antwoorden['moeite_spreken'] == 0 ? "checked" : "" ?>>
                                            <label>Nee</label>
                                        </p>
                                    </div>
                                </div>

                                <div class="question">
                                    <p>- Welke taal spreekt u thuis?</p>
                                    <textarea rows="1" cols="25"
                                        name="taal_thuis"><?= e($antwoorden['taal_thuis'] ?? "") ?></textarea>
                                </div>

                                <div class="question">
                                    <p>Zijn er de afgelopen tijd veranderingen opgetreden in uw concentratievermogen?
                                    </p>
                                    <div class="checkboxes">
                                        <p>
                                            <input type="radio" value="1" name="verandering_concentratievermogen"
                                                <?= isset($antwoorden['verandering_concentratievermogen']) && $antwoorden['verandering_concentratievermogen'] == 1 ? "checked" : "" ?>>
                                            <label>Ja</label>
                                        </p>
                                        <p>
                                            <input type="radio" value="0" name="verandering_concentratievermogen"
                                                <?= isset($antwoorden['verandering_concentratievermogen']) && $antwoorden['verandering_concentratievermogen'] == 0 ? "checked" : "" ?>>
                                            <label>Nee</label>
                                        </p>
                                    </div>
                                </div>

                                <div class="question">
                                    <p>Kunt u moeilijker dagelijkse beslissingen nemen?</p>
                                    <div class="checkboxes">
                                        <p>
                                            <input type="radio" value="1" name="moeilijker_beslissen"
                                                <?= isset($antwoorden['moeilijker_beslissen']) && $antwoorden['moeilijker_beslissen'] == 1 ? "checked" : "" ?>>
                                            <label>Ja</label>
                                        </p>
                                        <p>
                                            <input type="radio" value="0" name="moeilijker_beslissen"
                                                <?= isset($antwoorden['moeilijker_beslissen']) && $antwoorden['moeilijker_beslissen'] == 0 ? "checked" : "" ?>>
                                            <label>Nee</label>
                                        </p>
                                    </div>
                                </div>

                                <div class="question">
                                    <p>Zijn er de afgelopen tijd veranderingen opgetreden in uw geheugen?</p>
                                    <div class="checkboxes">
                                        <p>
                                            <input type="radio" value="1" name="verandering_geheugen"
                                                <?= isset($antwoorden['verandering_geheugen']) && $antwoorden['verandering_geheugen'] == 1 ? "checked" : "" ?>>
                                            <label>Ja</label>
                                        </p>
                                        <p>
                                            <input type="radio" value="0" name="verandering_geheugen"
                                                <?= isset($antwoorden['verandering_geheugen']) && $antwoorden['verandering_geheugen'] == 0 ? "checked" : "" ?>>
                                            <label>Nee</label>
                                        </p>
                                    </div>
                                </div>

                                <div class="question">
                                    <p>Zijn er de afgelopen tijd veranderingen opgetreden in uw oriëntatie?</p>
                                    <div class="checkboxes">
                                        <p>
                                            <input type="radio" value="1" name="verandering_orientatie"
                                                <?= isset($antwoorden['verandering_orientatie']) && $antwoorden['verandering_orientatie'] == 1 ? "checked" : "" ?>>
                                            <label>Ja</label>
                                        </p>
                                        <p>
                                            <input type="radio" value="0" name="verandering_orientatie"
                                                <?= isset($antwoorden['verandering_orientatie']) && $antwoorden['verandering_orientatie'] == 0 ? "checked" : "" ?>>
                                            <label>Nee</label>
                                        </p>
                                    </div>
                                </div>

                                <div class="question">
                                    <p>Gebruikt u medicatie die uw oriëntatie, reactievermogen of denken beïnvloeden?
                                    </p>
                                    <div class="checkboxes">
                                        <div class="question-answer">
                                            <input type="radio" value="1" name="invloed_medicatie"
                                                <?= isset($antwoorden['invloed_medicatie']) && $antwoorden['invloed_medicatie'] == 1 ? "checked" : "" ?>>
                                            <label>Ja</label>
                                            <textarea rows="1" cols="25" id="checkfield" name="invloed_medicatie_welke"
                                                placeholder="welke?"><?= e($antwoorden['invloed_medicatie_welke'] ?? "") ?></textarea>
                                        </div>
                                        <p>
                                            <input type="radio" value="0" name="invloed_medicatie"
                                                <?= isset($antwoorden['invloed_medicatie']) && $antwoorden['invloed_medicatie'] == 0 ? "checked" : "" ?>>
                                            <label>Nee</label>
                                        </p>
                                    </div>
                                </div>

                                <div class="question">
                                    <p>Gebruikt u verdovende/stimulerende middelen?</p>
                                    <div class="checkboxes">
                                        <div class="question-answer">
                                            <input type="radio" value="1" name="gebruikt_middelen"
                                                <?= isset($antwoorden['gebruikt_middelen']) && $antwoorden['gebruikt_middelen'] == 1 ? "checked" : "" ?>>
                                            <label>Ja</label>
                                            <div id="checkfield">
                                                <div class="question">
                                                    <div class="observe">
                                                        <input type="checkbox" value="1"
                                                            name="gebruikt_middelen_softdrugs"
                                                            <?= isset($antwoorden['gebruikt_middelen_softdrugs']) && $antwoorden['gebruikt_middelen_softdrugs'] == 1 ? "checked" : "" ?>>
                                                        <p>Softdrugs</p>
                                                    </div>
                                                    <textarea rows="1" cols="25"
                                                        name="gebruikt_middelen_softdrugs_welke"><?= e($antwoorden['gebruikt_middelen_softdrugs_welke'] ?? "") ?></textarea>
                                                </div>
                                                <div class="question">
                                                    <div class="observe">
                                                        <input type="checkbox" value="1"
                                                            name="gebruikt_middelen_harddrugs"
                                                            <?= isset($antwoorden['gebruikt_middelen_harddrugs']) && $antwoorden['gebruikt_middelen_harddrugs'] == 1 ? "checked" : "" ?>>
                                                        <p>Harddrugs</p>
                                                    </div>
                                                    <textarea rows="1" cols="25"
                                                        name="gebruikt_middelen_harddrugs_welke"><?= e($antwoorden['gebruikt_middelen_harddrugs_welke'] ?? "") ?></textarea>
                                                </div>
                                                <div class="question">
                                                    <div class="observe">
                                                        <input type="checkbox" value="1"
                                                            name="gebruikt_middelen_alcohol"
                                                            <?= isset($antwoorden['gebruikt_middelen_alcohol']) && $antwoorden['gebruikt_middelen_alcohol'] == 1 ? "checked" : "" ?>>
                                                        <p>Alcohol</p>
                                                    </div>
                                                    <textarea rows="1" cols="25"
                                                        name="gebruikt_middelen_alcohol_welke"><?= e($antwoorden['gebruikt_middelen_alcohol_welke'] ?? "") ?></textarea>
                                                </div>
                                                <div class="question">
                                                    <div class="observe">
                                                        <input type="checkbox" value="1" name="gebruikt_middelen_anders"
                                                            <?= isset($antwoorden['gebruikt_middelen_anders']) && $antwoorden['gebruikt_middelen_anders'] == 1 ? "checked" : "" ?>>
                                                        <p>Anders, namelijk:</p>
                                                    </div>
                                                    <textarea rows="1" cols="25"
                                                        name="gebruikt_middelen_anders_welke"><?= e($antwoorden['gebruikt_middelen_anders_welke'] ?? "") ?></textarea>
                                                </div>
                                            </div>
                                        </div>
                                        <p>
                                            <input type="radio" value="0" name="gebruikt_middelen"
                                                <?= isset($antwoorden['gebruikt_middelen']) && $antwoorden['gebruikt_middelen'] == 0 ? "checked" : "" ?>>
                                            <label>Nee</label>
                                        </p>
                                    </div>
                                </div>

                                <div class="question">
                                    <p>Heeft u pijnklachten?</p>
                                    <div class="checkboxes">
                                        <p>
                                            <input type="radio" value="1" name="pijnklachten"
                                                <?= isset($antwoorden['pijnklachten']) && $antwoorden['pijnklachten'] == 1 ? "checked" : "" ?>>
                                            <label>Ja</label>
                                        </p>
                                        <p>
                                            <input type="radio" value="0" name="pijnklachten"
                                                <?= isset($antwoorden['pijnklachten']) && $antwoorden['pijnklachten'] == 0 ? "checked" : "" ?>>
                                            <label>Nee</label>
                                        </p>
                                    </div>
                                </div>

                                <div class="question">
                                    <p>- Waar, wanneer, soort pijn?</p>
                                    <textarea rows="1" cols="25"
                                        name="pijnklachten_waar_wanneer_soort"><?= e($antwoorden['pijnklachten_waar_wanneer_soort'] ?? "") ?></textarea>
                                </div>

                                <div class="question">
                                    <p>- Wat doet u doorgaans tegen de pijn?</p>
                                    <textarea rows="1" cols="25"
                                        name="pijnklachten_tegengaan_pijn"><?= e($antwoorden['pijnklachten_tegengaan_pijn'] ?? "") ?></textarea>
                                </div>

                                <div class="question">
                                    <p>- Wat doet u om pijn/ongemak zoveel mogelijk te voorkomen?</p>
                                    <textarea rows="1" cols="25"
                                        name="pijnklachten_preventie"><?= e($antwoorden['pijnklachten_preventie'] ?? "") ?></textarea>
                                </div>

                                <div class="observation">
                                    <h2>Verpleegkundige observatie bij dit patroon</h2>
                                    <div class="question">
                                        <div class="observe"><input type="checkbox" value="1" name="observatie1"
                                                <?= isset($boolArrayObservatie[0]) && $boolArrayObservatie[0] == 1 ? "checked" : "" ?>>
                                            <p>Wijziging in de waarneming</p>
                                        </div>
                                    </div>
                                    <div class="question">
                                        <div class="observe"><input type="checkbox" value="1" name="observatie2"
                                                <?= isset($boolArrayObservatie[1]) && $boolArrayObservatie[1] == 1 ? "checked" : "" ?>>
                                            <p>Verstoord denken</p>
                                        </div>
                                    </div>
                                    <div class="question">
                                        <div class="observe"><input type="checkbox" value="1" name="observatie3"
                                                <?= isset($boolArrayObservatie[2]) && $boolArrayObservatie[2] == 1 ? "checked" : "" ?>>
                                            <p>Kennistekort</p>
                                        </div>
                                    </div>
                                    <div class="question">
                                        <div class="observe"><input type="checkbox" value="1" name="observatie4"
                                                <?= isset($boolArrayObservatie[3]) && $boolArrayObservatie[3] == 1 ? "checked" : "" ?>>
                                            <p>Dreigend cognitietekort</p>
                                        </div>
                                    </div>
                                    <div class="question">
                                        <div class="observe"><input type="checkbox" value="1" name="observatie5"
                                                <?= isset($boolArrayObservatie[4]) && $boolArrayObservatie[4] == 1 ? "checked" : "" ?>>
                                            <p>Beslisconflict</p>
                                        </div>
                                    </div>
                                    <div class="question">
                                        <div class="observe"><input type="checkbox" value="1" name="observatie6"
                                                <?= isset($boolArrayObservatie[5]) && $boolArrayObservatie[5] == 1 ? "checked" : "" ?>>
                                            <p>Achterdocht</p>
                                        </div>
                                    </div>
                                    <div class="question">
                                        <div class="observe"><input type="checkbox" value="1" name="observatie7"
                                                <?= isset($boolArrayObservatie[6]) && $boolArrayObservatie[6] == 1 ? "checked" : "" ?>>
                                            <p>Acute verwardheid</p>
                                        </div>
                                    </div>
                                    <div class="question">
                                        <div class="observe"><input type="checkbox" value="1" name="observatie8"
                                                <?= isset($boolArrayObservatie[7]) && $boolArrayObservatie[7] == 1 ? "checked" : "" ?>>
                                            <p>Pijn (specificeer type en locatie)</p>
                                        </div>
                                    </div>
                                    <div class="question">
                                        <div class="observe"><input type="checkbox" value="1" name="observatie9"
                                                <?= isset($boolArrayObservatie[8]) && $boolArrayObservatie[8] == 1 ? "checked" : "" ?>>
                                            <p>Chronische pijn (specificeer type en locatie)</p>
                                        </div>
                                    </div>
                                    <div class="question">
                                        <div class="observe"><input type="checkbox" value="1" name="observatie10"
                                                <?= isset($boolArrayObservatie[9]) && $boolArrayObservatie[9] == 1 ? "checked" : "" ?>>
                                            <p>Middelenmisbruik:</p>
                                        </div>
                                    </div>
                                    <div class="question">
                                        <div class="observe"><input type="checkbox" value="1" name="observatie11"
                                                <?= isset($boolArrayObservatie[10]) && $boolArrayObservatie[10] == 1 ? "checked" : "" ?>>
                                            <p>Alcohol</p>
                                        </div>
                                    </div>
                                    <div class="question">
                                        <div class="observe"><input type="checkbox" value="1" name="observatie12"
                                                <?= isset($boolArrayObservatie[11]) && $boolArrayObservatie[11] == 1 ? "checked" : "" ?>>
                                            <p>Drugs</p>
                                        </div>
                                    </div>
                                </div>

                            </div>
                        </div>
                        <div class="submit">
                            <button name="navbutton" class="btn btn-secondary" type="submit"
                                value="prev">Vorige</button>
                            <button name="navbutton" class="btn btn-secondary" type="submit"
                                value="next">Volgende</button>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </form>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>