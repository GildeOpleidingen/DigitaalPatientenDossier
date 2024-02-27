
<?php
session_start();
include '../../Database/DatabaseConnection.php';
include '../../Functions/Functions.php';

$clientId = $_GET['id'];

$antwoorden = getPatternAnswers($clientId, 10);

if(isset($antwoorden['reactie_spanningen'])) {
    $boolArrayReacties = str_split($antwoorden['reactie_spanningen']);
}
if(isset($antwoorden['observatie'])) {
    $boolArrayObservatie = str_split($antwoorden['observatie']);
}
print_r($antwoorden);

if (isset($_REQUEST['navbutton'])) {
    //TODO: hier actie om data op te slaan in database.
    switch($_REQUEST['navbutton']) {
        case 'next': //action for next here
            header('Location: patroon11.php?id='.$clientId);
            break;
    
        case 'prev': //action for previous here
            header('Location: patroon09.php?id='.$clientId);
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
            <div class="pages">10 Stressverwerkingspatroon (probleemhantering)</div>
                <div class="form">
                    <div class="questionnaire">
                        <div class="question"><p>Hoe reageert u gewoonlijk op situaties die spanningen oproepen?</p> 
                            <div class="observation">
                                <div class="question"><div class="observe"><input type="checkbox" <?= $boolArrayReacties[0] ? "checked" : "" ?>><p>Zoveel mogelijk vermijden</p></div></div>
                                <div class="question"><div class="observe"><input type="checkbox" <?= $boolArrayReacties[1] ? "checked" : "" ?>><p>Drugs gebruiken</p></div></div>
                                <div class="question"><div class="observe"><input type="checkbox" <?= $boolArrayReacties[2] ? "checked" : "" ?>><p>Ontwikkeling van lichamelijke symptomen</p></div></div>
                                <div class="question"><div class="observe"><input type="checkbox" <?= $boolArrayReacties[3] ? "checked" : "" ?>><p>Medicatie</p></div></div>
                                <div class="question"><div class="observe"><input type="checkbox" <?= $boolArrayReacties[4] ? "checked" : "" ?>><p>Meer/minder eten</p></div></div>
                                <div class="question"><div class="observe"><input type="checkbox" <?= $boolArrayReacties[5] ? "checked" : "" ?>><p>Agressie</p></div></div>
                                <div class="question"><div class="observe"><input type="checkbox" <?= $boolArrayReacties[6] ? "checked" : "" ?>><p>Praten met anderen</p></div></div>
                                <div class="question"><div class="observe"><input type="checkbox" <?= $boolArrayReacties[7] ? "checked" : "" ?>><p>Alcohol drinken</p></div></div>
                                <div class="question"><div class="observe"><input type="checkbox" <?= $boolArrayReacties[8] ? "checked" : "" ?>><p>Houd mijn gevoelens voor me</p></div></div>
                                <div class="question"><div class="observe"><input type="checkbox" <?= $boolArrayReacties[9] ? "checked" : "" ?>><p>Slapen/terugtrekken</p></div></div>
                                <div class="question"><div class="observe"><input type="checkbox" <?= $boolArrayReacties[10] ? "checked" : "" ?>><p>Vertrouwen op religie</p></div></div>
                                <div class="question"><div class="observe"><input type="checkbox" <?= $boolArrayReacties[11] ? "checked" : "" ?>><p>Zo goed mogelijk zelf oplossen</p></div></div>
                                <div class="question"><div class="observe"><input type="checkbox" <?= $boolArrayReacties[12] ? "checked" : "" ?>><p>Anders, namelijk:</p></div><textarea  rows="1" cols="25" type="text"></textarea></div>
                            </div>
                        </div>
                        <div class="question"><p>Probeert u spanningsvolle situaties zo goed mogelijk te voorkomen?</p>
                            <div class="checkboxes">
                                <div class="question-answer">
                                    <input id="radio" type="radio" name="radio-1" <?= $antwoorden['spanningsvolle_situaties_voorkomen'] ? "checked" : "" ?>>
                                    <label>Ja</label>
                                    <textarea  rows="1" cols="25" id="checkfield" type="text" placeholder="en wel?"><?= $antwoorden['spanningsvolle_situaties_voorkomen_hoe'] ?? "" ?></textarea>
                                </div>
                                <p>
                                    <input type="radio" name="radio-1" <?= !$antwoorden['spanningsvolle_situaties_voorkomen'] ? "checked" : "" ?>>
                                    <label>Nee</label>
                                </p>
                            </div>
                        </div>
                        <div class="question"><p>- Probeert u spanningsvolle situaties zo goed mogelijk op te lossen?</p>
                            <div class="checkboxes">
                                <div class="question-answer">
                                    <input id="radio" type="radio" name="radio-2" <?= $antwoorden['spanningsvolle_situaties_oplossen'] ? "checked" : "" ?>>
                                    <label>Ja</label>
                                    <textarea  rows="1" cols="25" id="checkfield" type="text" placeholder="en wel?"><?= $antwoorden['spanningsvolle_situaties_oplossen_hoe'] ?? ""?></textarea>
                                </div>
                                <p>
                                    <input type="radio" name="radio-2" <?= !$antwoorden['spanningsvolle_situaties_oplossen'] ? "checked" : "" ?>>
                                    <label>Nee</label>
                                </p>
                            </div>
                        </div>
                        <div class="question"><p>Zijn er omstandigheden waarbij u in de war raakt?</p>
                            <div class="checkboxes">
                                <div class="question-answer">
                                    <input id="radio" type="radio" name="radio-3" <?= $antwoorden['omstandigheden_in_war_raken'] ? "checked" : "" ?>>
                                    <label>Ja</label>
                                    <textarea  rows="1" cols="25" id="checkfield" type="text" placeholder="welke?"><?= $antwoorden['omstandigheden_in_war_raken_welke'] ?? ""?></textarea>
                                </div>
                                <p>
                                    <input type="radio" name="radio-3" <?= !$antwoorden['omstandigheden_in_war_raken'] ? "checked" : "" ?>>
                                    <label>Nee</label>
                                </p>
                            </div>
                        </div>
                        <div class="question"><p>- Bent u wel eens angstig of in paniek?</p>
                            <div class="checkboxes">
                                <div class="question-answer">
                                    <input id="radio" type="radio" name="radio-4" <?= $antwoorden['angsig_paniek'] ? "checked" : "" ?>>
                                    <label>Ja</label>
                                    <textarea  rows="1" cols="25" id="checkfield" type="text" placeholder="wat doet u dan?"><?= $antwoorden['angsig_paniek_actie'] ?? ""?></textarea>
                                </div>
                                <p>
                                    <input type="radio" name="radio-4" <?= !$antwoorden['angsig_paniek'] ? "checked" : "" ?>>
                                    <label>Nee</label>
                                </p>
                            </div>
                        </div>
                        <div class="question"><p>- Weet u een dergelijke situatie te vookomen?</p>
                            <div class="checkboxes">
                                <p>    
                                    <input type="radio" name="radio-5" <?= $antwoorden['angsig_paniek_lukt_voorkomen'] ? "checked" : "" ?>>
                                    <label>Ja</label>
                                </p>
                                <p>
                                    <input type="radio" name="radio-5" <?= !$antwoorden['angsig_paniek_lukt_voorkomen'] ? "checked" : "" ?>>
                                    <label>Nee</label>
                                </p>
                            </div>
                        </div>
                        <div class="question"><p>Zijn er wel eens momenten dat u niet verder wilt leven?</p>
                            <div class="checkboxes">
                                <p>    
                                    <input type="radio" name="radio-6" <?= $antwoorden['suicidaal'] ? "checked" : "" ?>>
                                    <label>Ja</label>
                                </p>
                                <p>
                                    <input type="radio" name="radio-6" <?= !$antwoorden['suicidaal'] ? "checked" : "" ?>>
                                    <label>Nee</label>
                                </p>
                            </div>
                        </div>
                        <div class="question"><p>- Zo ja, ook op dit moment?</p>
                            <div class="checkboxes">
                                <p>    
                                    <input type="radio" name="radio-7" <?= $antwoorden['suicidaal_momenteel'] ? "checked" : "" ?>>
                                    <label>Ja</label>
                                </p>
                                <p>
                                    <input type="radio" name="radio-7" <?= !$antwoorden['suicidaal_momenteel'] ? "checked" : "" ?>>
                                    <label>Nee</label>
                                </p>
                            </div>
                        </div>
                        <div class="question"><p>Bent u wel eens agressief?</p>
                            <div class="checkboxes">
                                <p>    
                                    <input type="radio" name="radio-8" <?= $antwoorden['agressiief'] ? "checked" : "" ?>>
                                    <label>Ja</label>
                                </p>
                                <p>
                                    <input type="radio" name="radio-8" <?= !$antwoorden['agressiief'] ? "checked" : "" ?>>
                                    <label>Nee</label>
                                </p>
                            </div>
                        </div>
                        <div class="question"><p>- Voelt u een dreiging om u zelf of anderen iets aan te doen?</p>
                            <div class="checkboxes">
                                <p>    
                                    <input type="radio" name="radio-9" <?= $antwoorden['anderen_iets_aan_willen_doen'] ? "checked" : "" ?>>
                                    <label>Ja</label>
                                </p>
                                <p>
                                    <input type="radio" name="radio-9" <?= !$antwoorden['anderen_iets_aan_willen_doen'] ? "checked" : "" ?>>
                                    <label>Nee</label>
                                </p>
                            </div>
                        </div>
                        <div class="question"><p>- Neemt u maatregelen om de veiligheid van u zelf en anderen te waarborgen?</p>
                            <div class="checkboxes">
                                <div class="question-answer">
                                    <input id="radio" type="radio" name="radio-10" <?= $antwoorden['maatregelen_veiligheid'] ? "checked" : "" ?>>
                                    <label>Ja</label>
                                    <textarea  rows="1" cols="25" id="checkfield" type="text" placeholder="door?"><?= $antwoorden['maatregelen_veiligheid_door'] ?? ""?></textarea>
                                </div>
                                <p>
                                    <input type="radio" name="radio-10" <?= $antwoorden['maatregelen_veiligheid'] ? "checked" : "" ?>>
                                    <label>Nee</label>
                                </p>
                            </div>
                        </div>
                        <div class="question"><p>Heeft u moeite met het uiten van gevoelens c.q. problemen?</p>
                            <div class="checkboxes">
                                <p>    
                                    <input type="radio" name="radio-11" <?= $antwoorden['moeite_uiten_gevoelens'] ? "checked" : "" ?>>
                                    <label>Ja</label>
                                </p>
                                <p>
                                    <input type="radio" name="radio-11" <?= !$antwoorden['moeite_uiten_gevoelens'] ? "checked" : "" ?>>
                                    <label>Nee</label>
                                </p>
                            </div>
                        </div>
                        <div class="question"><p>- Met wie bespreekt u uw gevoelens c.q. problemen?</p><textarea  rows="1" cols="25" type="text"><?= $antwoorden['bespreken_gevoelens_met'] ?? ""?></textarea></div>

                        <div class="observation">
                            <h2>Verpleegkundige observatie bij dit patroon</h2>
                            <div class="question"><div class="observe"><input type="checkbox" <?= $boolArrayObservatie[0] ? "checked" : "" ?>><p>Defensieve coping</p></div></div>
                            <div class="question"><div class="observe"><input type="checkbox" <?= $boolArrayObservatie[1] ? "checked" : "" ?>><p>Probleemvermijding</p></div></div>
                            <div class="question"><div class="observe"><input type="checkbox" <?= $boolArrayObservatie[2] ? "checked" : "" ?>><p>Ineffectieve coping</p></div></div>
                            <div class="question"><div class="observe"><input type="checkbox" <?= $boolArrayObservatie[3] ? "checked" : "" ?>><p>Ineffectieve ontkenning</p></div></div>
                            <div class="question"><div class="observe"><input type="checkbox" <?= $boolArrayObservatie[4] ? "checked" : "" ?>><p>Posttraumatische reactie</p></div></div>
                            <div class="question"><div class="observe"><input type="checkbox" <?= $boolArrayObservatie[5] ? "checked" : "" ?>><p>Verminderd aanpassingsvermogen</p></div></div>
                            <div class="question"><div class="observe"><input type="checkbox" <?= $boolArrayObservatie[6] ? "checked" : "" ?>><p>Gezinscoping: ontplooiingsmogelijkheden</p></div></div>
                            <div class="question"><div class="observe"><input type="checkbox" <?= $boolArrayObservatie[7] ? "checked" : "" ?>><p>Bedreigde gezinscoping</p></div></div>
                            <div class="question"><div class="observe"><input type="checkbox" <?= $boolArrayObservatie[8] ? "checked" : "" ?>><p>Gebrekkige gezinscoping</p></div></div>
                            <div class="question"><div class="observe"><input type="checkbox" <?= $boolArrayObservatie[9] ? "checked" : "" ?>><p>Dreiging van suïcidaliteit</p></div></div>
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