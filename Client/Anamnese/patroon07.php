
<?php
session_start();
include '../../Database/DatabaseConnection.php';
include '../../Functions/Functions.php';

$clientId = $_GET['id'];
$antwoorden = getPatternAnswers($clientId, 7);
echo "<pre>";
print_r($antwoorden);
echo "</pre>";

if (isset($_REQUEST['navbutton'])) {
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
    <div class="main-content">
        <div class="content">
            <div class="form-content">
            <div class="pages">7 Zelfbelevingspatroon</div>
                <div class="form">
                    <div class="questionnaire">
                        <div class="question"><p>Kunt u uzelf, in het kort, beschijven?</p><textarea  rows="1" cols="25" type="text"></textarea></div>
                        <div class="question"><p>Kunt u voor uzelf opkomen?</p>
                            <div class="checkboxes">
                                <p>    
                                    <input type="radio" name="radio-1">
                                    <label>Ja</label>
                                </p>
                                <p>
                                    <input type="radio" name="radio-1">
                                    <label>Nee</label>
                                </p>
                            </div>
                        </div>
                        <div class="question"><p>Waar blijkt dat uit?</p><textarea  rows="1" cols="25" type="text"></textarea></div>
                        <div class="question"><p>Is uw stemming de laatste tijd veranderd?</p>
                            <div class="checkboxes">
                                <div class="question-answer">
                                    <input id="radio" type="radio" name="radio-2">
                                    <label>Ja</label>
                                    <textarea  rows="1" cols="25" id="checkfield" type="text" placeholder="en wel?"></textarea>
                                </div>
                                <p>
                                    <input type="radio" name="radio-2">
                                    <label>Nee</label>
                                </p>
                            </div>
                        </div>
                        <div class="question"><p>- Hoe voelt u zich op dit moment?</p> 
                            <div class="observation">
                                <div class="question"><div class="observe"><input type="checkbox"><p>Neerslachtig</p></div></div>
                                <div class="question"><div class="observe"><input type="checkbox"><p>Wanhopig</p></div></div>
                                <div class="question"><div class="observe"><input type="checkbox"><p>Machteloos</p></div></div>
                                <div class="question"><div class="observe"><input type="checkbox"><p>Opgewekt</p></div></div>
                                <div class="question"><div class="observe"><input type="checkbox"><p>Somber</p></div></div>
                                <div class="question"><div class="observe"><input type="checkbox"><p>Eufoor</p></div></div>
                                <div class="question"><div class="observe"><input type="checkbox"><p>Labiel</p></div></div>
                                <div class="question"><div class="observe"><input type="checkbox"><p>Gespannen</p></div></div>
                                <div class="question"><div class="observe"><input type="checkbox"><p>Verdrietig</p></div></div>
                                <div class="question"><div class="observe"><input type="checkbox"><p>Anders, namelijk:</p></div><textarea  rows="1" cols="25" type="text"></textarea></div>
                            </div>
                        </div>
                        <div class="question"><p>- Is er de afgeloen tijd iets veranderd in uw concentratievermogen ten gevolgen van u stemming?</p>
                            <div class="checkboxes">
                                <p>    
                                    <input type="radio" name="radio-3">
                                    <label>Ja</label>
                                </p>
                                <p>
                                    <input type="radio" name="radio-3">
                                    <label>Nee</label>
                                </p>
                            </div>
                        </div>
                        <div class="question"><p>- Is er de afgeloen tijd iets veranderd in uw denkpatroon ten gevolgen van u stemming?</p>
                            <div class="checkboxes">
                                <p>    
                                    <input type="radio" name="radio-4">
                                    <label>Ja</label>
                                </p>
                                <p>
                                    <input type="radio" name="radio-4">
                                    <label>Nee</label>
                                </p>
                            </div>
                        </div>
                        <div class="question"><p>Ervaart u uzelf nu anders dan voorheen?</p>
                            <div class="checkboxes">
                                <p>    
                                    <input type="radio" name="radio-5">
                                    <label>Ja</label>
                                </p>
                                <p>
                                    <input type="radio" name="radio-5">
                                    <label>Nee</label>
                                </p>
                            </div>
                        </div>
                        <div class="question"><p>- Zijn er veranderingen in uw uiterlijk en/of mogelijkheden wardoor u zich anders voelt?</p>
                            <div class="checkboxes">
                                <p>    
                                    <input type="radio" name="radio-6">
                                    <label>Ja</label>
                                </p>
                                <p>
                                    <input type="radio" name="radio-6">
                                    <label>Nee</label>
                                </p>
                            </div>
                        </div>
                        <div class="question"><p>- Voelt u (lichamelijke) sensaties?</p>
                            <div class="checkboxes">
                                <div class="question-answer">
                                    <input id="radio" type="radio" name="radio-7">
                                    <label>Ja</label>
                                    <textarea  rows="1" cols="25" id="checkfield" type="text" placeholder="wat voelt u?"></textarea>
                                </div>
                                <p>
                                    <input type="radio" name="radio-7">
                                    <label>Nee</label>
                                </p>
                            </div>
                        </div>
                        <div class="question"><p>- Hoe voelt u zich momenteel?</p> 
                            <div class="observation">
                                <div class="question"><div class="observe"><input type="checkbox"><p>Sterk</p></div></div>
                                <div class="question"><div class="observe"><input type="checkbox"><p>Zwak</p></div></div>
                                <div class="question"><div class="observe"><input type="checkbox"><p>Krachteloos</p></div></div>
                            </div>
                        </div>
                        <div class="question"><p>- Hoe staat het met uw lichamelijke energie?</p> 
                            <div class="observation">
                                <div class="question"><div class="observe"><input type="checkbox"><p>Genoeg</p></div></div>
                                <div class="question"><div class="observe"><input type="checkbox"><p>Te veel</p></div></div>
                                <div class="question"><div class="observe"><input type="checkbox"><p>Te weinig</p></div></div>
                            </div>
                        </div>
                        <div class="question"><p>Zorgt u goed voor u zelf, of vindt u dat het beter kan?</p><textarea  rows="1" cols="25" type="text"></textarea></div>

                        <div class="observation">
                            <h2>Verpleegkundige observatie bij dit patroon</h2>
                            <div class="question"><div class="observe"><input type="checkbox"><p>Lichte angst</p></div></div>
                            <div class="question"><div class="observe"><input type="checkbox"><p>Matige angst</p></div></div>
                            <div class="question"><div class="observe"><input type="checkbox"><p>Hevige (paniek) angst</p></div></div>
                            <div class="question"><div class="observe"><input type="checkbox"><p>Lichte anticiperende angst</p></div></div>
                            <div class="question"><div class="observe"><input type="checkbox"><p>Matige anticiperende angst</p></div></div>
                            <div class="question"><div class="observe"><input type="checkbox"><p>Hevige anticiperende angst</p></div></div>
                            <div class="question"><div class="observe"><input type="checkbox"><p>Vrees</p></div></div>
                            <div class="question"><div class="observe"><input type="checkbox"><p>Reactieve depressie</p></div></div>
                            <div class="question"><div class="observe"><input type="checkbox"><p>Moedeloosheid</p></div></div>
                            <div class="question"><div class="observe"><input type="checkbox"><p>Identiteitsstoornis</p></div></div>
                            <div class="question"><div class="observe"><input type="checkbox"><p>Lichte machteloosheid</p></div></div>
                            <div class="question"><div class="observe"><input type="checkbox"><p>Matige machteloosheid</p></div></div>
                            <div class="question"><div class="observe"><input type="checkbox"><p>Ernstige machteloosheid</p></div></div>
                            <div class="question"><div class="observe"><input type="checkbox"><p>Geringe zelfachting</p></div></div>
                            <div class="question"><div class="observe"><input type="checkbox"><p>Chronisch geringe zelfachting</p></div></div>
                            <div class="question"><div class="observe"><input type="checkbox"><p>Reactief geringe zelfachting</p></div></div>
                            <div class="question"><div class="observe"><input type="checkbox"><p>Verstoord lichaamsbeeld</p></div></div>
                            <div class="question"><div class="observe"><input type="checkbox"><p>Hopeloosheid</p></div></div>
                            <div class="question"><div class="observe"><input type="checkbox"><p>Dreigende zelfverminking (automutilatie)</p></div></div>
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