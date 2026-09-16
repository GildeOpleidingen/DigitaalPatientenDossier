
<?php
session_start();
include '../../includes/auth.php';
include '../../database/DatabaseConnection.php';
include_once '../../models/autoload.php';

$Main = new Main();
$clientId = $_SESSION['clientId'];
 $patternId = 8; // Dit is patroon 8, pas aan per pagina

// Haal eerder opgeslagen antwoorden op zodat het formulier correct voorgevuld wordt
$antwoorden = $Main->getPatternAnswers($_SESSION['clientId'], $patternId);
// observatie array ophalen en splitsen zoals in patroon11
$boolArrayObservatie = isset($antwoorden['observatie']) && $antwoorden['observatie'] !== null ? str_split($antwoorden['observatie']) : array_fill(0, 17, '0');

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_REQUEST['navbutton'])) {
    // Lees ingevulde gegevens en sla op in eigen patroon08 tabel (zelfde aanpak als patroon11)
    $getrouwd_samenwonend = isset($_POST['getrouwd_samenwonend']) ? $_POST['getrouwd_samenwonend'] : 0;
    $kinderen = isset($_POST['kinderen']) ? $_POST['kinderen'] : 0;
    $tevreden_thuissituatie = isset($_POST['tevreden_thuissituatie']) ? $_POST['tevreden_thuissituatie'] : 0;
    $steun_vrienden_familie = isset($_POST['steun_vrienden_familie']) ? $_POST['steun_vrienden_familie'] : 0;
    $inkomstenbron = isset($_POST['inkomstenbron']) ? $_POST['inkomstenbron'] : null;
    $verandering_fin_sit_vroeger = isset($_POST['verandering_fin_sit_vroeger']) ? $_POST['verandering_fin_sit_vroeger'] : 0;
    $verandering_fin_sit_vroeger_welke = isset($_POST['verandering_fin_sit_vroeger_welke']) ? $_POST['verandering_fin_sit_vroeger_welke'] : null;
    $verandering_fin_sit_toekomst = isset($_POST['verandering_fin_sit_toekomst']) ? $_POST['verandering_fin_sit_toekomst'] : 0;
    $verandering_fin_sit_toekomst_welke = isset($_POST['verandering_fin_sit_toekomst_welke']) ? $_POST['verandering_fin_sit_toekomst_welke'] : null;
    $opleiding = isset($_POST['opleiding']) ? $_POST['opleiding'] : null;
    $verandering_sociale_contacten = isset($_POST['verandering_sociale_contacten']) ? $_POST['verandering_sociale_contacten'] : 0;
    $verandering_sociale_contacten_welke = isset($_POST['verandering_sociale_contacten_welke']) ? $_POST['verandering_sociale_contacten_welke'] : null;
    $groot_gezin = isset($_POST['groot_gezin']) ? $_POST['groot_gezin'] : 0;
    $plaats_in_gezin = isset($_POST['plaats_in_gezin']) ? $_POST['plaats_in_gezin'] : null;
    $onderlinge_contacten_gezin = isset($_POST['onderlinge_contacten_gezin']) ? $_POST['onderlinge_contacten_gezin'] : null;
    $agressie_gezin = isset($_POST['agressie_gezin']) ? $_POST['agressie_gezin'] : 0;
    $verenigingslid = isset($_POST['verenigingslid']) ? $_POST['verenigingslid'] : 0;
    $vereniging_welke = isset($_POST['vereniging_welke']) ? $_POST['vereniging_welke'] : null;
    $contact_met_derden = isset($_POST['contact_met_derden']) ? $_POST['contact_met_derden'] : null;
    $verlies_geleden = isset($_POST['verlies_geleden']) ? $_POST['verlies_geleden'] : 0;
    $verlies_geleden_welke = isset($_POST['verlies_geleden_welke']) ? $_POST['verlies_geleden_welke'] : null;

    // Array van checkboxes voor observaties maken (zoals in patroon11)
    $observatieArr = array();
    for ($i = 1; $i <= 17; $i++) {
        $observatieArr[] = !empty($_POST['observatie' . $i]);
    }
    $observatie = $Main->convertBoolArrayToString($observatieArr);
    // Debug: log observatie string and checkbox presence
    error_log('[patroon08] POST handler reached');
    error_log('[patroon08] navbutton=' . (isset($_REQUEST['navbutton']) ? $_REQUEST['navbutton'] : 'NULL'));
    error_log('[patroon08] observatie_string=' . $observatie);
    error_log('[patroon08] observatie_arr=' . json_encode($arr));
    // Log individual checkbox keys present in POST
    $present = [];
    for ($i = 1; $i <= 17; $i++) {
        $key = 'observatie' . $i;
        $present[$key] = isset($_POST[$key]) ? true : false;
    }
    error_log('[patroon08] observatie_keys_post=' . json_encode($present));

    // Haal vragenlijst ID op.
    $vragenlijstId = $Main->getVragenlijstId($_SESSION['clientId'], $_SESSION['loggedin_id']);

    // kijken of patroon08 bestaat door te kijken naar vragenlijst id
    $result = DatabaseConnection::getConn()->prepare("SELECT p.id FROM patroon08rollenrelatie p WHERE p.vragenlijstid = ?");
    $result->bind_param("i", $vragenlijstId);
    $result->execute();
    $result = $result->get_result()->fetch_assoc();

    if ($result != null) {
        // update
        $sql = "UPDATE `patroon08rollenrelatie` SET
            `getrouwd_samenwonend` = ?,
            `kinderen` = ?,
            `tevreden_thuissituatie` = ?,
            `steun_vrienden_familie` = ?,
            `inkomstenbron` = ?,
            `verandering_fin_sit_vroeger` = ?,
            `verandering_fin_sit_vroeger_welke` = ?,
            `verandering_fin_sit_toekomst` = ?,
            `verandering_fin_sit_toekomst_welke` = ?,
            `opleiding` = ?,
            `verandering_sociale_contacten` = ?,
            `verandering_sociale_contacten_welke` = ?,
            `groot_gezin` = ?,
            `plaats_in_gezin` = ?,
            `onderlinge_contacten_gezin` = ?,
            `agressie_gezin` = ?,
            `verenigingslid` = ?,
            `vereniging_welke` = ?,
            `contact_met_derden` = ?,
            `verlies_geleden` = ?,
            `verlies_geleden_welke` = ?,
            `observatie` = ?
            WHERE `vragenlijstid` = ?";

        $stmt = DatabaseConnection::getConn()->prepare($sql);
        if ($stmt) {
            // Bind everything as strings to avoid type-binding mistakes; MySQL will convert types as needed.
            $types = str_repeat('s', 23);
            $stmt->bind_param($types,
                $getrouwd_samenwonend,
                $kinderen,
                $tevreden_thuissituatie,
                $steun_vrienden_familie,
                $inkomstenbron,
                $verandering_fin_sit_vroeger,
                $verandering_fin_sit_vroeger_welke,
                $verandering_fin_sit_toekomst,
                $verandering_fin_sit_toekomst_welke,
                $opleiding,
                $verandering_sociale_contacten,
                $verandering_sociale_contacten_welke,
                $groot_gezin,
                $plaats_in_gezin,
                $onderlinge_contacten_gezin,
                $agressie_gezin,
                $verenigingslid,
                $vereniging_welke,
                $contact_met_derden,
                $verlies_geleden,
                $verlies_geleden_welke,
                $observatie,
                $vragenlijstId
            );
            $stmt->execute();
            $stmt->close();
        } else {
            error_log('[patroon08] Error preparing UPDATE statement: ' . DatabaseConnection::getConn()->error);
            echo "Error preparing statement: " . DatabaseConnection::getConn()->error;
        }
    } else {
        // insert
        $sql = "INSERT INTO `patroon08rollenrelatie` (
                `vragenlijstid`, `getrouwd_samenwonend`, `kinderen`, `tevreden_thuissituatie`, `steun_vrienden_familie`,
                `inkomstenbron`, `verandering_fin_sit_vroeger`, `verandering_fin_sit_vroeger_welke`, `verandering_fin_sit_toekomst`,
                `verandering_fin_sit_toekomst_welke`, `opleiding`, `verandering_sociale_contacten`, `verandering_sociale_contacten_welke`,
                `groot_gezin`, `plaats_in_gezin`, `onderlinge_contacten_gezin`, `agressie_gezin`, `verenigingslid`,
                `vereniging_welke`, `contact_met_derden`, `verlies_geleden`, `verlies_geleden_welke`, `observatie`
            ) VALUES (
                ?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?
            )";

        $stmt = DatabaseConnection::getConn()->prepare($sql);
    if ($stmt) {
            // again bind as strings
            $types = str_repeat('s', 23);
            $stmt->bind_param($types,
                $vragenlijstId,
                $getrouwd_samenwonend,
                $kinderen,
                $tevreden_thuissituatie,
                $steun_vrienden_familie,
                $inkomstenbron,
                $verandering_fin_sit_vroeger,
                $verandering_fin_sit_vroeger_welke,
                $verandering_fin_sit_toekomst,
                $verandering_fin_sit_toekomst_welke,
                $opleiding,
                $verandering_sociale_contacten,
                $verandering_sociale_contacten_welke,
                $groot_gezin,
                $plaats_in_gezin,
                $onderlinge_contacten_gezin,
                $agressie_gezin,
                $verenigingslid,
                $vereniging_welke,
                $contact_met_derden,
                $verlies_geleden,
                $verlies_geleden_welke,
                $observatie
            );
            $stmt->execute();
            $stmt->close();
        } else {
            error_log('[patroon08] Error preparing INSERT statement: ' . DatabaseConnection::getConn()->error);
            echo "Error preparing statement: " . DatabaseConnection::getConn()->error;
        }
    }

    // Navigeren naar juiste pagina (header redirect wanneer mogelijk, anders JS fallback)
    error_log('[patroon08] About to navigate, navbutton=' . $_REQUEST['navbutton']);
    $target = '';
    switch ($_REQUEST['navbutton']) {
        case 'next':
            $target = 'patroon09.php';
            break;
        case 'prev':
            $target = 'patroon07.php';
            break;
    }

    if ($target !== '') {
        if (!headers_sent()) {
            header('Location: ' . $target);
            exit;
        } else {
            // fallback: client-side redirect
            echo '<script>window.location.href="' . $target . '";</script>';
            exit;
        }
    }
    exit;
}
?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="Stylesheet" href="../../assets/css/client/patronen.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" 
          rel="stylesheet" 
          integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" 
          crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css" 
          integrity="sha512-Kc323vGBEqzTmouAECnVceyQqyqdsSiqLQISBL29aUW4U/M7pSPA/gEUZQqv1cwx4OnYxTxve5UMg5GT6L4JJg==" 
          crossorigin="anonymous" referrerpolicy="no-referrer" />
    <title>Anamnese</title>
