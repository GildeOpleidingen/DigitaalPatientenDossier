
<?php
session_start();
include '../../Database/DatabaseConnection.php';
include '../../Functions/Functions.php';

$antwoorden = getPatternAnswers($_SESSION['clientId'], 6);

$boolArrayObservatie = str_split($antwoorden['observatie']);

if (isset($_REQUEST['navbutton'])) {
    $clientId = $_GET['id'];
    //TODO: hier actie om data op te slaan in database.
    switch($_REQUEST['navbutton']) {
        case 'next': //action for next here
            header('Location: patroon07.php?id='.$clientId);
            break;
    
        case 'prev': //action for previous here
            header('Location: patroon05.php?id='.$clientId);
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
    <div class="main-content">
        <div class="content">
            <div class="form-content">
            <div class="pages">6 Cognitie- en waarnemingspatroon</div>
                <div class="form">
                    <div class="questionnaire">
                        <div class="question"><p>Heeft u moeite met horen?</p>
                            <div class="checkboxes">
                                <p>    
                                    <input type="radio" name="moeilijk_horen" <?= $antwoorden['moeilijk_horen'] ? "checked" : "" ?>>
                                    <label>Ja</label>
                                </p>
                                <p>
                                    <input type="radio" name="moeilijk_horen" <?= !$antwoorden['moeilijk_horen'] ? "checked" : "" ?>>
                                    <label>Nee</label>
                                </p>
                            </div>
                        </div>
                        <div class="question"><p>- Hoort u stemmen die op dat moment door personen in uw omgeving niet gehoord (kunnen) worden?</p>
                            <div class="checkboxes">
                                <div class="question-answer">
                                    <input id="radio" type="radio" name="hoort_stemmen" <?= $antwoorden['hoort_stemmen'] ? "checked" : ""?>>
                                    <label>Ja</label>
                                    <textarea  rows="1" cols="25" id="checkfield" type="text" placeholder="wat hoort u?"> <?= $antwoorden['hoort_stemmen_wat'] ?? "" ?> </textarea>
                                </div>
                                <p>
                                    <input type="radio" name="hoort_stemmen" <?= !$antwoorden['hoort_stemmen'] ? "checked" : "" ?>>
                                    <label>Nee</label>
                                </p>
                            </div>
                        </div>
                        <div class="question"><p>Heeft u moeite met zien?</p>
                            <div class="checkboxes">
                                <p>    
                                    <input type="radio" name="moeite_met_zien" <?= $antwoorden['moeite_met_zien'] ? "checked" : "" ?>>
                                    <label>Ja</label>
                                </p>
                                <p>
                                    <input type="radio" name="moeite_met_zien" <?= !$antwoorden['moeite_met_zien'] ? "checked" : "" ?>>
                                    <label>Nee</label>
                                </p>
                            </div>
                        </div>
                        <div class="question"><p>- Ziet u personen, dieren, objecten die op dat moment door personen in uw omgeving niet gezien (kunnen) worden?</p>
                            <div class="checkboxes">
                                <div class="question-answer">
                                    <input id="radio" type="radio" name="ziet_dingen" <?= $antwoorden['ziet_dingen'] ? "checked" : "" ?>>
                                    <label>Ja</label>
                                    <textarea  rows="1" cols="25" id="checkfield" type="text" placeholder="wat ziet u?"><?= $antwoorden['ziet_dingen_wat'] ?? "" ?></textarea>
                                </div>
                                <p>
                                    <input type="radio" name="ziet_dingen" <?= !$antwoorden['ziet_dingen'] ? "checked" : "" ?>>
                                    <label>Nee</label>
                                </p>
                            </div>
                        </div>
                        <div class="question"><p>Ruikt u iets dat op dat moment door personen in uw omgeving niet geroken (kan) worden?</p>
                            <div class="checkboxes">
                                <div class="question-answer">
                                    <input id="radio" type="radio" name="ruikt_iets_onverklaarbaar" <?= $antwoorden['ruikt_iets_onverklaarbaar'] ? "checked" : "" ?>>
                                    <label>Ja</label>
                                    <textarea  rows="1" cols="25" id="checkfield" type="text" placeholder="wat ruikt u?"><?= $antwoorden['ruikt_iets_onverklaarbaar_wat'] ?? "" ?></textarea>
                                </div>
                                <p>
                                    <input type="radio" name="ruikt_iets_onverklaarbaar" <?= !$antwoorden['ruikt_iets_onverklaarbaar'] ? "checked" : "" ?>>
                                    <label>Nee</label>
                                </p>
                            </div>
                        </div>
                        <div class="question"><p>Zijn er de afgelopen tijd veranderingen opgetreden in uw denken?</p>
                            <div class="checkboxes">
                                <p>    
                                    <input type="radio" name="verandering_denken" <?= $antwoorden['verandering_denken'] ? "checked" : "" ?>>
                                    <label>Ja</label>
                                </p>
                                <p>
                                    <input type="radio" name="verandering_denken" <?= !$antwoorden['verandering_denken'] ? "checked" : "" ?>>
                                    <label>Nee</label>
                                </p>
                            </div>
                        </div>
                        <div class="question"><p>Heeft u moeite met spreken?</p>
                            <div class="checkboxes">
                                <p>    
                                    <input type="radio" name="moeite_spreken" <?= $antwoorden['moeite_spreken'] ? "checked" : "" ?>>
                                    <label>Ja</label>
                                </p>
                                <p>
                                    <input type="radio" name="moeite_spreken" <?= !$antwoorden['moeite_spreken'] ? "checked" : "" ?>>
                                    <label>Nee</label>
                                </p>
                            </div>
                        </div>
                        <div class="question"><p>- Welke taal spreekt u thuis?</p><textarea  rows="1" cols="25" type="text"><?= $antwoorden['taal_thuis'] ?? "" ?></textarea></div>
                        <div class="question"><p>Zijn er de afgelopen tijd veranderingen opgetreden in uw concentratievermogen?</p>
                            <div class="checkboxes">
                                <p>    
                                    <input type="radio" name="verandering_concentratievermogen" <?= $antwoorden['verandering_concentratievermogen'] ? "checked" : "" ?>>
                                    <label>Ja</label>
                                </p>
                                <p>
                                    <input type="radio" name="verandering_concentratievermogen" <?= !$antwoorden['verandering_concentratievermogen'] ? "checked" : "" ?>>
                                    <label>Nee</label>
                                </p>
                            </div>
                        </div>
                        <div class="question"><p>Kunt u moeilijker dagelijkse beslissingen nemen?</p>
                            <div class="checkboxes">
                                <p>    
                                    <input type="radio" name="moeilijker_beslissen" <?= $antwoorden['moeilijker_beslissen'] ? "checked" : "" ?>>
                                    <label>Ja</label>
                                </p>
                                <p>
                                    <input type="radio" name="moeilijker_beslissen" <?= !$antwoorden['moeilijker_beslissen'] ? "checked" : "" ?>>
                                    <label>Nee</label>
                                </p>
                            </div>
                        </div>
                        <div class="question"><p>Zijn er de afgelopen tijd veranderingen opgetreden in uw geheugen?</p>
                            <div class="checkboxes">
                                <p>    
                                    <input type="radio" name="verandering_geheugen" <?= $antwoorden['verandering_geheugen'] ? "checked" : "" ?>>
                                    <label>Ja</label>
                                </p>
                                <p>
                                    <input type="radio" name="verandering_geheugen" <?= !$antwoorden['verandering_geheugen'] ? "checked" : "" ?>>
                                    <label>Nee</label>
                                </p>
                            </div>
                        </div>
                        <div class="question"><p>Zijn er de afgelopen tijd veranderingen opgetreden in uw oriëntatie?</p>
                            <div class="checkboxes">
                                <p>    
                                    <input type="radio" name="verandering_orientatie" <?= $antwoorden['verandering_orientatie'] ? "checked" : "" ?>>
                                    <label>Ja</label>
                                </p>
                                <p>
                                    <input type="radio" name="verandering_orientatie" <?= !$antwoorden['verandering_orientatie'] ? "checked" : "" ?>>
                                    <label>Nee</label>
                                </p>
                            </div>
                        </div>
                        <div class="question"><p>Gebruikt u medicatie die uw oriëntatie, reactievermogen of denken beïnvloeden?</p>
                            <div class="checkboxes">
                                <div class="question-answer">
                                    <input id="radio" type="radio" name="invloed_medicatie" <?= $antwoorden['invloed_medicatie'] ? "checked" : "" ?>>
                                    <label>Ja</label>
                                    <textarea  rows="1" cols="25" id="checkfield" type="text" placeholder="welke?" ><?= $antwoorden['invloed_medicatie_welke'] ?? "" ?></textarea>
                                </div>
                                <p>
                                    <input type="radio" name="invloed_medicatie" <?= !$antwoorden['invloed_medicatie'] ? "checked" : "" ?>>
                                    <label>Nee</label>
                                </p>
                            </div>
                        </div>
                        <div class="question"><p>Gebruikt u verdovende/stimulerende middelen?</p>
                            <div class="checkboxes">
                                <div class="question-answer">
                                    <input id="radio" type="radio" name="gebruikt_middelen" <?= $antwoorden['gebruikt_middelen'] ? "checked" : "" ?>>
                                    <label>Ja</label>
                                    <div id="checkfield">
                                        <div class="question"><div class="observe"><input type="checkbox" <?= $antwoorden['gebruikt_middelen_softdrugs'] ? "checked" : "" ?> name="gebruikt_middelen_softdrugs"><p>Softdrugs</p></div><textarea  rows="1" cols="25" type="text"><?= $antwoorden['gebruikt_middelen_softdrugs_welke'] ?? ""?></textarea></div>
                                        <div class="question"><div class="observe"><input type="checkbox" <?= $antwoorden['gebruikt_middelen_harddrugs'] ? "checked" : "" ?> name="gebruikt_middelen_harddrugs"><p>Harddrugs</p></div><textarea  rows="1" cols="25" type="text"><?= $antwoorden['gebruikt_middelen_harddrugs_welke'] ?? ""?></textarea></div>
                                        <div class="question"><div class="observe"><input type="checkbox" <?= $antwoorden['gebruikt_middelen_alcohol'] ? "checked" : "" ?> name="gebruikt_middelen_alcohol"><p>Alcohol</p></div><textarea  rows="1" cols="25" type="text"><?= $antwoorden['gebruikt_middelen_alcohol_welke'] ?? ""?></textarea></div>
                                        <div class="question"><div class="observe"><input type="checkbox" <?= $antwoorden['gebruikt_middelen_anders'] ? "checked" : "" ?> name="gebruikt_middelen_anders"><p>Anders, namelijk:</p></div><textarea  rows="1" cols="25" type="text"><?= $antwoorden['gebruikt_middelen_anders_welke'] ?? ""?></textarea></div>
                                    </div>
                                </div>
                                <p>
                                    <input type="radio" name="gebruikt_middelen" <?= !$antwoorden['gebruikt_middelen'] ? "checked" : "" ?>>
                                    <label>Nee</label>
                                </p>
                            </div>
                        </div>
                        <div class="question"><p>Heeft u pijnklachten?</p>
                            <div class="checkboxes">
                                <p>    
                                    <input type="radio" name="pijnklachten" <?= $antwoorden['pijnklachten'] ? "checked" : "" ?>>
                                    <label>Ja</label>
                                </p>
                                <p>
                                    <input type="radio" name="pijnklachten" <?= !$antwoorden['pijnklachten'] ? "checked" : "" ?>>
                                    <label>Nee</label>
                                </p>
                            </div>
                        </div>
                        <div class="question"><p>- Waar, wanneer, soort pijn?</p><textarea  rows="1" cols="25" type="text"><?= $antwoorden['pijnklachten_waar_wanneer_soort'] ?? "" ?></textarea></div>
                        <div class="question"><p>- Wat doet u doorgaans tegen de pijn?</p><textarea  rows="1" cols="25" type="text"><?= $antwoorden['pijnklachten_tegengaan_pijn'] ?? "" ?></textarea></div>
                        <div class="question"><p>- Wat doet u om pijn/ongemak zoveel mogelijk te voorkomen?</p><textarea  rows="1" cols="25" type="text"><?= $antwoorden['pijnklachten_preventie'] ?? "" ?></textarea></div>
                        
                        <div class="observation">
                            <h2>Verpleegkundige observatie bij dit patroon</h2>
                            <div class="question"><div class="observe"><input type="checkbox" <?= $boolArrayObservatie[0] ? "checked" : "" ?>><p>Wijziging in de waarneming</p></div></div>
                            <div class="question"><div class="observe"><input type="checkbox" <?= $boolArrayObservatie[1] ? "checked" : "" ?>><p>Verstoord denken</p></div></div>
                            <div class="question"><div class="observe"><input type="checkbox" <?= $boolArrayObservatie[2] ? "checked" : "" ?>><p>Kennistekort</p></div></div>
                            <div class="question"><div class="observe"><input type="checkbox" <?= $boolArrayObservatie[3] ? "checked" : "" ?>><p>Dreigend cognitietekort</p></div></div>
                            <div class="question"><div class="observe"><input type="checkbox" <?= $boolArrayObservatie[4] ? "checked" : "" ?>><p>Beslisconflict</p></div></div>
                            <div class="question"><div class="observe"><input type="checkbox" <?= $boolArrayObservatie[5] ? "checked" : "" ?>><p>Achterdocht</p></div></div>
                            <div class="question"><div class="observe"><input type="checkbox" <?= $boolArrayObservatie[6] ? "checked" : "" ?>><p>Acute verwardheid</p></div></div>
                            <div class="question"><div class="observe"><input type="checkbox" <?= $boolArrayObservatie[7] ? "checked" : "" ?>><p>Pijn (specificeer type en locatie)</p></div></div>
                            <div class="question"><div class="observe"><input type="checkbox" <?= $boolArrayObservatie[8] ? "checked" : "" ?>><p>Chronische pijn (specificeer type en locatie)</p></div></div>
                            <div class="question"><div class="observe"><input type="checkbox" <?= $boolArrayObservatie[9] ? "checked" : "" ?>><p>Middelenmisbruik:</p></div></div>
                            <div class="question"><div class="observe"><input type="checkbox" <?= $boolArrayObservatie[10] ? "checked" : "" ?>><p>Alcohol</p></div></div>
                            <div class="question"><div class="observe"><input type="checkbox" <?= $boolArrayObservatie[11] ? "checked" : "" ?>><p>Drugs</p></div></div>
                        </div>
                    </div>
                </div>
                <div class="submit">
                    <button name="navbutton" type="submit" value="prev">< Vorige</button>
                    <button name="navbutton" type="submit" value="next">Volgende ></button>
                </div>
            </div>
        </div>
        </form>
    </div>
    </div>

</body>
</html>