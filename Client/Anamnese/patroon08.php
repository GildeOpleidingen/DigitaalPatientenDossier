
<?php
session_start();
include '../../Database/DatabaseConnection.php';
include '../../Functions/Functions.php';


$antwoorden = getPatternAnswers($_SESSION['clientId'], 8);

$boolArrayObservatie = str_split($antwoorden['observatie']) ?? "";

if (isset($_REQUEST['navbutton'])) {
    $clientId = $_GET['id'];
    //TODO: hier actie om data op te slaan in database.
    switch($_REQUEST['navbutton']) {
        case 'next': //action for next here
            header('Location: patroon09.php?id='.$clientId);
            break;
    
        case 'prev': //action for previous here
            header('Location: patroon07.php?id='.$clientId);
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
            <div class="pages">8 Rollen- en relatiepatroon</div>
                <div class="form">
                    <div class="questionnaire">
                    <div class="question"><p>Bent u getrouwd/samenwonend?</p>
                            <div class="checkboxes">
                                <p>    
                                    <input type="radio" name="radio-1" <?= $antwoorden['getrouwd_samenwonend'] ? "checked" : "" ?>>
                                    <label>Ja</label>
                                </p>
                                <p>
                                    <input type="radio" name="radio-1" <?= !$antwoorden['getrouwd_samenwonend'] ? "checked" : "" ?>>
                                    <label>Nee</label>
                                </p>
                            </div>
                        </div>
                        <div class="question"><p>- Heeft u kinderen?</p>
                            <div class="checkboxes">
                                <p>    
                                    <input type="radio" name="radio-2" <?= $antwoorden['kinderen'] ? "checked" : "" ?>>
                                    <label>Ja</label>
                                </p>
                                <p>
                                    <input type="radio" name="radio-2" <?= !$antwoorden['kinderen'] ? "checked" : "" ?>>
                                    <label>Nee</label>
                                </p>
                            </div>
                        </div>
                        <div class="question"><p>- Bent u tevreden over uw thuissituatie?</p>
                            <div class="checkboxes">
                                <p>    
                                    <input type="radio" name="radio-3" <?= $antwoorden['tevreden_thuissituatie'] ? "checked" : "" ?>>
                                    <label>Ja</label>
                                </p>
                                <p>
                                    <input type="radio" name="radio-3" <?= !$antwoorden['tevreden_thuissituatie'] ? "checked" : "" ?>>
                                    <label>Nee</label>
                                </p>
                            </div>
                        </div>
                        <div class="question"><p>Heeft u een vrienden-/familiekring waar u steun aan heeft?</p>
                            <div class="checkboxes">
                                <p>    
                                    <input type="radio" name="radio-4" <?= $antwoorden['steun_vrienden_familie'] ? "checked" : "" ?>>
                                    <label>Ja</label>
                                </p>
                                <p>
                                    <input type="radio" name="radio-4" <?= !$antwoorden['steun_vrienden_familie'] ? "checked" : "" ?>>
                                    <label>Nee</label>
                                </p>
                            </div>
                        </div>
                        <div class="question"><p>Wat is uw beroep of huidige bron van inkomsten?</p><textarea  rows="1" cols="25" type="text"><?= $antwoorden['inkomstenbron'] ?? "" ?></textarea></div>
                        <div class="question"><p>- Is er de afgelopen tijd een verandering geweest in uw financiële situatie?</p>
                            <div class="checkboxes">
                                <div class="question-answer">
                                    <input id="radio" type="radio" name="radio-5" <?= $antwoorden['verandering_fin_sit_vroeger'] ? "checked" : "" ?>>
                                    <label>Ja</label>
                                    <textarea  rows="1" cols="25" id="checkfield" type="text" placeholder="welke?"><?= $antwoorden['inkomstenbron'] ?? "" ?></textarea>
                                </div>
                                <p>
                                    <input type="radio" name="radio-5" <?= !$antwoorden['verandering_fin_sit_vroeger'] ? "checked" : "" ?>>
                                    <label>Nee</label>
                                </p>
                            </div>
                        </div>
                        <div class="question"><p>- Verwacht u in de nabije toekomst een verandering in uw financiële situatie?</p>
                            <div class="checkboxes">
                                <div class="question-answer">
                                    <input id="radio" type="radio" name="radio-6" <?= $antwoorden['verandering_fin_sit_toekomst'] ? "checked" : "" ?>>
                                    <label>Ja</label>
                                    <textarea  rows="1" cols="25" id="checkfield" type="text" placeholder="welke?"></textarea>
                                </div>
                                <p>
                                    <input type="radio" name="radio-6" <?= !$antwoorden['verandering_fin_sit_toekomst'] ? "checked" : "" ?>>
                                    <label>Nee</label>
                                </p>
                            </div>
                        </div>
                        <div class="question"><p>Wat is uw opleiding?</p><textarea  rows="1" cols="25" type="text"><?= $antwoorden['opleiding'] ?? "" ?></textarea></div>
                        <div class="question"><p>Is er de laatste tijd verandering gekomen in uw sociale contacten?</p>
                            <div class="checkboxes">
                                <div class="question-answer">
                                    <input id="radio" type="radio" name="radio-7" <?= $antwoorden['verandering_sociale_contacten'] ? "checked" : "" ?>>
                                    <label>Ja</label>
                                    <textarea  rows="1" cols="25" id="checkfield" type="text" placeholder="welke?"><?= $antwoorden['verandering_sociale_contacten_welke'] ?? "" ?></textarea>
                                </div>
                                <p>
                                    <input type="radio" name="radio-7" <?= !$antwoorden['verandering_sociale_contacten'] ? "checked" : "" ?>>
                                    <label>Nee</label>
                                </p>
                            </div>
                        </div>
                        <div class="question"><p>Komt u uit een groot gezin?</p>
                            <div class="checkboxes">
                                <p>    
                                    <input type="radio" name="radio-8" <?= $antwoorden['groot_gezin'] ? "checked" : "" ?>>
                                    <label>Ja</label> 
                                </p>
                                <p>
                                    <input type="radio" name="radio-8" <?= !$antwoorden['groot_gezin'] ? "checked" : "" ?>>
                                    <label>Nee</label>
                                </p>
                            </div>
                        </div>
                        <div class="question"><p>- Wat was u plaats in dat gezin?</p><textarea  rows="1" cols="25" type="text"><?= $antwoorden['plaats_in_gezin'] ?? "" ?></textarea></div>
                        <div class="question"><p>- Hoe verliepen de onderlinge contacten?</p><textarea  rows="1" cols="25" type="text"><?= $antwoorden['onderlinge_contacten_gezin'] ?? "" ?></textarea></div>
                        <div class="question"><p>- Was er sprake van agressie in dat gezin?</p>
                            <div class="checkboxes">
                                <p>    
                                    <input type="radio" name="radio-9" <?= $antwoorden['agressie_gezin'] ? "checked" : "" ?>>
                                    <label>Ja</label>
                                </p>
                                <p>
                                    <input type="radio" name="radio-9" <?= !$antwoorden['agressie_gezin'] ? "checked" : "" ?>>
                                    <label>Nee</label>
                                </p>
                            </div>
                        </div>
                        <div class="question"><p>Bent u lid van verenigingen?</p>
                            <div class="checkboxes">
                                <div class="question-answer">
                                    <input id="radio" type="radio" name="radio-10" <?= $antwoorden['verenigingslid'] ? "checked" : "" ?>>
                                    <label>Ja</label>
                                    <textarea  rows="1" cols="25" id="checkfield" type="text" placeholder="welke?"><?= $antwoorden['vereniging_welke'] ?? "" ?></textarea>
                                </div>
                                <p>
                                    <input type="radio" name="radio-10" <?= !$antwoorden['verenigingslid'] ? "checked" : "" ?>>
                                    <label>Nee</label>
                                </p>
                            </div>
                        </div>
                        <div class="question"><p>- Hoe verliepen de contacten met derden (collega's, kennissen, verenigingsgenoten)?</p><textarea  rows="1" cols="25" type="text"><?= $antwoorden['contact_met_derden'] ?? "" ?></textarea></div>
                        <div class="question"><p>Heeft u de laatst tijd een verlies geleden (werk, personen, enzovoort)?</p>
                            <div class="checkboxes">
                                <div class="question-answer">
                                    <input id="radio" type="radio" name="radio-11" <?= $antwoorden['verlies_geleden'] ? "checked" : "" ?>>
                                    <label>Ja</label>
                                    <textarea  rows="1" cols="25" id="checkfield" type="text" placeholder="en wel?"><?= $antwoorden['verlies_geleden_welke'] ?? "" ?></textarea>
                                </div>
                                <p>
                                    <input type="radio" name="radio-11" <?= !$antwoorden['verlies_geleden'] ? "checked" : "" ?>>
                                    <label>Nee</label>
                                </p>
                            </div>
                        </div>

                        <div class="observation">
                            <h2>Verpleegkundige observatie bij dit patroon</h2>
                            <div class="question"><div class="observe"><input type="checkbox" <?= $boolArrayObservatie[0] ? "checked" : ""?>><p>Verstoorde verbale communicatie (afwijkende groei en ontwikkeling in communicatieve vaardigheden)</p></div></div>
                            <div class="question"><div class="observe"><input type="checkbox" <?= $boolArrayObservatie[1] ? "checked" : ""?>><p>Anticiperende rouw</p></div></div>
                            <div class="question"><div class="observe"><input type="checkbox" <?= $boolArrayObservatie[2] ? "checked" : ""?>><p>Disfunctionele rouw</p></div></div>
                            <div class="question"><div class="observe"><input type="checkbox" <?= $boolArrayObservatie[3] ? "checked" : ""?>><p>Gewijzigde gezinsprocessen</p></div></div>
                            <div class="question"><div class="observe"><input type="checkbox" <?= $boolArrayObservatie[4] ? "checked" : ""?>><p>(Dreigend) ouderschapstekort</p></div></div>
                            <div class="question"><div class="observe"><input type="checkbox" <?= $boolArrayObservatie[5] ? "checked" : ""?>><p>Ouderrolconflict</p></div></div>
                            <div class="question"><div class="observe"><input type="checkbox" <?= $boolArrayObservatie[6] ? "checked" : ""?>><p>Inadequate sociale interacties</p></div></div>
                            <div class="question"><div class="observe"><input type="checkbox" <?= $boolArrayObservatie[7] ? "checked" : ""?>><p>Afwijkende groei en ontikkeling in sociale vaardigheden</p></div></div>
                            <div class="question"><div class="observe"><input type="checkbox" <?= $boolArrayObservatie[8] ? "checked" : ""?>><p>Sociaal isolement</p></div></div>
                            <div class="question"><div class="observe"><input type="checkbox" <?= $boolArrayObservatie[9] ? "checked" : ""?>><p>Verstoorde rolvervulling</p></div></div>
                            <div class="question"><div class="observe"><input type="checkbox" <?= $boolArrayObservatie[10] ? "checked" : ""?>><p>Onopgelost onafhankelijkheids-/afhankelijkheidsconflict</p></div></div>
                            <div class="question"><div class="observe"><input type="checkbox" <?= $boolArrayObservatie[11] ? "checked" : ""?>><p>Sociale afwijzing</p></div></div>
                            <div class="question"><div class="observe"><input type="checkbox" <?= $boolArrayObservatie[12] ? "checked" : ""?>><p>(Dreigende) overbelasting van de mantelzorg)</p></div></div>
                            <div class="question"><div class="observe"><input type="checkbox" <?= $boolArrayObservatie[13] ? "checked" : ""?>><p>Mantelzorgtekort</p></div></div>
                            <div class="question"><div class="observe"><input type="checkbox" <?= $boolArrayObservatie[14] ? "checked" : ""?>><p>Dreigend geweld:</p></div></div>
                            <div class="question"><div class="observe"><input type="checkbox" <?= $boolArrayObservatie[15] ? "checked" : ""?>><p>gericht op andere</p></div></div>
                            <div class="question"><div class="observe"><input type="checkbox" <?= $boolArrayObservatie[16] ? "checked" : ""?>><p>gericht op voorwerpen (meubilair, enzovoort)</p></div></div>
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