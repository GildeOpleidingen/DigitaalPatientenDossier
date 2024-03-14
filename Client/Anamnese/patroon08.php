<?php
session_start();
include '../../Database/DatabaseConnection.php';
include '../../Functions/Functions.php';
include '../../Functions/AnamneseFunctions.php';

$antwoorden = getPatternAnswers($_SESSION['clientId'], 8);

$boolArrayObservatie = str_split($antwoorden['observatie']);

$medewerkerId = $_SESSION['loggedin_id'];
$clientId = $_SESSION['clientId'];

if (isset($_REQUEST['navbutton'])) {
$getrouwd_of_samenwonend = $_POST['getrouwd_samenwonend'];
$kinderen = $_POST['kinderen'];
$tevreden_thuissituatie = $_POST['tevreden_thuissituatie'];
$steun_vrienden_familie = $_POST['steun_vrienden_familie'];
$inkomstenbron = $_POST['inkomstenbron'];
$verandering_fin_sit_vroeger = $_POST['verandering_fin_sit_vroeger'] ?? "";
$verandering_fin_sit_vroeger_welke = $_POST['verandering_fin_sit_vroeger_welke'] ?? "";
$verandering_fin_sit_toekomst = $_POST['verandering_fin_sit_toekomst'] ?? "";
$verandering_fin_sit_toekomst_welke = $_POST['verandering_fin_sit_toekomst_welke'] ?? "";
$verandering_sociale_contacten = $_POST['verandering_sociale_contacten'];
$verandering_sociale_contacten_welke = $_POST['verandering_sociale_contacten_welke'];
$opleiding = $_POST['opleiding'];
$groot_gezin = $_POST['groot_gezin'];
$plaats_in_gezin = $_POST['plaats_in_gezin'];
$onderlinge_contacten_gezin = $_POST['onderlinge_contacten_gezin'];
$agressie_gezin = $_POST['agressie_gezin'];
$verenigingslid = $_POST['verenigingslid'];
$vereniging_welke = $_POST['vereniging_welke'];
$contact_met_derden = $_POST['contact_met_derden'];
$verlies_geleden = $_POST['verlies_geleden'];
$verlies_geleden_welke = $_POST['verlies_geleden_welke'];

// array van checkboxes van observatie tab
$observatie = array(!empty($_POST['observatie1']), !empty($_POST['observatie2']), !empty($_POST['observatie3']), !empty($_POST['observatie4']), !empty($_POST['observatie5']), !empty($_POST['observatie6']), !empty($_POST['observatie7']), !empty($_POST['observatie8']), !empty($_POST['observatie9']), !empty($_POST['observatie10']), !empty($_POST['observatie11']), !empty($_POST['observatie12']), !empty($_POST['observatie13']), !empty($_POST['observatie14']), !empty($_POST['observatie15']), !empty($_POST['observatie16']), !empty($_POST['observatie17']));

$observatie = convertBoolArrayToString($observatie);

$result = getVragenlijstId($clientId);
if ($result != null){
    $vragenlijstId = $result;
} else {
    $result = insertVragenlijst($_SESSION['clientId'], $medewerkerId);
    $vragenlijstId = $result;
}

$result = checkIfPatternExists("patroon08rollenrelatie", $vragenlijstId);

if ($result != null) {
    //update
    $result1 = DatabaseConnection::getConn()->prepare("UPDATE `patroon08rollenrelatie` SET
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
           WHERE `vragenlijstid`=?");
    $result1->bind_param("iiiisisissisissiississi", $getrouwd_of_samenwonend, $kinderen, $tevreden_thuissituatie, $steun_vrienden_familie, $inkomstenbron, $verandering_fin_sit_vroeger, $verandering_fin_sit_vroeger_welke, $verandering_fin_sit_toekomst, $verandering_fin_sit_toekomst_welke, $opleiding, $verandering_sociale_contacten, $verandering_sociale_contacten_welke, $groot_gezin, $plaats_in_gezin, $onderlinge_contacten_gezin, $agressie_gezin, $verenigingslid, $vereniging_welke, $contact_met_derden, $verlies_geleden, $verlies_geleden_welke, $observatie, $vragenlijstId);
    $result1->execute();
    $result1 = $result1->get_result();

}else{
    //hier insert je alle data in patroon02

    $result2 = DatabaseConnection::getConn()->prepare( "INSERT INTO `patroon08rollenrelatie`(
                `vragenlijstid`,
                `getrouwd_samenwonend`,
                `kinderen`,
                `tevreden_thuissituatie`,
                `steun_vrienden_familie`,
                `inkomstenbron`,
                `verandering_fin_sit_vroeger`,
                `verandering_fin_sit_vroeger_welke`,
                `verandering_fin_sit_toekomst`,
                `verandering_fin_sit_toekomst_welke`,
                `opleiding`,
                `verandering_sociale_contacten`,
                `verandering_sociale_contacten_welke`,
                `groot_gezin`,
                `plaats_in_gezin`,
                `onderlinge_contacten_gezin`,
                `agressie_gezin`,
                `verenigingslid`,
                `vereniging_welke`,
                `contact_met_derden`,
                `verlies_geleden`,
                `verlies_geleden_welke`,
                `observatie`)
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
    $result2->bind_param("iiiiisisissisissiississ", $vragenlijstId, $getrouwd_of_samenwonend, $kinderen, $tevreden_thuissituatie, $steun_vrienden_familie, $inkomstenbron, $verandering_fin_sit_vroeger, $verandering_fin_sit_vroeger_welke, $verandering_fin_sit_toekomst, $verandering_fin_sit_toekomst_welke, $opleiding, $verandering_sociale_contacten, $verandering_sociale_contacten_welke, $groot_gezin, $plaats_in_gezin, $onderlinge_contacten_gezin, $agressie_gezin, $verenigingslid, $vereniging_welke, $contact_met_derden, $verlies_geleden, $verlies_geleden_welke, $observatie);
    $result2->execute();
    $result2 = $result2->get_result();
    }
}

if (isset($_REQUEST['navbutton'])) {
    //TODO: hier actie om data op te slaan in database.
    switch($_REQUEST['navbutton']) {
        case 'next': //action for next here
            header('Location: patroon09.php');
            break;

        case 'prev': //action for previous here
            header('Location: patroon07.php');
            break;
    }
    exit;
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
	<link rel="Stylesheet" href="patronen.css">
    <title>Anamnese</title>
</head>
<body>
    <form action="" method="post">
    <div class="main">
        <?php
        include '../../Includes/header.php';
        include '../../Includes/sidebar.php';
        ?>
        <div class="content">
            <div class="form-content">
            <div class="pages">8 Rollen- en relatiepatroon</div>
                <div class="form">
                    <div class="questionnaire">
                    <div class="question"><p>Bent u getrouwd/samenwonend?</p>
                            <div class="checkboxes">
                                <p>    
                                    <input type="radio" name="getrouwd_samenwonend" value="1" <?= $antwoorden['getrouwd_samenwonend'] ? "checked" : "" ?>>
                                    <label>Ja</label>
                                </p>
                                <p>
                                    <input type="radio" name="getrouwd_samenwonend" value="0" <?= !$antwoorden['getrouwd_samenwonend'] ? "checked" : "" ?>>
                                    <label>Nee</label>
                                </p>
                            </div>
                        </div>
                        <div class="question"><p>- Heeft u kinderen?</p>
                            <div class="checkboxes">
                                <p>    
                                    <input type="radio" name="kinderen" value="1" <?= $antwoorden['kinderen'] ? "checked" : "" ?>>
                                    <label>Ja</label>
                                </p>
                                <p>
                                    <input type="radio" name="kinderen" value="0" <?= !$antwoorden['kinderen'] ? "checked" : "" ?>>
                                    <label>Nee</label>
                                </p>
                            </div>
                        </div>
                        <div class="question"><p>- Bent u tevreden over uw thuissituatie?</p>
                            <div class="checkboxes">
                                <p>    
                                    <input type="radio" name="tevreden_thuissituatie" value="1" <?= $antwoorden['tevreden_thuissituatie'] ? "checked" : "" ?>>
                                    <label>Ja</label>
                                </p>
                                <p>
                                    <input type="radio" name="tevreden_thuissituatie" value="0" <?= !$antwoorden['tevreden_thuissituatie'] ? "checked" : "" ?>>
                                    <label>Nee</label>
                                </p>
                            </div>
                        </div>
                        <div class="question"><p>Heeft u een vrienden-/familiekring waar u steun aan heeft?</p>
                            <div class="checkboxes">
                                <p>    
                                    <input type="radio" name="steun_vrienden_familie" value="1" <?= $antwoorden['steun_vrienden_familie'] ? "checked" : "" ?>>
                                    <label>Ja</label>
                                </p>
                                <p>
                                    <input type="radio" name="steun_vrienden_familie" value="0" <?= !$antwoorden['steun_vrienden_familie'] ? "checked" : "" ?>>
                                    <label>Nee</label>
                                </p>
                            </div>
                        </div>
                        <div class="question"><p>Wat is uw beroep of huidige bron van inkomsten?</p><textarea  rows="1" cols="25" type="text" name="inkomstenbron"><?= $antwoorden['inkomstenbron'] ?></textarea></div>
                        <div class="question"><p>- Is er de afgelopen tijd een verandering geweest in uw financiële situatie?</p>
                            <div class="checkboxes">
                                <div class="question-answer">
                                    <input id="radio" type="radio" name="verandering_fin_sit_vroeger" value="1" <?= $antwoorden['verandering_fin_sit_vroeger'] ? "checked" : "" ?>>
                                    <label>Ja</label>
                                    <textarea  rows="1" cols="25" id="checkfield" type="text" placeholder="welke?" name="verandering_fin_sit_vroeger_welke"><?= $antwoorden['verandering_fin_sit_vroeger_welke'] ?></textarea>
                                </div>
                                <p>
                                    <input type="radio" name="verandering_fin_sit_vroeger" value="0"  <?= !$antwoorden['verandering_fin_sit_vroeger'] ? "checked" : "" ?>>
                                    <label>Nee</label>
                                </p>
                            </div>
                        </div>
                        <div class="question"><p>- Verwacht u in de nabije toekomst een verandering in uw financiële situatie?</p>
                            <div class="checkboxes">
                                <div class="question-answer">
                                    <input id="radio" type="radio" name="verandering_fin_sit_toekomst" value="1" <?= $antwoorden['verandering_fin_sit_toekomst'] ? "checked" : "" ?>>
                                    <label>Ja</label>
                                    <textarea  rows="1" cols="25" id="checkfield" type="text" placeholder="welke?" name="verandering_fin_sit_toekomst_welke"><?= $antwoorden['verandering_fin_sit_toekomst_welke'] ?></textarea>
                                </div>
                                <p>
                                    <input type="radio" name="verandering_fin_sit_toekomst" value="0" <?= !$antwoorden['verandering_fin_sit_toekomst'] ? "checked" : "" ?>>
                                    <label>Nee</label>
                                </p>
                            </div>
                        </div>
                        <div class="question"><p>Wat is uw opleiding?</p><textarea  rows="1" cols="25" type="text" name="opleiding"><?= $antwoorden['opleiding'] ?></textarea></div>
                        <div class="question"><p>Is er de laatste tijd verandering gekomen in uw sociale contacten?</p>
                            <div class="checkboxes">
                                <div class="question-answer">
                                    <input id="radio" type="radio" name="verandering_sociale_contacten" value="1" <?= $antwoorden['verandering_sociale_contacten'] ? "checked" : "" ?>>
                                    <label>Ja</label>
                                    <textarea  rows="1" cols="25" id="checkfield" type="text" placeholder="welke?" name="verandering_sociale_contacten_welke"><?= $antwoorden['verandering_sociale_contacten_welke'] ?></textarea>
                                </div>
                                <p>
                                    <input type="radio" name="verandering_sociale_contacten" value="0" <?= !$antwoorden['verandering_sociale_contacten'] ? "checked" : "" ?>>
                                    <label>Nee</label>
                                </p>
                            </div>
                        </div>
                        <div class="question"><p>Komt u uit een groot gezin?</p>
                            <div class="checkboxes">
                                <p>    
                                    <input type="radio" name="groot_gezin" value="1" <?= $antwoorden['groot_gezin'] ? "checked" : "" ?>>
                                    <label>Ja</label> 
                                </p>
                                <p>
                                    <input type="radio" name="groot_gezin" value="0" <?= !$antwoorden['groot_gezin'] ? "checked" : "" ?>>
                                    <label>Nee</label>
                                </p>
                            </div>
                        </div>
                        <div class="question"><p>- Wat was u plaats in dat gezin?</p><textarea  rows="1" cols="25" type="text" name="plaats_in_gezin"><?= $antwoorden['plaats_in_gezin'] ?></textarea></div>
                        <div class="question"><p>- Hoe verliepen de onderlinge contacten?</p><textarea  rows="1" cols="25" type="text" name="onderlinge_contacten_gezin"><?= $antwoorden['onderlinge_contacten_gezin'] ?></textarea></div>
                        <div class="question"><p>- Was er sprake van agressie in dat gezin?</p>
                            <div class="checkboxes">
                                <p>    
                                    <input type="radio" name="agressie_gezin" value="1" <?= $antwoorden['agressie_gezin'] ? "checked" : "" ?>>
                                    <label>Ja</label>
                                </p>
                                <p>
                                    <input type="radio" name="agressie_gezin" value="0" <?= !$antwoorden['agressie_gezin'] ? "checked" : "" ?>>
                                    <label>Nee</label>
                                </p>
                            </div>
                        </div>
                        <div class="question"><p>Bent u lid van verenigingen?</p>
                            <div class="checkboxes">
                                <div class="question-answer">
                                    <input id="radio" type="radio" name="verenigingslid" value="1" <?= $antwoorden['verenigingslid'] ? "checked" : "" ?>>
                                    <label>Ja</label>
                                    <textarea  rows="1" cols="25" id="checkfield" type="text" placeholder="welke?" name="vereniging_welke"><?= $antwoorden['vereniging_welke'] ?></textarea>
                                </div>
                                <p>
                                    <input type="radio" name="verenigingslid" value="0" <?= !$antwoorden['verenigingslid'] ? "checked" : "" ?>>
                                    <label>Nee</label>
                                </p>
                            </div>
                        </div>
                        <div class="question"><p>- Hoe verliepen de contacten met derden (collega's, kennissen, verenigingsgenoten)?</p><textarea  rows="1" cols="25" type="text" name="contact_met_derden"><?= $antwoorden['contact_met_derden'] ?></textarea></div>
                        <div class="question"><p>Heeft u de laatst tijd een verlies geleden (werk, personen, enzovoort)?</p>
                            <div class="checkboxes">
                                <div class="question-answer">
                                    <input id="radio" type="radio" name="verlies_geleden" value="1" <?= $antwoorden['verlies_geleden'] ? "checked" : "" ?>>
                                    <label>Ja</label>
                                    <textarea  rows="1" cols="25" id="checkfield" type="text" placeholder="en wel?" name="verlies_geleden_welke"><?= $antwoorden['verlies_geleden_welke'] ?></textarea>
                                </div>
                                <p>
                                    <input type="radio" name="verlies_geleden" value="0" <?= !$antwoorden['verlies_geleden'] ? "checked" : "" ?>>
                                    <label>Nee</label>
                                </p>
                            </div>
                        </div>

                        <div class="observation">
                            <h2>Verpleegkundige observatie bij dit patroon</h2>
                            <div class="question"><div class="observe"><input type="checkbox" <?= $boolArrayObservatie[0] ? "checked" : ""?> name="observatie1"><p>Verstoorde verbale communicatie (afwijkende groei en ontwikkeling in communicatieve vaardigheden)</p></div></div>
                            <div class="question"><div class="observe"><input type="checkbox" <?= $boolArrayObservatie[1] ? "checked" : ""?> name="observatie2"><p>Anticiperende rouw</p></div></div>
                            <div class="question"><div class="observe"><input type="checkbox" <?= $boolArrayObservatie[2] ? "checked" : ""?> name="observatie3"><p>Disfunctionele rouw</p></div></div>
                            <div class="question"><div class="observe"><input type="checkbox" <?= $boolArrayObservatie[3] ? "checked" : ""?> name="observatie4"><p>Gewijzigde gezinsprocessen</p></div></div>
                            <div class="question"><div class="observe"><input type="checkbox" <?= $boolArrayObservatie[4] ? "checked" : ""?> name="observatie5"><p>(Dreigend) ouderschapstekort</p></div></div>
                            <div class="question"><div class="observe"><input type="checkbox" <?= $boolArrayObservatie[5] ? "checked" : ""?> name="observatie6"><p>Ouderrolconflict</p></div></div>
                            <div class="question"><div class="observe"><input type="checkbox" <?= $boolArrayObservatie[6] ? "checked" : ""?> name="observatie7"><p>Inadequate sociale interacties</p></div></div>
                            <div class="question"><div class="observe"><input type="checkbox" <?= $boolArrayObservatie[7] ? "checked" : ""?> name="observatie8"><p>Afwijkende groei en ontikkeling in sociale vaardigheden</p></div></div>
                            <div class="question"><div class="observe"><input type="checkbox" <?= $boolArrayObservatie[8] ? "checked" : ""?> name="observatie9"><p>Sociaal isolement</p></div></div>
                            <div class="question"><div class="observe"><input type="checkbox" <?= $boolArrayObservatie[9] ? "checked" : ""?> name="observatie10"><p>Verstoorde rolvervulling</p></div></div>
                            <div class="question"><div class="observe"><input type="checkbox" <?= $boolArrayObservatie[10] ? "checked" : ""?> name="observatie11"><p>Onopgelost onafhankelijkheids-/afhankelijkheidsconflict</p></div></div>
                            <div class="question"><div class="observe"><input type="checkbox" <?= $boolArrayObservatie[11] ? "checked" : ""?> name="observatie12"><p>Sociale afwijzing</p></div></div>
                            <div class="question"><div class="observe"><input type="checkbox" <?= $boolArrayObservatie[12] ? "checked" : ""?> name="observatie13"><p>(Dreigende) overbelasting van de mantelzorg)</p></div></div>
                            <div class="question"><div class="observe"><input type="checkbox" <?= $boolArrayObservatie[13] ? "checked" : ""?> name="observatie14"><p>Mantelzorgtekort</p></div></div>
                            <div class="question"><div class="observe"><input type="checkbox" <?= $boolArrayObservatie[14] ? "checked" : ""?> name="observatie15"><p>Dreigend geweld:</p></div></div>
                            <div class="question"><div class="observe"><input type="checkbox" <?= $boolArrayObservatie[15] ? "checked" : ""?> name="observatie16"><p>gericht op andere</p></div></div>
                            <div class="question"><div class="observe"><input type="checkbox" <?= $boolArrayObservatie[16] ? "checked" : ""?> name="observatie17"><p>gericht op voorwerpen (meubilair, enzovoort)</p></div></div>
                        </div>
                    </div>
                </div>
                <div class="submit">
                    <button name="navbutton" type="submit" value="prev">< Vorige</button>
                    <button name="navbutton" type="submit" value="next">Volgende ></button>
                </div>
            </div>
        </div>
        </div>
        </form>
</body>
</html>