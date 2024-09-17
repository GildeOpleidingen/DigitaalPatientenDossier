
<?php
session_start();
include '../../database/DatabaseConnection.php';
include_once '../../classes/Main.php';
$Main = new Main();

$antwoorden = $Main->getPatternAnswers($_SESSION['clientId'], 10);

$boolArrayReacties = str_split($antwoorden['reactie_spanningen']);
$boolArrayObservatie = str_split($antwoorden['observatie']);

if (isset($_REQUEST['navbutton'])) {
    //TODO: hier actie om data op te slaan in database.
    switch($_REQUEST['navbutton']) {
        case 'next': //action for next here
            header('Location: patroon11.php');
            break;
    
        case 'prev': //action for previous here
            header('Location: patroon09.php');
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
    <link rel="stylesheet" href="../../assets/css/bootstrap.min.css">
    <title>Anamnese</title>
</head>
<body>
    <form action="" method="post">
    <div class="main">
        <?php
        include '../../includes/n-header.php';
        ?>
        <?php
        include '../../includes/n-sidebar.php';
        ?>
        <div class="content mt-3">
            <div class="form-content">
            <div class="pages">10. Stressverwerkingspatroon (probleemhantering)</div>
                <div class="form">
                    <div class="questionnaire">
                        <div class="question"><p>Hoe reageert u gewoonlijk op situaties die spanningen oproepen?</p> 
                            <div class="observation">
                                <div class="question"><div class="observe"><input type="checkbox" <?= isset($boolArrayReacties[0]) ? "checked" : "" ?> name="reactie1"><p>Zoveel mogelijk vermijden</p></div></div>
                                <div class="question"><div class="observe"><input type="checkbox" <?= isset($boolArrayReacties[1]) ? "checked" : "" ?> name="reactie2"><p>Drugs gebruiken</p></div></div>
                                <div class="question"><div class="observe"><input type="checkbox" <?= isset($boolArrayReacties[2]) ? "checked" : "" ?> name="reactie3"><p>Ontwikkeling van lichamelijke symptomen</p></div></div>
                                <div class="question"><div class="observe"><input type="checkbox" <?= isset($boolArrayReacties[3]) ? "checked" : "" ?> name="reactie4"><p>Medicatie</p></div></div>
                                <div class="question"><div class="observe"><input type="checkbox" <?= isset($boolArrayReacties[4]) ? "checked" : "" ?> name="reactie5"><p>Meer/minder eten</p></div></div>
                                <div class="question"><div class="observe"><input type="checkbox" <?= isset($boolArrayReacties[5]) ? "checked" : "" ?> name="reactie6"><p>Agressie</p></div></div>
                                <div class="question"><div class="observe"><input type="checkbox" <?= isset($boolArrayReacties[6]) ? "checked" : "" ?> name="reactie7"><p>Praten met anderen</p></div></div>
                                <div class="question"><div class="observe"><input type="checkbox" <?= isset($boolArrayReacties[7]) ? "checked" : "" ?> name="reactie8"><p>Alcohol drinken</p></div></div>
                                <div class="question"><div class="observe"><input type="checkbox" <?= isset($boolArrayReacties[8]) ? "checked" : "" ?> name="reactie9"><p>Houd mijn gevoelens voor me</p></div></div>
                                <div class="question"><div class="observe"><input type="checkbox" <?= isset($boolArrayReacties[9]) ? "checked" : "" ?> name="reactie10"><p>Slapen/terugtrekken</p></div></div>
                                <div class="question"><div class="observe"><input type="checkbox" <?= isset($boolArrayReacties[10]) ? "checked" : "" ?> name="reactie11"><p>Vertrouwen op religie</p></div></div>
                                <div class="question"><div class="observe"><input type="checkbox" <?= isset($boolArrayReacties[11]) ? "checked" : "" ?> name="reactie12"><p>Zo goed mogelijk zelf oplossen</p></div></div>
                                <div class="question"><div class="observe"><input type="checkbox" <?= isset($boolArrayReacties[12]) ? "checked" : "" ?> name="reactie13"><p>Anders, namelijk:</p></div><textarea  rows="1" cols="25" type="text"><?= isset($antwoorden['reactie_anders']) ?></textarea></div>
                            </div>
                        </div>
                        <div class="question"><p>Probeert u spanningsvolle situaties zo goed mogelijk te voorkomen?</p>
                            <div class="checkboxes">
                                <div class="question-answer">
                                    <input id="radio" type="radio" name="spanningsvolle_situaties_voorkomen" <?= isset($antwoorden['spanningsvolle_situaties_voorkomen']) ? "checked" : "" ?>>
                                    <label>Ja</label>
                                    <textarea  rows="1" cols="25" id="checkfield" type="text" placeholder="en wel?" name="spanningsvolle_situaties_voorkomen_hoe"><?= isset($antwoorden['spanningsvolle_situaties_voorkomen_hoe']) ?></textarea>
                                </div>
                                <p>
                                    <input type="radio" name="spanningsvolle_situaties_voorkomen" <?= !isset($antwoorden['spanningsvolle_situaties_voorkomen']) ? "checked" : "" ?>>
                                    <label>Nee</label>
                                </p>
                            </div>
                        </div>
                        <div class="question"><p>- Probeert u spanningsvolle situaties zo goed mogelijk op te lossen?</p>
                            <div class="checkboxes">
                                <div class="question-answer">
                                    <input id="radio" type="radio" name="spanningsvolle_situaties_oplossen" <?= isset($antwoorden['spanningsvolle_situaties_oplossen']) ? "checked" : "" ?>>
                                    <label>Ja</label>
                                    <textarea  rows="1" cols="25" id="checkfield" type="text" placeholder="en wel?" name="spanningsvolle_situaties_oplossen_hoe"><?= isset($antwoorden['spanningsvolle_situaties_oplossen_hoe'])?></textarea>
                                </div>
                                <p>
                                    <input type="radio" name="spanningsvolle_situaties_oplossen" <?= !isset($antwoorden['spanningsvolle_situaties_oplossen']) ? "checked" : "" ?>>
                                    <label>Nee</label>
                                </p>
                            </div>
                        </div>
                        <div class="question"><p>Zijn er omstandigheden waarbij u in de war raakt?</p>
                            <div class="checkboxes">
                                <div class="question-answer">
                                    <input id="radio" type="radio" name="omstandigheden_in_war_raken" <?= isset($antwoorden['omstandigheden_in_war_raken']) ? "checked" : "" ?>>
                                    <label>Ja</label>
                                    <textarea  rows="1" cols="25" id="checkfield" type="text" placeholder="welke?" name="omstandigheden_in_war_raken_welke"><?= isset($antwoorden['omstandigheden_in_war_raken_welke'])?></textarea>
                                </div>
                                <p>
                                    <input type="radio" name="omstandigheden_in_war_raken" <?= !isset($antwoorden['omstandigheden_in_war_raken']) ? "checked" : "" ?>>
                                    <label>Nee</label>
                                </p>
                            </div>
                        </div>
                        <div class="question"><p>- Bent u wel eens angstig of in paniek?</p>
                            <div class="checkboxes">
                                <div class="question-answer">
                                    <input id="radio" type="radio" name="angstig_paniek" <?= isset($antwoorden['angstig_paniek']) ? "checked" : "" ?>>
                                    <label>Ja</label>
                                    <textarea  rows="1" cols="25" id="checkfield" type="text" placeholder="wat doet u dan?" name="angstig_paniek_actie"><?= isset($antwoorden['angstig_paniek_actie'])?></textarea>
                                </div>
                                <p>
                                    <input type="radio" name="angstig_paniek" <?= !isset($antwoorden['angstig_paniek']) ? "checked" : "" ?>>
                                    <label>Nee</label>
                                </p>
                            </div>
                        </div>
                        <div class="question"><p>- Weet u een dergelijke situatie te vookomen?</p>
                            <div class="checkboxes">
                                <p>    
                                    <input type="radio" name="angstig_paniek_lukt_voorkomen" <?= isset($antwoorden['angstig_paniek_lukt_voorkomen']) ? "checked" : "" ?>>
                                    <label>Ja</label>
                                </p>
                                <p>
                                    <input type="radio" name="angstig_paniek_lukt_voorkomen" <?= !isset($antwoorden['angstig_paniek_lukt_voorkomen']) ? "checked" : "" ?>>
                                    <label>Nee</label>
                                </p>
                            </div>
                        </div>
                        <div class="question"><p>Zijn er wel eens momenten dat u niet verder wilt leven?</p>
                            <div class="checkboxes">
                                <p>    
                                    <input type="radio" name="suicidaal" <?= isset($antwoorden['suicidaal']) ? "checked" : "" ?>>
                                    <label>Ja</label>
                                </p>
                                <p>
                                    <input type="radio" name="suicidaal" <?= !isset($antwoorden['suicidaal']) ? "checked" : "" ?>>
                                    <label>Nee</label>
                                </p>
                            </div>
                        </div>
                        <div class="question"><p>- Zo ja, ook op dit moment?</p>
                            <div class="checkboxes">
                                <p>    
                                    <input type="radio" name="suicidaal_momenteel" <?= isset($antwoorden['suicidaal_momenteel']) ? "checked" : "" ?>>
                                    <label>Ja</label>
                                </p>
                                <p>
                                    <input type="radio" name="suicidaal_momenteel" <?= !isset($antwoorden['suicidaal_momenteel']) ? "checked" : "" ?>>
                                    <label>Nee</label>
                                </p>
                            </div>
                        </div>
                        <div class="question"><p>Bent u wel eens agressief?</p>
                            <div class="checkboxes">
                                <p>    
                                    <input type="radio" name="agressief" <?= isset($antwoorden['agressief']) ? "checked" : "" ?>>
                                    <label>Ja</label>
                                </p>
                                <p>
                                    <input type="radio" name="agressief" <?= !isset($antwoorden['agressief']) ? "checked" : "" ?>>
                                    <label>Nee</label>
                                </p>
                            </div>
                        </div>
                        <div class="question"><p>- Voelt u een dreiging om u zelf of anderen iets aan te doen?</p>
                            <div class="checkboxes">
                                <p>    
                                    <input type="radio" name="anderen_iets_aan_willen_doen" <?= isset($antwoorden['anderen_iets_aan_willen_doen']) ? "checked" : "" ?>>
                                    <label>Ja</label>
                                </p>
                                <p>
                                    <input type="radio" name="anderen_iets_aan_willen_doen" <?= !isset($antwoorden['anderen_iets_aan_willen_doen']) ? "checked" : "" ?>>
                                    <label>Nee</label>
                                </p>
                            </div>
                        </div>
                        <div class="question"><p>- Neemt u maatregelen om de veiligheid van u zelf en anderen te waarborgen?</p>
                            <div class="checkboxes">
                                <div class="question-answer">
                                    <input id="radio" type="radio" name="maatregelen_veiligheid" <?= isset($antwoorden['maatregelen_veiligheid']) ? "checked" : "" ?>>
                                    <label>Ja</label>
                                    <textarea  rows="1" cols="25" id="checkfield" type="text" placeholder="door?" name="maatregelen_veiligheid_door"><?= isset($antwoorden['maatregelen_veiligheid_door'])?></textarea>
                                </div>
                                <p>
                                    <input type="radio" name="maatregelen_veiligheid" <?= isset($antwoorden['maatregelen_veiligheid']) ? "checked" : "" ?>>
                                    <label>Nee</label>
                                </p>
                            </div>
                        </div>
                        <div class="question"><p>Heeft u moeite met het uiten van gevoelens c.q. problemen?</p>
                            <div class="checkboxes">
                                <p>    
                                    <input type="radio" name="moeite_uiten_gevoelens" <?= isset($antwoorden['moeite_uiten_gevoelens']) ? "checked" : "" ?>>
                                    <label>Ja</label>
                                </p>
                                <p>
                                    <input type="radio" name="moeite_uiten_gevoelens" <?= !isset($antwoorden['moeite_uiten_gevoelens']) ? "checked" : "" ?>>
                                    <label>Nee</label>
                                </p>
                            </div>
                        </div>
                        <div class="question"><p>- Met wie bespreekt u uw gevoelens c.q. problemen?</p><textarea  rows="1" cols="25" type="text" name="bespreken_gevoelens_met"><?= isset($antwoorden['bespreken_gevoelens_met'])?></textarea></div>

                        <div class="observation">
                            <h2>Verpleegkundige observatie bij dit patroon</h2>
                            <div class="question"><div class="observe"><input type="checkbox" <?= isset($boolArrayObservatie[0]) ? "checked" : "" ?> name="observatie1"><p>Defensieve coping</p></div></div>
                            <div class="question"><div class="observe"><input type="checkbox" <?= isset($boolArrayObservatie[1]) ? "checked" : "" ?> name="observatie2"><p>Probleemvermijding</p></div></div>
                            <div class="question"><div class="observe"><input type="checkbox" <?= isset($boolArrayObservatie[2]) ? "checked" : "" ?> name="observatie3"><p>Ineffectieve coping</p></div></div>
                            <div class="question"><div class="observe"><input type="checkbox" <?= isset($boolArrayObservatie[3]) ? "checked" : "" ?> name="observatie4"><p>Ineffectieve ontkenning</p></div></div>
                            <div class="question"><div class="observe"><input type="checkbox" <?= isset($boolArrayObservatie[4]) ? "checked" : "" ?> name="observatie5"><p>Posttraumatische reactie</p></div></div>
                            <div class="question"><div class="observe"><input type="checkbox" <?= isset($boolArrayObservatie[5]) ? "checked" : "" ?> name="observatie6"><p>Verminderd aanpassingsvermogen</p></div></div>
                            <div class="question"><div class="observe"><input type="checkbox" <?= isset($boolArrayObservatie[6]) ? "checked" : "" ?> name="observatie7"><p>Gezinscoping: ontplooiingsmogelijkheden</p></div></div>
                            <div class="question"><div class="observe"><input type="checkbox" <?= isset($boolArrayObservatie[7]) ? "checked" : "" ?> name="observatie8"><p>Bedreigde gezinscoping</p></div></div>
                            <div class="question"><div class="observe"><input type="checkbox" <?= isset($boolArrayObservatie[8]) ? "checked" : "" ?> name="observatie9"><p>Gebrekkige gezinscoping</p></div></div>
                            <div class="question"><div class="observe"><input type="checkbox" <?= isset($boolArrayObservatie[9]) ? "checked" : "" ?> name="observatie10"><p>Dreiging van suïcidaliteit</p></div></div>
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