<?php
session_start();
include '../../Database/DatabaseConnection.php';
include '../../Functions/Functions.php';
include '../../Functions/AnamneseFunctions.php';

$antwoorden = getPatternAnswers($_SESSION['clientId'], 11);

$boolArrayObservatie = str_split($antwoorden['observatie']);
$boolArrayGeloof = str_split($antwoorden['geloof_welk']);

$medewerkerId = $_SESSION['loggedin_id'];
$clientId = $_SESSION['clientId'];

if (isset($_REQUEST['navbutton'])) {
    $gelovig = $_POST['gelovig'];
    $geloofWelk = array(!empty($_POST['geloof1']), !empty($_POST['geloof2']), !empty($_POST['geloof3']), !empty($_POST['geloof4']), !empty($_POST['geloof5']), !empty($_POST['geloof6']));
    $geloofAnders = strval($_POST['geloof_anders']);
    $behoefteReligieuzeActiviteit = $_POST['behoefte_religieuze_activiteit'];
    $gebruikenTavGeloofsovertuiging = $_POST['gebruiken_tav_geloofsovertuiging'];
    $gebruikenTavGeloofsovertuigingWelke = $_POST['gebruiken_tav_geloofsovertuiging_welke'];
    $gebruikenTavGeloofsovertuigingWanneer = $_POST['gebruiken_tav_geloofsovertuiging_wanneer'];
    $overeenkomstWaardenNormen = $_POST['overeenkomst_waarden_normen'];
    $etnischeAchtergrond = $_POST['etnische_achtergrond'];
    $gebruikenMbtEtnischeAchtergrond = $_POST['gebruiken_mbt_etnische_achtergrond'];
    $gebruikenMbtEtnischeAchtergrondWelke = $_POST['gebruiken_mbt_etnische_achtergrond_welke'];
    $gebruikenMbtEtnischeAchtergrondWanneer = $_POST['gebruiken_mbt_etnische_achtergrond_wanneer'];
    // array van checkboxes van observatie tab
    $arr = array(!empty($_POST['observatie1']), !empty($_POST['observatie2']), !empty($_POST['observatie3']), !empty($_POST['observatie4']));

    $geloofWelk = convertBoolArrayToString($geloofWelk);
    $observatie = convertBoolArrayToString($arr);

    $result = getVragenlijstId($clientId);
    if ($result != null){
        $vragenlijstId = $result;
    } else {
        $result = insertVragenlijst($_SESSION['clientId'], $medewerkerId);
        $vragenlijstId = $result;
    }

    $result = checkIfPatternExists("patroon11waardelevensovertuiging", $vragenlijstId);


    if ($result != null) {
        //update
        $result1 = DatabaseConnection::getConn()->prepare("UPDATE `patroon11waardelevensovertuiging`
            SET
            `gelovig`=?,
            `geloof_welk`=?,
            `geloof_anders`=?,
            `behoefte_religieuze_activiteit`=?,
            `gebruiken_tav_geloofsovertuiging`=?,
            `gebruiken_tav_geloofsovertuiging_welke`=?,
            `gebruiken_tav_geloofsovertuiging_wanneer`=?,
            `overeenkomst_waarden_normen`=?,
            `etnische_achtergrond`=?,
            `gebruiken_mbt_etnische_achtergrond`=?,
            `gebruiken_mbt_etnische_achtergrond_welke`=?,
            `gebruiken_mbt_etnische_achtergrond_wanneer`=?,
            `observatie`=?
            WHERE `vragenlijstid`=?");
        $result1->bind_param("issiissisisssi", $gelovig, $geloofWelk, $geloofAnders, $behoefteReligieuzeActiviteit, $gebruikenTavGeloofsovertuiging, $gebruikenTavGeloofsovertuigingWelke, $gebruikenTavGeloofsovertuigingWanneer, $overeenkomstWaardenNormen, $etnischeAchtergrond, $gebruikenMbtEtnischeAchtergrond, $gebruikenMbtEtnischeAchtergrondWelke, $gebruikenMbtEtnischeAchtergrondWanneer, $observatie, $vragenlijstId);
        $result1->execute();
        $result1 = $result1->get_result();

    }else{
        //hier insert je alle data in patroon02
        $geloofAnders = "'$geloofAnders'";
        $result2 = DatabaseConnection::getConn()->prepare( "INSERT INTO `patroon11waardelevensovertuiging`(
                `vragenlijstid`,
                `gelovig`,
                `geloof_welk`,
                `geloof_anders`,
                `behoefte_religieuze_activiteit`,
                `gebruiken_tav_geloofsovertuiging`,
                `gebruiken_tav_geloofsovertuiging_welke`,
                `gebruiken_tav_geloofsovertuiging_wanneer`,
                `overeenkomst_waarden_normen`,
                `etnische_achtergrond`,
                `gebruiken_mbt_etnische_achtergrond`,
                `gebruiken_mbt_etnische_achtergrond_welke`,
                `gebruiken_mbt_etnische_achtergrond_wanneer`,
                `observatie`)
            VALUES (
                    ?,
                    ?,
                    ?,
                    ?,
                    ?,
                    ?,
                    ?,
                    ?,
                    ?,
                    ?,
                    ?,
                    ?,
                    ?,
                    ?)");
        $result2->bind_param("iissiissisisss", $vragenlijstId, $gelovig, $geloofWelk, $geloofAnders, $behoefteReligieuzeActiviteit, $gebruikenTavGeloofsovertuiging, $gebruikenTavGeloofsovertuigingWelke, $gebruikenTavGeloofsovertuigingWanneer, $overeenkomstWaardenNormen, $etnischeAchtergrond, $gebruikenMbtEtnischeAchtergrond, $gebruikenMbtEtnischeAchtergrondWelke, $gebruikenMbtEtnischeAchtergrondWanneer, $observatie);
        $result2->execute();
        $result2 = $result2->get_result();

    }
    switch ($_REQUEST['navbutton']) {
        case 'next': //action for next here
            header('Location: patroon01.php');
            break;

        case 'prev': //action for previous here
            header('Location: patroon10.php');
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
            <div class="pages">11 Stressverwerkingspatroon (probleemhantering)</div>
                <div class="form">
                    <div class="questionnaire">
                        <div class="question"><p>Bent u gelovig?</p>
                            <div class="checkboxes">
                                <div class="question-answer">
                                    <input id="radio" type="radio" name="gelovig" value="1"<?= $antwoorden['gelovig'] ? "checked" : "" ?>
                                    <label>Ja</label>
                                    <div id="checkfield">
                                        <div class="question"><div class="observe"><input type="checkbox" <?= @$boolArrayGeloof[0] ? "checked" : "" ?> name="geloof1"><p>R-K</p></div></div>
                                        <div class="question"><div class="observe"><input type="checkbox" <?= @$boolArrayGeloof[1] ? "checked" : "" ?> name="geloof2"><p>Nederlands hervormd</p></div></div>
                                        <div class="question"><div class="observe"><input type="checkbox" <?= @$boolArrayGeloof[2] ? "checked" : "" ?> name="geloof3"><p>Gereformeerd</p></div></div>
                                        <div class="question"><div class="observe"><input type="checkbox" <?= @$boolArrayGeloof[3] ? "checked" : "" ?> name="geloof4"><p>Moslim</p></div></div>
                                        <div class="question"><div class="observe"><input type="checkbox" <?= @$boolArrayGeloof[4] ? "checked" : "" ?> name="geloof5"><p>Joods</p></div></div>
                                        <div class="question"><div class="observe"><input type="checkbox" <?= @$boolArrayGeloof[5] ? "checked" : "" ?> name="geloof6"><p>Anders, namelijk:</p></div><textarea  rows="1" cols="25" type="text" name="geloof_anders"><?= $antwoorden['geloof_anders'] ? $antwoorden['geloof_anders'] : "" ?></textarea></div>
                                    </div>
                                </div>
                                <p>
                                    <input type="radio" name="gelovig" value="0"<?= !$antwoorden['gelovig'] ? "checked" : "" ?>>
                                    <label>Nee</label>
                                </p>
                            </div>
                        </div>
                        <div class="question"><p>- Heeft u behoefte aan religieuze activiteiten?</p>
                            <div class="checkboxes">
                                <p>    
                                    <input type="radio" name="behoefte_religieuze_activiteit" value="1"<?= $antwoorden['behoefte_religieuze_activiteit'] ? "checked" : "" ?>>
                                    <label>Ja</label>
                                </p>
                                <p>
                                    <input type="radio" name="behoefte_religieuze_activiteit" value="0"<?= !$antwoorden['behoefte_religieuze_activiteit'] ? "checked" : "" ?>>
                                    <label>Nee</label>
                                </p>
                            </div>
                        </div>
                        <div class="question"><p>- Zijn er gebruiken ten aanzien van uw geloofsovertuiging waar rekening mee gehouden moet worden?</p>
                            <div class="checkboxes">
                                <div class="question-answer">
                                    <input id="radio" type="radio" name="gebruiken_tav_geloofsovertuiging" value="1"<?= $antwoorden['gebruiken_tav_geloofsovertuiging'] ? "checked" : "" ?>>
                                    <label>Ja</label>
                                    <textarea rows="1" cols="25" id="checkfield" type="text" placeholder="welke?" name="gebruiken_tav_geloofsovertuiging_welke"><?= $antwoorden['gebruiken_tav_geloofsovertuiging_welke'] ?></textarea>
                                </div>
                                <p>
                                    <input type="radio" name="gebruiken_tav_geloofsovertuiging" value="0"<?= !$antwoorden['gebruiken_tav_geloofsovertuiging'] ? "checked" : "" ?>>
                                    <label>Nee</label>
                                </p>
                            </div>
                        </div>
                        <div class="question"><p>Ja, wanneer?</p><textarea  rows="1" cols="25" type="text" name="gebruiken_tav_geloofsovertuiging_wanneer"><?= $antwoorden['gebruiken_tav_geloofsovertuiging_wanneer'] ?></textarea></div>
                        <div class="question"><p>Komen uw waarden en normen overeen met maatschappelijke waarden en normen?</p>
                            <div class="checkboxes">
                                <p>    
                                    <input type="radio" name="overeenkomst_waarden_normen" value="1"<?= $antwoorden['overeenkomst_waarden_normen'] ? "checked" : "" ?>>
                                    <label>Ja</label>
                                </p>
                                <p>
                                    <input type="radio" name="overeenkomst_waarden_normen" value="0"<?= !$antwoorden['overeenkomst_waarden_normen'] ? "checked" : "" ?>>
                                    <label>Nee</label>
                                </p>
                            </div>
                        </div>
                        <div class="question"><p>Wat is uw etnische achtergrond</p><textarea rows="1" cols="25" type="text" name="etnische_achtergrond"><?= $antwoorden['etnische_achtergrond'] ? $antwoorden['etnische_achtergrond'] : "" ?></textarea></div>
                        <div class="question"><p>- Zijn er gebruiken met betrekking tot uw etnische achtergrond waar rekening mee gehouden moet worden?</p>
                            <div class="checkboxes">
                                <div class="question-answer">
                                    <input id="radio" type="radio" name="gebruiken_mbt_etnische_achtergrond" value="1" <?= $antwoorden['gebruiken_mbt_etnische_achtergrond'] ? "checked" : "" ?>>
                                    <label>Ja</label>
                                    <textarea  rows="1" cols="25" id="checkfield" type="text" placeholder="welke?" name="gebruiken_mbt_etnische_achtergrond_welke"><?= $antwoorden['gebruiken_mbt_etnische_achtergrond_welke'] ?></textarea>
                                </div>
                                <p>
                                    <input type="radio" name="gebruiken_mbt_etnische_achtergrond" value="0" <?= !$antwoorden['gebruiken_mbt_etnische_achtergrond'] ? "checked" : "" ?>>
                                    <label>Nee</label>
                                </p>
                            </div>
                        </div>
                        <div class="question"><p>Ja, wanneer?</p><textarea  rows="1" cols="25" type="text" name="gebruiken_mbt_etnische_achtergrond_wanneer"><?= $antwoorden['gebruiken_mbt_etnische_achtergrond'] ? $antwoorden['gebruiken_mbt_etnische_achtergrond_wanneer'] : "" ?></textarea></div>

                        <div class="observation">
                            <h2>Verpleegkundige observatie bij dit patroon</h2>
                            <div class="question"><div class="observe"><input type="checkbox" <?= $boolArrayObservatie[0] ? "checked" : "" ?> name="observatie1"><p>Geestelijke nood</p></div></div>
                            <div class="question"><div class="observe"><input type="checkbox" <?= $boolArrayObservatie[1] ? "checked" : "" ?> name="observatie2"><p>Verandering in waarden en normen</p></div></div>
                            <div class="question"><div class="observe"><input type="checkbox" <?= $boolArrayObservatie[2] ? "checked" : "" ?> name="observatie3"><p>Verandering in rolopvatting met betrekking tot etnische achtergrond</p></div></div>
                            <div class="question"><div class="observe"><input type="checkbox" <?= $boolArrayObservatie[3] ? "checked" : "" ?> name="observatie4"><p>Verandering in rolinvulling met betrekking tot etnische achtergrond</p></div></div>
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