</head>

<body>
    <form action="" method="post">
        <div class="main">
            <?php
            include '../../includes/n-header.php';
            include '../../includes/n-sidebar.php';
            ?>
            <div class="mt-5 pt-5 content">
                <div class="mt-4 mb-3 bg-white p-3" style="height: 90%; overflow: auto;">
                    <p class="card-text">
                    <div class="form-content">
                        <div class="h4 text-primary">8. Rollen- en relatiepatroon</div>
                        <div class="form">
                            <div class="questionnaire">
                                <div class="question">
                                    <p>Bent u getrouwd/samenwonend?</p>
                                    <div class="checkboxes">
                                        <p>
                                            <input type="radio" name="getrouwd_samenwonend" value="1" <?= (isset($antwoorden['getrouwd_samenwonend']) && $antwoorden['getrouwd_samenwonend'] == '1') ? "checked" : "" ?>>
                                            <label>Ja</label>
                                        </p>
                                        <p>
                                            <input type="radio" name="getrouwd_samenwonend" value="0" <?= (!isset($antwoorden['getrouwd_samenwonend']) || $antwoorden['getrouwd_samenwonend'] == '0') ? "checked" : "" ?>>
                                            <label>Nee</label>
                                        </p>
                                    </div>
                                </div>
                                <div class="question">
                                    <p>- Heeft u kinderen?</p>
                                    <div class="checkboxes">
                                        <p>
                                            <input type="radio" name="kinderen" value="1" <?= (isset($antwoorden['kinderen']) && $antwoorden['kinderen'] == '1') ? "checked" : "" ?>>
                                            <label>Ja</label>
                                        </p>
                                        <p>
                                            <input type="radio" name="kinderen" value="0" <?= (!isset($antwoorden['kinderen']) || $antwoorden['kinderen'] == '0') ? "checked" : "" ?>>
                                            <label>Nee</label>
                                        </p>
                                    </div>
                                </div>
                                <div class="question">
                                    <p>- Bent u tevreden over uw thuissituatie?</p>
                                    <div class="checkboxes">
                                        <p>
                                            <input type="radio" name="tevreden_thuissituatie" value="1" <?= (isset($antwoorden['tevreden_thuissituatie']) && $antwoorden['tevreden_thuissituatie'] == '1') ? "checked" : "" ?>>
                                            <label>Ja</label>
                                        </p>
                                        <p>
                                            <input type="radio" name="tevreden_thuissituatie" value="0" <?= (!isset($antwoorden['tevreden_thuissituatie']) || $antwoorden['tevreden_thuissituatie'] == '0') ? "checked" : "" ?>>
                                            <label>Nee</label>
                                        </p>
                                    </div>
                                </div>
                                <div class="question">
                                    <p>Heeft u een vrienden-/familiekring waar u steun aan heeft?</p>
                                    <div class="checkboxes">
                                        <p>
                                            <input type="radio" name="steun_vrienden_familie" value="1" <?= (isset($antwoorden['steun_vrienden_familie']) && $antwoorden['steun_vrienden_familie'] == '1') ? "checked" : "" ?>>
                                            <label>Ja</label>
                                        </p>
                                        <p>
                                            <input type="radio" name="steun_vrienden_familie" value="0" <?= (!isset($antwoorden['steun_vrienden_familie']) || $antwoorden['steun_vrienden_familie'] == '0') ? "checked" : "" ?>>
                                            <label>Nee</label>
                                        </p>
                                    </div>
                                </div>
                                <div class="question">
                                    <p>Wat is uw beroep of huidige bron van inkomsten?</p><textarea rows="1" cols="25" type="text" name="inkomstenbron"><?= isset($antwoorden['inkomstenbron']) ? $antwoorden['inkomstenbron'] : '' ?></textarea>
                                </div>
                                <div class="question">
                                    <p>- Is er de afgelopen tijd een verandering geweest in uw financiële situatie?</p>
                                    <div class="checkboxes">
                                        <div class="question-answer">
                                            <input class="radio" type="radio" name="verandering_fin_sit_vroeger" value="1" <?= (isset($antwoorden['verandering_fin_sit_vroeger']) && $antwoorden['verandering_fin_sit_vroeger'] == '1') ? "checked" : "" ?>>
                                            <label>Ja</label>
                                            <textarea rows="1" cols="25" class="checkfield" type="text" placeholder="welke?" name="verandering_fin_sit_vroeger_welke"><?= isset($antwoorden['verandering_fin_sit_vroeger_welke']) ? $antwoorden['verandering_fin_sit_vroeger_welke'] : '' ?></textarea>
                                        </div>
                                        <p>
                                            <input type="radio" name="verandering_fin_sit_vroeger" value="0" <?= (!isset($antwoorden['verandering_fin_sit_vroeger']) || $antwoorden['verandering_fin_sit_vroeger'] == '0') ? "checked" : "" ?>>
                                            <label>Nee</label>
                                        </p>
                                    </div>
                                </div>
                                <div class="question">
                                    <p>- Verwacht u in de nabije toekomst een verandering in uw financiële situatie?</p>
                                    <div class="checkboxes">
                                        <div class="question-answer">
                                            <input class="radio" type="radio" name="verandering_fin_sit_toekomst" value="1" <?= (isset($antwoorden['verandering_fin_sit_toekomst']) && $antwoorden['verandering_fin_sit_toekomst'] == '1') ? "checked" : "" ?>>
                                            <label>Ja</label>
                                            <textarea rows="1" cols="25" class="checkfield" type="text" placeholder="welke?" name="verandering_fin_sit_toekomst_welke"><?= isset($antwoorden['verandering_fin_sit_toekomst_welke']) ? $antwoorden['verandering_fin_sit_toekomst_welke'] : '' ?></textarea>
                                        </div>
                                        <p>
                                            <input type="radio" name="verandering_fin_sit_toekomst" value="0" <?= (!isset($antwoorden['verandering_fin_sit_toekomst']) || $antwoorden['verandering_fin_sit_toekomst'] == '0') ? "checked" : "" ?>>
                                            <label>Nee</label>
                                        </p>
                                    </div>
                                </div>
                                <div class="question">
                                    <p>Wat is uw opleiding?</p><textarea rows="1" cols="25" type="text" name="opleiding"><?= isset($antwoorden['opleiding']) ? $antwoorden['opleiding'] : '' ?></textarea>
                                </div>
                                <div class="question">
                                    <p>Is er de laatste tijd verandering gekomen in uw sociale contacten?</p>
                                    <div class="checkboxes">
                                        <div class="question-answer">
                                            <input class="radio" type="radio" name="verandering_sociale_contacten" value="1" <?= (isset($antwoorden['verandering_sociale_contacten']) && $antwoorden['verandering_sociale_contacten'] == '1') ? "checked" : "" ?>>
                                            <label>Ja</label>
                                            <textarea rows="1" cols="25" class="checkfield" type="text" placeholder="welke?" name="verandering_sociale_contacten_welke"><?= isset($antwoorden['verandering_sociale_contacten_welke']) ? $antwoorden['verandering_sociale_contacten_welke'] : '' ?></textarea>
                                        </div>
                                        <p>
                                            <input type="radio" name="verandering_sociale_contacten" value="0" <?= (!isset($antwoorden['verandering_sociale_contacten']) || $antwoorden['verandering_sociale_contacten'] == '0') ? "checked" : "" ?> >
                                            <label>Nee</label>
                                        </p>
                                    </div>
                                </div>
                                <div class="question">
                                    <p>Komt u uit een groot gezin?</p>
                                    <div class="checkboxes">
                                        <p>
                                            <input type="radio" name="groot_gezin" value="1" <?= (isset($antwoorden['groot_gezin']) && $antwoorden['groot_gezin'] == '1') ? "checked" : "" ?>>
                                            <label>Ja</label>
                                        </p>
                                        <p>
                                            <input type="radio" name="groot_gezin" value="0" <?= (!isset($antwoorden['groot_gezin']) || $antwoorden['groot_gezin'] == '0') ? "checked" : "" ?>>
                                            <label>Nee</label>
                                        </p>
                                    </div>
                                </div>
                                <div class="question">
                                    <p>- Wat was u plaats in dat gezin?</p><textarea rows="1" cols="25" type="text" name="plaats_in_gezin"><?= isset($antwoorden['plaats_in_gezin']) ? $antwoorden['plaats_in_gezin'] : '' ?></textarea>
                                </div>
                                <div class="question">
                                    <p>- Hoe verliepen de onderlinge contacten?</p><textarea rows="1" cols="25" type="text" name="onderlinge_contacten_gezin"><?= isset($antwoorden['onderlinge_contacten_gezin']) ? $antwoorden['onderlinge_contacten_gezin'] : '' ?></textarea>
                                </div>
                                <div class="question">
                                    <p>- Was er sprake van agressie in dat gezin?</p>
                                    <div class="checkboxes">
                                        <p>
                                            <input type="radio" name="agressie_gezin" value="1" <?= (isset($antwoorden['agressie_gezin']) && $antwoorden['agressie_gezin'] == '1') ? "checked" : "" ?> >
                                            <label>Ja</label>
                                        </p>
                                        <p>
                                            <input type="radio" name="agressie_gezin" value="0" <?= (!isset($antwoorden['agressie_gezin']) || $antwoorden['agressie_gezin'] == '0') ? "checked" : "" ?> >
                                            <label>Nee</label>
                                        </p>
                                    </div>
                                </div>
                                <div class="question">
                                    <p>Bent u lid van verenigingen?</p>
                                    <div class="checkboxes">
                                        <div class="question-answer">
                                            <input class="radio" type="radio" name="verenigingslid" value="1" <?= (isset($antwoorden['verenigingslid']) && $antwoorden['verenigingslid'] == '1') ? "checked" : "" ?>>
                                            <label>Ja</label>
                                            <textarea rows="1" cols="25" class="checkfield" type="text" placeholder="welke?" name="vereniging_welke"><?= isset($antwoorden['vereniging_welke']) ? $antwoorden['vereniging_welke'] : '' ?></textarea>
                                        </div>
                                        <p>
                                            <input type="radio" name="verenigingslid" value="0" <?= (!isset($antwoorden['verenigingslid']) || $antwoorden['verenigingslid'] == '0') ? "checked" : "" ?>>
                                            <label>Nee</label>
                                        </p>
                                    </div>
                                </div>
                                <div class="question">
                                    <p>- Hoe verliepen de contacten met derden (collega's, kennissen, verenigingsgenoten)?</p><textarea rows="1" cols="25" type="text" name="contact_met_derden"><?= isset($antwoorden['contact_met_derden']) ? $antwoorden['contact_met_derden'] : '' ?></textarea>
                                </div>
                                <div class="question">
                                    <p>Heeft u de laatst tijd een verlies geleden (werk, personen, enzovoort)?</p>
                                    <div class="checkboxes">
                                        <div class="question-answer">
                                            <input class="radio" type="radio" name="verlies_geleden" value="1" <?= (isset($antwoorden['verlies_geleden']) && $antwoorden['verlies_geleden'] == '1') ? "checked" : "" ?>>
                                            <label>Ja</label>
                                            <textarea rows="1" cols="25" class="checkfield" type="text" placeholder="en wel?" name="verlies_geleden_welke"><?= isset($antwoorden['verlies_geleden_welke']) ? $antwoorden['verlies_geleden_welke'] : '' ?></textarea>
                                        </div>
                                        <p>
                                            <input type="radio" name="verlies_geleden" value="0" <?= (!isset($antwoorden['verlies_geleden']) || $antwoorden['verlies_geleden'] == '0') ? "checked" : "" ?>>
                                            <label>Nee</label>
                                        </p>
                                    </div>
                                </div>

                                <div class="observation">
                                    <h2>Verpleegkundige observatie bij dit patroon</h2>
                                    <div class="question">
                                        <div class="observe"><input type="checkbox" <?= (isset($boolArrayObservatie[0]) && $boolArrayObservatie[0] == '1') ? "checked" : "" ?> name="observatie1">
                                            <p>Verstoorde verbale communicatie (afwijkende groei en ontwikkeling in communicatieve vaardigheden)</p>
                                        </div>
                                    </div>
                                    <div class="question">
                                        <div class="observe"><input type="checkbox" <?= (isset($boolArrayObservatie[1]) && $boolArrayObservatie[1] == '1') ? "checked" : "" ?> name="observatie2">
                                            <p>Anticiperende rouw</p>
                                        </div>
                                    </div>
                                    <div class="question">
                                        <div class="observe"><input type="checkbox" <?= (isset($boolArrayObservatie[2]) && $boolArrayObservatie[2] == '1') ? "checked" : "" ?> name="observatie3">
                                            <p>Disfunctionele rouw</p>
                                        </div>
                                    </div>
                                    <div class="question">
                                        <div class="observe"><input type="checkbox" <?= (isset($boolArrayObservatie[3]) && $boolArrayObservatie[3] == '1') ? "checked" : "" ?> name="observatie4">
                                            <p>Gewijzigde gezinsprocessen</p>
                                        </div>
                                    </div>
                                    <div class="question">
                                        <div class="observe"><input type="checkbox" <?= (isset($boolArrayObservatie[4]) && $boolArrayObservatie[4] == '1') ? "checked" : "" ?> name="observatie5">
                                            <p>(Dreigend) ouderschapstekort</p>
                                        </div>
                                    </div>
                                    <div class="question">
                                        <div class="observe"><input type="checkbox" <?= (isset($boolArrayObservatie[5]) && $boolArrayObservatie[5] == '1') ? "checked" : "" ?> name="observatie6">
                                            <p>Ouderrolconflict</p>
                                        </div>
                                    </div>
                                    <div class="question">
                                        <div class="observe"><input type="checkbox" <?= (isset($boolArrayObservatie[6]) && $boolArrayObservatie[6] == '1') ? "checked" : "" ?> name="observatie7">
                                            <p>Inadequate sociale interacties</p>
                                        </div>
                                    </div>
                                    <div class="question">
                                        <div class="observe"><input type="checkbox" <?= (isset($boolArrayObservatie[7]) && $boolArrayObservatie[7] == '1') ? "checked" : "" ?> name="observatie8">
                                            <p>Afwijkende groei en ontikkeling in sociale vaardigheden</p>
                                        </div>
                                    </div>
                                    <div class="question">
                                        <div class="observe"><input type="checkbox" <?= (isset($boolArrayObservatie[8]) && $boolArrayObservatie[8] == '1') ? "checked" : "" ?> name="observatie9">
                                            <p>Sociaal isolement</p>
                                        </div>
                                    </div>
                                    <div class="question">
                                        <div class="observe"><input type="checkbox" <?= (isset($boolArrayObservatie[9]) && $boolArrayObservatie[9] == '1') ? "checked" : "" ?> name="observatie10">
                                            <p>Verstoorde rolvervulling</p>
                                        </div>
                                    </div>
                                    <div class="question">
                                        <div class="observe"><input type="checkbox" <?= (isset($boolArrayObservatie[10]) && $boolArrayObservatie[10] == '1') ? "checked" : "" ?> name="observatie11">
                                            <p>Onopgelost onafhankelijkheids-/afhankelijkheidsconflict</p>
                                        </div>
                                    </div>
                                    <div class="question">
                                        <div class="observe"><input type="checkbox" <?= (isset($boolArrayObservatie[11]) && $boolArrayObservatie[11] == '1') ? "checked" : "" ?> name="observatie12">
                                            <p>Sociale afwijzing</p>
                                        </div>
                                    </div>
                                    <div class="question">
                                        <div class="observe"><input type="checkbox" <?= (isset($boolArrayObservatie[12]) && $boolArrayObservatie[12] == '1') ? "checked" : "" ?> name="observatie13">
                                            <p>(Dreigende) overbelasting van de mantelzorg)</p>
                                        </div>
                                    </div>
                                    <div class="question">
                                        <div class="observe"><input type="checkbox" <?= (isset($boolArrayObservatie[13]) && $boolArrayObservatie[13] == '1') ? "checked" : "" ?> name="observatie14">
                                            <p>Mantelzorgtekort</p>
                                        </div>
                                    </div>
                                    <div class="question">
                                        <div class="observe"><input type="checkbox" <?= (isset($boolArrayObservatie[14]) && $boolArrayObservatie[14] == '1') ? "checked" : "" ?> name="observatie15">
                                            <p>Dreigend geweld:</p>
                                        </div>
                                    </div>
                                    <div class="question">
                                        <div class="observe"><input type="checkbox" <?= (isset($boolArrayObservatie[15]) && $boolArrayObservatie[15] == '1') ? "checked" : "" ?> name="observatie16">
                                            <p>gericht op andere</p>
                                        </div>
                                    </div>
                                    <div class="question">
                                        <div class="observe"><input type="checkbox" <?= (isset($boolArrayObservatie[16]) && $boolArrayObservatie[16] == '1') ? "checked" : "" ?> name="observatie17">
                                            <p>gericht op voorwerpen (meubilair, enzovoort)</p>
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
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" 
            integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" 
            crossorigin="anonymous"></script> 
</body>

</html>