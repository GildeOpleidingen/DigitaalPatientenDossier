
<?php
session_start();
include '../../database/DatabaseConnection.php';
include_once '../../classes/Main.php';
$Main = new Main();

$antwoorden = $Main->getPatternAnswers($_SESSION['clientId'], 5);

$boolArrayInslaapmiddel = str_split($antwoorden['gebruik_inslaapmiddel_welke']);
$boolArrayObservatie = str_split($antwoorden['observatie']);

if (isset($_REQUEST['navbutton'])) {
    //TODO: hier actie om data op te slaan in database.
    switch($_REQUEST['navbutton']) {
        case 'next': //action for next here
            header('Location: patroon06.php');
            break;
    
        case 'prev': //action for previous here
            header('Location: patroon04.php');
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
            <div class="pages">5. Slaap- en rustpatroon</div>
                <div class="form">
                    <div class="questionnaire">
                    <div class="question"><p>Is er in de afgelopen periode verandering in de de duur van uw slaap gekomen?</p>
                            <div class="checkboxes">
                                <div class="question-answer">
                                    <input id="radio" type="radio" name="verandering_inslaaptijd" <?= $antwoorden['verandering_inslaaptijd'] ? "checked" : "" ?>>
                                    <label>Ja</label>
                                    <textarea  rows="1" cols="25" id="checkfield" type="text" placeholder="en wel?" name="verandering_inslaaptijd_blijktuit"> <?= $antwoorden['verandering_inslaaptijd_blijktuit'] ??  "" ?> </textarea>
                                </div>
                                <p>
                                    <input type="radio" name="verandering_inslaaptijd" <?= !$antwoorden['verandering_inslaaptijd'] ? "checked" : "" ?>>
                                    <label>Nee</label>
                                </p>
                            </div>
                        </div>
                        <div class="question"><p>- Is er verandering ontstaan in de kwaliteit van uw slaap (in- en/of doorslaapprobleem)?</p>
                            <div class="checkboxes">
                                <div class="question-answer">
                                    <input id="radio" type="radio" name="verandering_kwaliteit_slapen" <?= $antwoorden['verandering_kwaliteit_slapen'] ? "checked" : "" ?>>   
                                    <label>Ja</label>
                                    <textarea  rows="1" cols="25" id="checkfield" type="text" placeholder="en wel?" name="verandering_kwaliteit_slapen_blijktuit"> <?= $antwoorden['verandering_kwaliteit_slapen_blijktuit'] ??  "" ?> </textarea>
                                </div>
                                <p>
                                    <input type="radio" name="verandering_kwaliteit_slapen" <?= !$antwoorden['verandering_kwaliteit_slapen'] ? "checked" : "" ?>>
                                    <label>Nee</label>
                                </p>
                            </div>
                        </div>
                        <div class="question"><p>- Doet u iets om (in) te kunnen slapen?</p>
                            <div class="checkboxes">
                                <div class="question-answer">
                                    <input id="radio" type="radio" name="gebruik_inslaapmiddel" <?= $antwoorden['gebruik_inslaapmiddel'] ? "checked" : "" ?>>
                                    <label>Ja</label>
                                    <div id="checkfield">
                                        <div class="question"><div class="observe"><input type="checkbox" <?= @$boolArrayInslaapmiddel[0] ? "checked" : "" ?> name="inslaapmiddel1"><p>Medicijngebruik</p></div></div>
                                        <div class="question"><div class="observe"><input type="checkbox" <?= @$boolArrayInslaapmiddel[1] ? "checked" : "" ?> name="inslaapmiddel2"><p>Beweging</p></div></div>
                                        <div class="question"><div class="observe"><input type="checkbox" <?= @$boolArrayInslaapmiddel[2] ? "checked" : "" ?> name="inslaapmiddel3"><p>Alcohol/drugs</p></div></div>
                                        <div class="question"><div class="observe"><input type="checkbox" <?= @$boolArrayInslaapmiddel[3] ? "checked" : "" ?> name="inslaapmiddel4"><p>Eten/drinken</p></div></div>
                                        <div class="question"><div class="observe"><input type="checkbox" <?= @$boolArrayInslaapmiddel[4] ? "checked" : "" ?> name="inslaapmiddel5"><p>Douche/bad</p></div></div>
                                        <div class="question"><div class="observe"><input type="checkbox" <?= @$boolArrayInslaapmiddel[5] ? "checked" : "" ?> name="inslaapmiddel6"><p>Anders, namelijk:</p></div><textarea  rows="1" cols="25" type="text"></textarea></div>
                                    </div>
                                </div>
                                <p>
                                    <input type="radio" name="gebruik_inslaapmiddel" <?= !$antwoorden['gebruik_inslaapmiddel'] ? "checked" : "" ?>>
                                    <label>Nee</label>
                                </p>
                            </div>
                        </div>
                        <div class="question"><p>- Hoe lang slaapt u nomaal?</p><p><input type="number" step=0.5 min="0" max="24" value="<?= $antwoorden['slaapduur'] ?>" name="slaapduur"> uur</p></div>
                        <div class="question"><p>- Voelt u zich uitgerust als u wakker wordt?</p>
                            <div class="checkboxes">
                                <p>    
                                    <input type="radio" name="uitgerust_wakker" <?= $antwoorden['uitgerust_wakker'] ? "checked" : "" ?>>
                                    <label>Ja</label>
                                </p>
                                <p>
                                    <input type="radio" name="uitgerust_wakker" <?= !$antwoorden['uitgerust_wakker'] ? "checked" : "" ?>>
                                    <label>Nee</label>
                                </p>
                            </div>
                        </div>
                        <div class="question"><p>Heeft u last van dromen, nachtmerries?</p>
                            <div class="checkboxes">
                                <p>    
                                    <input type="radio" name="dromen_nachtmerries" <?= $antwoorden['dromen_nachtmerries'] ? "checked" : "" ?>>
                                    <label>Ja</label>
                                </p>
                                <p>
                                    <input type="radio" name="dromen_nachtmerries" <?= !$antwoorden['dromen_nachtmerries'] ? "checked" : "" ?>>
                                    <label>Nee</label>
                                </p>
                            </div>
                        </div>
                        <div class="question"><p>- Neemt u rustperioden overdag?</p>
                            <div class="checkboxes">
                                <p>    
                                    <input type="radio" name="rustperiodes_overdag" <?= $antwoorden['rustperiodes_overdag'] ? "checked" : "" ?>>
                                    <label>Ja</label>
                                </p>
                                <p>
                                    <input type="radio" name="rustperiodes_overdag" <?= !$antwoorden['rustperiodes_overdag'] ? "checked" : "" ?>>
                                    <label>Nee</label>
                                </p>
                            </div>
                        </div>
                        <div class="question"><p>- Kunt u zich gemakkelijk ontspannen?</p>
                            <div class="checkboxes">
                                <p>    
                                    <input type="radio" name="gemakkelijk_ontspannen" <?= $antwoorden['gemakkelijk_ontspannen'] ? "checked" : "" ?>>
                                    <label>Ja</label>
                                </p>
                                <p>
                                    <input type="radio" name="gemakkelijk_ontspannen" <?= !$antwoorden['gemakkelijk_ontspannen'] ? "checked" : "" ?>>
                                    <label>Nee</label>
                                </p>
                            </div>
                        </div>

                        <div class="observation">
                            <h2>Verpleegkundige observatie bij dit patroon</h2>
                            <div class="question"><div class="observe"><input type="checkbox" <?= $boolArrayObservatie[0] ? "checked" : "" ?>><p>Verstoord slaap- en rustpatroon</p></div></div>
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