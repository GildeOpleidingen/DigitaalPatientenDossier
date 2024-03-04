
<?php
session_start();
include '../../Database/DatabaseConnection.php';
include '../../Functions/Functions.php';

$antwoorden = getPatternAnswers($_SESSION['clientId'], 11);

$boolArrayGeloof = str_split($antwoorden['geloof_welk']);
$boolArrayObservatie = str_split($antwoorden['observatie']);

if (isset($_REQUEST['navbutton'])) {
    $clientId = $_GET['id'];
    // TODO: hier actie om data op te slaan in database.
    switch($_REQUEST['navbutton']) {
        case 'next': //action for next here
            header('Location: patroon01.php?id='.$clientId); //TODO: hier moet naar de hoofdpagina genavigeerd worden.
            break;
    
        case 'prev': //action for previous here
            header('Location: patroon10.php?id='.$clientId);
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
        ?>
        <?php
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
                                    <input id="radio" type="radio" name="gelovig" <?= $antwoorden['gelovig'] ? "checked" : "" ?>>
                                    <label>Ja</label>
                                    <div id="checkfield">
                                        <div class="question"><div class="observe"><input type="checkbox" <?= $boolArrayGeloof[0] ? "checked" : "" ?> name="geloof1"><p>R-K</p></div></div>
                                        <div class="question"><div class="observe"><input type="checkbox" <?= $boolArrayGeloof[1] ? "checked" : "" ?> name="geloof2"><p>Nederlands hervormd</p></div></div>
                                        <div class="question"><div class="observe"><input type="checkbox" <?= $boolArrayGeloof[2] ? "checked" : "" ?> name="geloof3"><p>Gereformeerd</p></div></div>
                                        <div class="question"><div class="observe"><input type="checkbox" <?= $boolArrayGeloof[3] ? "checked" : "" ?> name="geloof4"><p>Moslim</p></div></div>
                                        <div class="question"><div class="observe"><input type="checkbox" <?= $boolArrayGeloof[4] ? "checked" : "" ?> name="geloof5"><p>Joods</p></div></div>
                                        <div class="question"><div class="observe"><input type="checkbox" <?= $boolArrayGeloof[5] ? "checked" : "" ?> name="geloof6"><p>Anders, namelijk:</p></div><textarea  rows="1" cols="25" type="text"><?= $antwoorden['geloof_anders'] ? $anwoorden['geloof_anders'] : "" ?></textarea></div>
                                    </div>
                                </div>
                                <p>
                                    <input type="radio" name="gelovig" <?= !$antwoorden['gelovig'] ? "checked" : "" ?>>
                                    <label>Nee</label>
                                </p>
                            </div>
                        </div>
                        <div class="question"><p>- Heeft u behoefte aan religieuze activiteiten?</p>
                            <div class="checkboxes">
                                <p>    
                                    <input type="radio" name="behoefte_religieuze_activiteit" <?= $antwoorden['behoefte_religieuze_activiteit'] ? "checked" : "" ?>>
                                    <label>Ja</label>
                                </p>
                                <p>
                                    <input type="radio" name="behoefte_religieuze_activiteit" <?= !$antwoorden['behoefte_religieuze_activiteit'] ? "checked" : "" ?>>
                                    <label>Nee</label>
                                </p>
                            </div>
                        </div>
                        <div class="question"><p>- Zijn er gebruiken ten aanzien van uw geloofsovertuiging waar rekening mee gehouden moet worden?</p>
                            <div class="checkboxes">
                                <div class="question-answer">
                                    <input id="radio" type="radio" name="gebruiken_tav_geloofsovertuiging" <?= $antwoorden['gebruiken_tav_geloofsovertuiging'] ? "checked" : "" ?>>
                                    <label>Ja</label>
                                    <textarea rows="1" cols="25" id="checkfield" type="text" placeholder="welke?" name="gebruiken_tav_geloofsovertuiging"><?= $antwoorden['gebruiken_tav_geloofsovertuiging'] ? $antwoorden['gebruiken_tav_geloofsovertuiging_welke']  : "" ?></textarea>
                                </div>
                                <p>
                                    <input type="radio" name="gebruiken_tav_geloofsovertuiging" <?= !$antwoorden['gebruiken_tav_geloofsovertuiging'] ? "checked" : "" ?>>
                                    <label>Nee</label>
                                </p>
                            </div>
                        </div>
                        <div class="question"><p>Ja, wanneer?</p><textarea  rows="1" cols="25" type="text" name="gebruiken_tav_geloofsovertuiging_wanneer"><?= $antwoorden['gebruiken_tav_geloofsovertuiging_wanneer'] ? $antwoorden['gebruiken_tav_geloofsovertuiging_wanneer']  : "" ?></textarea></div>
                        <div class="question"><p>Komen uw waarden en normen overeen met maatschappelijke waarden en normen?</p>
                            <div class="checkboxes">
                                <p>    
                                    <input type="radio" name="overeenkomst_waarden_normen" <?= $antwoorden['overeenkomst_waarden_normen'] ? "checked" : "" ?>>
                                    <label>Ja</label>
                                </p>
                                <p>
                                    <input type="radio" name="overeenkomst_waarden_normen" <?= !$antwoorden['overeenkomst_waarden_normen'] ? "checked" : "" ?>>
                                    <label>Nee</label>
                                </p>
                            </div>
                        </div>
                        <div class="question"><p>Wat is uw etnische achtergrond</p><textarea rows="1" cols="25" type="text" name="etnische_achtergrond"><?= $antwoorden['etnische_achtergrond'] ? $antwoorden['etnische_achtergrond'] : "" ?></textarea></div>
                        <div class="question"><p>- Zijn er gebruiken met betrekking tot uw etnische achtergrond waar rekening mee gehouden moet worden?</p>
                            <div class="checkboxes">
                                <div class="question-answer">
                                    <input id="radio" type="radio" name="gebruiken_mbt_etnische_achtergrond" <?= $antwoorden['gebruiken_mbt_etnische_achtergrond'] ? "checked" : "" ?>>
                                    <label>Ja</label>
                                    <textarea  rows="1" cols="25" id="checkfield" type="text" placeholder="welke?" name="gebruiken_mbt_etnische_achtergrond_welke"><?= $antwoorden['gebruiken_mbt_etnische_achtergrond'] ? $antwoorden['gebruiken_mbt_etnische_achtergrond_welke'] : "" ?></textarea>
                                </div>
                                <p>
                                    <input type="radio" name="gebruiken_mbt_etnische_achtergrond" <?= !$antwoorden['gebruiken_mbt_etnische_achtergrond'] ? "checked" : "" ?>>
                                    <label>Nee</label>
                                </p>
                            </div>
                        </div>
                        <div class="question"><p>Ja, wanneer?</p><textarea  rows="1" cols="25" type="text"><?= $antwoorden['gebruiken_mbt_etnische_achtergrond'] ? $antwoorden['gebruiken_mbt_etnische_achtergrond_wanneer'] : "" ?></textarea></div>

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