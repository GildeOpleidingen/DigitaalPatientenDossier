
<?php
session_start();
include '../../Database/DatabaseConnection.php';
include '../../Functions/Functions.php';

$antwoorden = getPatternAnswers($_SESSION['clientId'], 7);

$boolArrayGevoelOpDitMoment = str_split($antwoorden['gevoel_op_dit_moment']) ?? "";
$boolArrayGevoelMomenteel = str_split($antwoorden['gevoel_momenteel']) ?? "";
$boolArrayLichamelijkeEnergie = str_split($antwoorden['lichamelijke_energie']) ?? "";
$boolArrayObservatie = str_split($antwoorden['observatie']) ?? "";

if (isset($_REQUEST['navbutton'])) {
    $clientId = $_GET['id'];
    //TODO: hier actie om data op te slaan in database.
    switch($_REQUEST['navbutton']) {
        case 'next': //action for next here
            header('Location: patroon08.php?id='.$clientId);
            break;
    
        case 'prev': //action for previous here
            header('Location: patroon06.php?id='.$clientId);
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
            <div class="pages">7 Zelfbelevingspatroon</div>
                <div class="form">
                    <div class="questionnaire">
                        <div class="question"><p>Kunt u uzelf, in het kort, beschijven?</p><textarea  rows="1" cols="25" type="text"><?= $antwoorden['zelfbeschrijving'] ?? "" ?></textarea></div>
                        <div class="question"><p>Kunt u voor uzelf opkomen?</p>
                            <div class="checkboxes">
                                <p>    
                                    <input type="radio" name="radio-1" <?= $antwoorden['opkomen_voor_uzelf'] ? "checked" : "" ?>>
                                    <label>Ja</label>
                                </p>
                                <p>
                                    <input type="radio" name="radio-1" <?= !$antwoorden['opkomen_voor_uzelf'] ? "checked" : "" ?>>
                                    <label>Nee</label>
                                </p>
                            </div>
                        </div>
                        <div class="question"><p>Waar blijkt dat uit?</p><textarea  rows="1" cols="25" type="text"></textarea></div>
                        <div class="question"><p>Is uw stemming de laatste tijd veranderd?</p>
                            <div class="checkboxes">
                                <div class="question-answer">
                                    <input id="radio" type="radio" name="radio-2" <?= $antwoorden['verandering_stemming'] ? "checked" : "" ?>>
                                    <label>Ja</label>
                                    <textarea  rows="1" cols="25" id="checkfield" type="text" placeholder="en wel?"></textarea>
                                </div>
                                <p>
                                    <input type="radio" name="radio-2" <?= !$antwoorden['verandering_stemming'] ? "checked" : "" ?>>
                                    <label>Nee</label>
                                </p>
                            </div>
                        </div>
                        <div class="question"><p>- Hoe voelt u zich op dit moment?</p> 
                            <div class="observation">
                                <div class="question"><div class="observe"><input type="checkbox" <?= $boolArrayGevoelOpDitMoment[0] ? "checked" : "" ?>><p>Neerslachtig</p></div></div>
                                <div class="question"><div class="observe"><input type="checkbox" <?= $boolArrayGevoelOpDitMoment[1] ? "checked" : "" ?>><p>Wanhopig</p></div></div>
                                <div class="question"><div class="observe"><input type="checkbox" <?= $boolArrayGevoelOpDitMoment[2] ? "checked" : "" ?>><p>Machteloos</p></div></div>
                                <div class="question"><div class="observe"><input type="checkbox" <?= $boolArrayGevoelOpDitMoment[3] ? "checked" : "" ?>><p>Opgewekt</p></div></div>
                                <div class="question"><div class="observe"><input type="checkbox" <?= $boolArrayGevoelOpDitMoment[4] ? "checked" : "" ?>><p>Somber</p></div></div>
                                <div class="question"><div class="observe"><input type="checkbox" <?= $boolArrayGevoelOpDitMoment[5] ? "checked" : "" ?>><p>Eufoor</p></div></div>
                                <div class="question"><div class="observe"><input type="checkbox" <?= $boolArrayGevoelOpDitMoment[6] ? "checked" : "" ?>><p>Labiel</p></div></div>
                                <div class="question"><div class="observe"><input type="checkbox" <?= $boolArrayGevoelOpDitMoment[7] ? "checked" : "" ?>><p>Gespannen</p></div></div>
                                <div class="question"><div class="observe"><input type="checkbox" <?= $boolArrayGevoelOpDitMoment[8] ? "checked" : "" ?>><p>Verdrietig</p></div></div>
                                <div class="question"><div class="observe"><input type="checkbox" <?= $boolArrayGevoelOpDitMoment[9] ? "checked" : "" ?>><p>Anders, namelijk:</p></div><textarea  rows="1" cols="25" type="text"><?= $antwoorden['anders'] ?? "" ?></textarea></div>
                            </div>
                        </div>
                        <div class="question"><p>- Is er de afgeloen tijd iets veranderd in uw concentratievermogen ten gevolgen van u stemming?</p>
                            <div class="checkboxes">
                                <p>    
                                    <input type="radio" name="radio-3" <?= $antwoorden['verandering_concentratie'] ? "checked" : "" ?>>
                                    <label>Ja</label>
                                </p>
                                <p>
                                    <input type="radio" name="radio-3" <?= !$antwoorden['verandering_concentratie'] ? "checked" : "" ?>>
                                    <label>Nee</label>
                                </p>
                            </div>
                        </div>
                        <div class="question"><p>- Is er de afgeloen tijd iets veranderd in uw denkpatroon ten gevolgen van u stemming?</p>
                            <div class="checkboxes">
                                <p>    
                                    <input type="radio" name="radio-4" <?= $antwoorden['verandering_denkpatroon'] ? "checked" : "" ?>>
                                    <label>Ja</label>
                                </p>
                                <p>
                                    <input type="radio" name="radio-4" <?= !$antwoorden['verandering_denkpatroon'] ? "checked" : "" ?>>
                                    <label>Nee</label>
                                </p>
                            </div>
                        </div>
                        <div class="question"><p>Ervaart u uzelf nu anders dan voorheen?</p>
                            <div class="checkboxes">
                                <p>    
                                    <input type="radio" name="radio-5" <?= $antwoorden['verandering_uiterlijk'] ? "checked" : "" ?>>
                                    <label>Ja</label>
                                </p>
                                <p>
                                    <input type="radio" name="radio-5" <?= !$antwoorden['verandering_uiterlijk'] ? "checked" : "" ?>>
                                    <label>Nee</label>
                                </p>
                            </div>
                        </div>
                        <div class="question"><p>- Zijn er veranderingen in uw uiterlijk en/of mogelijkheden waardoor u zich anders voelt?</p>
                            <div class="checkboxes">
                                <p>    
                                    <input type="radio" name="radio-6" <?= $antwoorden['sensaties'] ? "checked" : "" ?>>
                                    <label>Ja</label>
                                </p>
                                <p>
                                    <input type="radio" name="radio-6" <?= !$antwoorden['sensaties'] ? "checked" : "" ?>>
                                    <label>Nee</label>
                                </p>
                            </div>
                        </div>
                        <div class="question"><p>- Voelt u (lichamelijke) sensaties?</p>
                            <div class="checkboxes">
                                <div class="question-answer">
                                    <input id="radio" type="radio" name="radio-7" <?= $antwoorden['sensaties'] ? "checked" : "" ?>>
                                    <label>Ja</label>
                                    <textarea  rows="1" cols="25" id="checkfield" type="text" placeholder="wat voelt u?"> <?= $antwoorden['sensaties'] ? $antwoorden['sensaties_wat'] : "" ?> </textarea>
                                </div>
                                <p>
                                    <input type="radio" name="radio-7" <?= !$antwoorden['sensaties'] ? "checked" : "" ?>>
                                    <label>Nee</label>
                                </p>
                            </div>
                        </div>
                        <div class="question"><p>- Hoe voelt u zich momenteel?</p> 
                            <div class="observation">
                                <div class="question"><div class="observe"><input type="checkbox" <?= $boolArrayGevoelMomenteel[0] ? "checked" : "" ?>><p>Sterk</p></div></div>
                                <div class="question"><div class="observe"><input type="checkbox" <?= $boolArrayGevoelMomenteel[1] ? "checked" : "" ?>><p>Zwak</p></div></div>
                                <div class="question"><div class="observe"><input type="checkbox" <?= $boolArrayGevoelMomenteel[2] ? "checked" : "" ?>><p>Krachteloos</p></div></div>
                            </div>
                        </div>
                        <div class="question"><p>- Hoe staat het met uw lichamelijke energie?</p> 
                            <div class="observation">
                                <div class="question"><div class="observe"><input type="checkbox" <?= $boolArrayLichamelijkeEnergie[0] ? "checked" : "" ?>><p>Genoeg</p></div></div>
                                <div class="question"><div class="observe"><input type="checkbox" <?= $boolArrayLichamelijkeEnergie[1] ? "checked" : "" ?>><p>Te veel</p></div></div>
                                <div class="question"><div class="observe"><input type="checkbox" <?= $boolArrayLichamelijkeEnergie[2] ? "checked" : "" ?>><p>Te weinig</p></div></div>
                            </div>
                        </div>
                        <div class="question"><p>Zorgt u goed voor u zelf, of vindt u dat het beter kan?</p><textarea  rows="1" cols="25" type="text"></textarea></div>

                        <div class="observation">
                            <h2>Verpleegkundige observatie bij dit patroon</h2>
                            <div class="question"><div class="observe"><input type="checkbox" <?= $boolArrayObservatie[0] ? "checked" : ""?>><p>Lichte angst</p></div></div>
                            <div class="question"><div class="observe"><input type="checkbox" <?= $boolArrayObservatie[1] ? "checked" : ""?>><p>Matige angst</p></div></div>
                            <div class="question"><div class="observe"><input type="checkbox" <?= $boolArrayObservatie[2] ? "checked" : ""?>><p>Hevige (paniek) angst</p></div></div>
                            <div class="question"><div class="observe"><input type="checkbox" <?= $boolArrayObservatie[3] ? "checked" : ""?>><p>Lichte anticiperende angst</p></div></div>
                            <div class="question"><div class="observe"><input type="checkbox" <?= $boolArrayObservatie[4] ? "checked" : ""?>><p>Matige anticiperende angst</p></div></div>
                            <div class="question"><div class="observe"><input type="checkbox" <?= $boolArrayObservatie[5] ? "checked" : ""?>><p>Hevige anticiperende angst</p></div></div>
                            <div class="question"><div class="observe"><input type="checkbox" <?= $boolArrayObservatie[6] ? "checked" : ""?>><p>Vrees</p></div></div>
                            <div class="question"><div class="observe"><input type="checkbox" <?= $boolArrayObservatie[7] ? "checked" : ""?>><p>Reactieve depressie</p></div></div>
                            <div class="question"><div class="observe"><input type="checkbox" <?= $boolArrayObservatie[8] ? "checked" : ""?>><p>Moedeloosheid</p></div></div>
                            <div class="question"><div class="observe"><input type="checkbox" <?= $boolArrayObservatie[9] ? "checked" : ""?>><p>Identiteitsstoornis</p></div></div>
                            <div class="question"><div class="observe"><input type="checkbox" <?= $boolArrayObservatie[10] ? "checked" : "" ?>><p>Lichte machteloosheid</p></div></div>
                            <div class="question"><div class="observe"><input type="checkbox" <?= $boolArrayObservatie[11] ? "checked" : "" ?>><p>Matige machteloosheid</p></div></div>
                            <div class="question"><div class="observe"><input type="checkbox" <?= $boolArrayObservatie[12] ? "checked" : "" ?>><p>Ernstige machteloosheid</p></div></div>
                            <div class="question"><div class="observe"><input type="checkbox" <?= $boolArrayObservatie[13] ? "checked" : "" ?>><p>Geringe zelfachting</p></div></div>
                            <div class="question"><div class="observe"><input type="checkbox" <?= $boolArrayObservatie[14] ? "checked" : "" ?>><p>Chronisch geringe zelfachting</p></div></div>
                            <div class="question"><div class="observe"><input type="checkbox" <?= $boolArrayObservatie[15] ? "checked" : "" ?>><p>Reactief geringe zelfachting</p></div></div>
                            <div class="question"><div class="observe"><input type="checkbox" <?= $boolArrayObservatie[16] ? "checked" : "" ?>><p>Verstoord lichaamsbeeld</p></div></div>
                            <div class="question"><div class="observe"><input type="checkbox" <?= $boolArrayObservatie[17] ? "checked" : "" ?>><p>Hopeloosheid</p></div></div>
                            <div class="question"><div class="observe"><input type="checkbox" <?= $boolArrayObservatie[18] ? "checked" : "" ?>><p>Dreigende zelfverminking (automutilatie)</p></div></div>
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