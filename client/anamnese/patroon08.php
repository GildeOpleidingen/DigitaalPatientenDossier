<?php
session_start();
include '../../database/DatabaseConnection.php';
include_once '../../classes/Main.php';
$Main = new Main();

$antwoorden = $Main->getPatternAnswers($_SESSION['clientId'], 8);

$boolArrayObservatie = str_split($antwoorden['observatie']);

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
    <link rel="stylesheet" href="../../assets/css/bootstrap.min.css">
    <title>Anamnese</title>
</head>
<body>
    <form action="" method="post">
    <div class="main">
        <?php
        include '../../includes/n-header.php';
        include '../../includes/n-sidebar.php';
        ?>
        <div class="content mt-3">
            <div class="form-content">
            <div class="pages">8. Rollen- en relatiepatroon</div>
                <div class="form">
                    <div class="questionnaire">
                    <div class="question"><p>Bent u getrouwd/samenwonend?</p>
                            <div class="checkboxes">
                                <p>    
                                    <input type="radio" name="getrouwd_samenwonend" <?= isset($antwoorden['getrouwd_samenwonend']) ? "checked" : "" ?>>
                                    <label>Ja</label>
                                </p>
                                <p>
                                    <input type="radio" name="getrouwd_samenwonend" <?= !isset($antwoorden['getrouwd_samenwonend']) ? "checked" : "" ?>>
                                    <label>Nee</label>
                                </p>
                            </div>
                        </div>
                        <div class="question"><p>- Heeft u kinderen?</p>
                            <div class="checkboxes">
                                <p>    
                                    <input type="radio" name="kinderen" <?= isset($antwoorden['kinderen']) ? "checked" : "" ?>>
                                    <label>Ja</label>
                                </p>
                                <p>
                                    <input type="radio" name="kinderen" <?= !isset($antwoorden['kinderen']) ? "checked" : "" ?>>
                                    <label>Nee</label>
                                </p>
                            </div>
                        </div>
                        <div class="question"><p>- Bent u tevreden over uw thuissituatie?</p>
                            <div class="checkboxes">
                                <p>    
                                    <input type="radio" name="tevreden_thuissituatie" <?= isset($antwoorden['tevreden_thuissituatie']) ? "checked" : "" ?>>
                                    <label>Ja</label>
                                </p>
                                <p>
                                    <input type="radio" name="tevreden_thuissituatie" <?= !isset($antwoorden['tevreden_thuissituatie']) ? "checked" : "" ?>>
                                    <label>Nee</label>
                                </p>
                            </div>
                        </div>
                        <div class="question"><p>Heeft u een vrienden-/familiekring waar u steun aan heeft?</p>
                            <div class="checkboxes">
                                <p>    
                                    <input type="radio" name="steun_vrienden_familie" <?= isset($antwoorden['steun_vrienden_familie']) ? "checked" : "" ?>>
                                    <label>Ja</label>
                                </p>
                                <p>
                                    <input type="radio" name="steun_vrienden_familie" <?= !isset($antwoorden['steun_vrienden_familie']) ? "checked" : "" ?>>
                                    <label>Nee</label>
                                </p>
                            </div>
                        </div>
                        <div class="question"><p>Wat is uw beroep of huidige bron van inkomsten?</p><textarea  rows="1" cols="25" type="text" name="inkomstenbron"><?= isset($antwoorden['inkomstenbron']) ?></textarea></div>
                        <div class="question"><p>- Is er de afgelopen tijd een verandering geweest in uw financiële situatie?</p>
                            <div class="checkboxes">
                                <div class="question-answer">
                                    <input id="radio" type="radio" name="verandering_fin_sit_vroeger" <?= isset($antwoorden['verandering_fin_sit_vroeger']) ? "checked" : "" ?>>
                                    <label>Ja</label>
                                    <textarea  rows="1" cols="25" id="checkfield" type="text" placeholder="welke?" name="verandering_fin_sit_vroeger_welke"><?= isset($antwoorden['verandering_fin_sit_vroeger_welke']) ?></textarea>
                                </div>
                                <p>
                                    <input type="radio" name="verandering_fin_sit_vroeger" <?= !isset($antwoorden['verandering_fin_sit_vroeger']) ? "checked" : "" ?>>
                                    <label>Nee</label>
                                </p>
                            </div>
                        </div>
                        <div class="question"><p>- Verwacht u in de nabije toekomst een verandering in uw financiële situatie?</p>
                            <div class="checkboxes">
                                <div class="question-answer">
                                    <input id="radio" type="radio" name="verandering_fin_sit_toekomst" <?= isset($antwoorden['verandering_fin_sit_toekomst']) ? "checked" : "" ?>>
                                    <label>Ja</label>
                                    <textarea  rows="1" cols="25" id="checkfield" type="text" placeholder="welke?" name="verandering_fin_sit_toekomst_welke"><?= isset($antwoorden['verandering_fin_sit_toekomst_welke']) ?></textarea>
                                </div>
                                <p>
                                    <input type="radio" name="verandering_fin_sit_toekomst" <?= !isset($antwoorden['verandering_fin_sit_toekomst']) ? "checked" : "" ?>>
                                    <label>Nee</label>
                                </p>
                            </div>
                        </div>
                        <div class="question"><p>Wat is uw opleiding?</p><textarea  rows="1" cols="25" type="text"><?= isset($antwoorden['opleiding']) ?></textarea></div>
                        <div class="question"><p>Is er de laatste tijd verandering gekomen in uw sociale contacten?</p>
                            <div class="checkboxes">
                                <div class="question-answer">
                                    <input id="radio" type="radio" name="verandering_fin_sit_toekomst" <?= isset($antwoorden['verandering_sociale_contacten']) ? "checked" : "" ?>>
                                    <label>Ja</label>
                                    <textarea  rows="1" cols="25" id="checkfield" type="text" placeholder="welke?" name="verandering_sociale_contacten_welke"><?= isset($antwoorden['verandering_sociale_contacten_welke']) ?></textarea>
                                </div>
                                <p>
                                    <input type="radio" name="verandering_fin_sit_toekomst" <?= !isset($antwoorden['verandering_sociale_contacten']) ? "checked" : "" ?>>
                                    <label>Nee</label>
                                </p>
                            </div>
                        </div>
                        <div class="question"><p>Komt u uit een groot gezin?</p>
                            <div class="checkboxes">
                                <p>    
                                    <input type="radio" name="groot_gezin" <?= isset($antwoorden['groot_gezin']) ? "checked" : "" ?>>
                                    <label>Ja</label> 
                                </p>
                                <p>
                                    <input type="radio" name="groot_gezin" <?= !isset($antwoorden['groot_gezin']) ? "checked" : "" ?>>
                                    <label>Nee</label>
                                </p>
                            </div>
                        </div>
                        <div class="question"><p>- Wat was u plaats in dat gezin?</p><textarea  rows="1" cols="25" type="text" name="plaats_in_gezin"><?= isset($antwoorden['plaats_in_gezin']) ?></textarea></div>
                        <div class="question"><p>- Hoe verliepen de onderlinge contacten?</p><textarea  rows="1" cols="25" type="text" name="onderlinge_contacten_gezin"><?= isset($antwoorden['onderlinge_contacten_gezin']) ?></textarea></div>
                        <div class="question"><p>- Was er sprake van agressie in dat gezin?</p>
                            <div class="checkboxes">
                                <p>    
                                    <input type="radio" name="agressie_gezin" <?= isset($antwoorden['agressie_gezin']) ? "checked" : "" ?>>
                                    <label>Ja</label>
                                </p>
                                <p>
                                    <input type="radio" name="agressie_gezin" <?= !isset($antwoorden['agressie_gezin']) ? "checked" : "" ?>>
                                    <label>Nee</label>
                                </p>
                            </div>
                        </div>
                        <div class="question"><p>Bent u lid van verenigingen?</p>
                            <div class="checkboxes">
                                <div class="question-answer">
                                    <input id="radio" type="radio" name="verenigingslid" <?= isset($antwoorden['verenigingslid']) ? "checked" : "" ?>>
                                    <label>Ja</label>
                                    <textarea  rows="1" cols="25" id="checkfield" type="text" placeholder="welke?" name="vereniging_welke"><?= isset($antwoorden['vereniging_welke']) ?></textarea>
                                </div>
                                <p>
                                    <input type="radio" name="verenigingslid" <?= !isset($antwoorden['verenigingslid']) ? "checked" : "" ?>>
                                    <label>Nee</label>
                                </p>
                            </div>
                        </div>
                        <div class="question"><p>- Hoe verliepen de contacten met derden (collega's, kennissen, verenigingsgenoten)?</p><textarea  rows="1" cols="25" type="text" name="contact_met_derden"><?= isset($antwoorden['contact_met_derden']) ?></textarea></div>
                        <div class="question"><p>Heeft u de laatst tijd een verlies geleden (werk, personen, enzovoort)?</p>
                            <div class="checkboxes">
                                <div class="question-answer">
                                    <input id="radio" type="radio" name="verlies_geleden" <?= isset($antwoorden['verlies_geleden']) ? "checked" : "" ?>>
                                    <label>Ja</label>
                                    <textarea  rows="1" cols="25" id="checkfield" type="text" placeholder="en wel?" name="verlies_geleden_welke"><?= isset($antwoorden['verlies_geleden_welke']) ?></textarea>
                                </div>
                                <p>
                                    <input type="radio" name="verlies_geleden" <?= !isset($antwoorden['verlies_geleden']) ? "checked" : "" ?>>
                                    <label>Nee</label>
                                </p>
                            </div>
                        </div>

                        <div class="observation">
                            <h2>Verpleegkundige observatie bij dit patroon</h2>
                            <div class="question"><div class="observe"><input type="checkbox" <?= isset($boolArrayObservatie[0]) ? "checked" : ""?> name="observatie1"><p>Verstoorde verbale communicatie (afwijkende groei en ontwikkeling in communicatieve vaardigheden)</p></div></div>
                            <div class="question"><div class="observe"><input type="checkbox" <?= isset($boolArrayObservatie[1]) ? "checked" : ""?> name="observatie2"><p>Anticiperende rouw</p></div></div>
                            <div class="question"><div class="observe"><input type="checkbox" <?= isset($boolArrayObservatie[2]) ? "checked" : ""?> name="observatie3"><p>Disfunctionele rouw</p></div></div>
                            <div class="question"><div class="observe"><input type="checkbox" <?= isset($boolArrayObservatie[3]) ? "checked" : ""?> name="observatie4"><p>Gewijzigde gezinsprocessen</p></div></div>
                            <div class="question"><div class="observe"><input type="checkbox" <?= isset($boolArrayObservatie[4]) ? "checked" : ""?> name="observatie5"><p>(Dreigend) ouderschapstekort</p></div></div>
                            <div class="question"><div class="observe"><input type="checkbox" <?= isset($boolArrayObservatie[5]) ? "checked" : ""?> name="observatie6"><p>Ouderrolconflict</p></div></div>
                            <div class="question"><div class="observe"><input type="checkbox" <?= isset($boolArrayObservatie[6]) ? "checked" : ""?> name="observatie7"><p>Inadequate sociale interacties</p></div></div>
                            <div class="question"><div class="observe"><input type="checkbox" <?= isset($boolArrayObservatie[7]) ? "checked" : ""?> name="observatie8"><p>Afwijkende groei en ontikkeling in sociale vaardigheden</p></div></div>
                            <div class="question"><div class="observe"><input type="checkbox" <?= isset($boolArrayObservatie[8]) ? "checked" : ""?> name="observatie9"><p>Sociaal isolement</p></div></div>
                            <div class="question"><div class="observe"><input type="checkbox" <?= isset($boolArrayObservatie[9]) ? "checked" : ""?> name="observatie10"><p>Verstoorde rolvervulling</p></div></div>
                            <div class="question"><div class="observe"><input type="checkbox" <?= isset($boolArrayObservatie[10]) ? "checked" : ""?> name="observatie11"><p>Onopgelost onafhankelijkheids-/afhankelijkheidsconflict</p></div></div>
                            <div class="question"><div class="observe"><input type="checkbox" <?= isset($boolArrayObservatie[11]) ? "checked" : ""?> name="observatie12"><p>Sociale afwijzing</p></div></div>
                            <div class="question"><div class="observe"><input type="checkbox" <?= isset($boolArrayObservatie[12]) ? "checked" : ""?> name="observatie13"><p>(Dreigende) overbelasting van de mantelzorg)</p></div></div>
                            <div class="question"><div class="observe"><input type="checkbox" <?= isset($boolArrayObservatie[13]) ? "checked" : ""?> name="observatie14"><p>Mantelzorgtekort</p></div></div>
                            <div class="question"><div class="observe"><input type="checkbox" <?= isset($boolArrayObservatie[14]) ? "checked" : ""?> name="observatie15"><p>Dreigend geweld:</p></div></div>
                            <div class="question"><div class="observe"><input type="checkbox" <?= isset($boolArrayObservatie[15]) ? "checked" : ""?> name="observatie16"><p>gericht op andere</p></div></div>
                            <div class="question"><div class="observe"><input type="checkbox" <?= isset($boolArrayObservatie[16]) ? "checked" : ""?> name="observatie17"><p>gericht op voorwerpen (meubilair, enzovoort)</p></div></div>
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