
<?php
session_start();
include '../../Database/DatabaseConnection.php';

$id = $_GET['id'];

if (isset($_REQUEST['navbutton'])) {
    //TODO: hier actie om data op te slaan in database.
    switch($_REQUEST['navbutton']) {
        case 'next': //action for next here
            header('Location: patroon11.php?id='.$id);
            break;
    
        case 'prev': //action for previous here
            header('Location: patroon09.php?id='.$id);
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
                                <div class="question"><div class="observe"><input type="checkbox"><p>Zoveel mogelijk vermijden</p></div></div>
                                <div class="question"><div class="observe"><input type="checkbox"><p>Drugs gebruiken</p></div></div>
                                <div class="question"><div class="observe"><input type="checkbox"><p>Ontwikkeling van lichamelijke symptomen</p></div></div>
                                <div class="question"><div class="observe"><input type="checkbox"><p>Medicatie</p></div></div>
                                <div class="question"><div class="observe"><input type="checkbox"><p>Meer/minder eten</p></div></div>
                                <div class="question"><div class="observe"><input type="checkbox"><p>Agressie</p></div></div>
                                <div class="question"><div class="observe"><input type="checkbox"><p>Praten met anderen</p></div></div>
                                <div class="question"><div class="observe"><input type="checkbox"><p>Alcohol drinken</p></div></div>
                                <div class="question"><div class="observe"><input type="checkbox"><p>Houd mijn gevoelens voor me</p></div></div>
                                <div class="question"><div class="observe"><input type="checkbox"><p>Slapen/terugtrekken</p></div></div>
                                <div class="question"><div class="observe"><input type="checkbox"><p>Vertrouwen op religie</p></div></div>
                                <div class="question"><div class="observe"><input type="checkbox"><p>Zo goed mogelijk zelf oplossen</p></div></div>
                                <div class="question"><div class="observe"><input type="checkbox"><p>Anders, namelijk:</p></div><textarea  rows="1" cols="25" type="text"></textarea></div>
                            </div>
                        </div>
                        <div class="question"><p>Probeert u spanningsvolle situaties zo goed mogelijk te voorkomen?</p>
                            <div class="checkboxes">
                                <div class="question-answer">
                                    <input id="radio" type="radio" name="radio-1">
                                    <label>Ja</label>
                                    <textarea  rows="1" cols="25" id="checkfield" type="text" placeholder="en wel?"></textarea>
                                </div>
                                <p>
                                    <input type="radio" name="radio-1">
                                    <label>Nee</label>
                                </p>
                            </div>
                        </div>
                        <div class="question"><p>- Probeert u spanningsvolle situaties zo goed mogelijk op te lossen?</p>
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
                        <div class="question"><p>Zijn er omstandigheden waarbij u in de war raakt?</p>
                            <div class="checkboxes">
                                <div class="question-answer">
                                    <input id="radio" type="radio" name="radio-3">
                                    <label>Ja</label>
                                    <textarea  rows="1" cols="25" id="checkfield" type="text" placeholder="welke?"></textarea>
                                </div>
                                <p>
                                    <input type="radio" name="radio-3">
                                    <label>Nee</label>
                                </p>
                            </div>
                        </div>
                        <div class="question"><p>- Bent u wel eens angstig of in paniek?</p>
                            <div class="checkboxes">
                                <div class="question-answer">
                                    <input id="radio" type="radio" name="radio-4">
                                    <label>Ja</label>
                                    <textarea  rows="1" cols="25" id="checkfield" type="text" placeholder="wat doet u dan?"></textarea>
                                </div>
                                <p>
                                    <input type="radio" name="radio-4">
                                    <label>Nee</label>
                                </p>
                            </div>
                        </div>
                        <div class="question"><p>- Weet u een dergelijke situatie te vookomen?</p>
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
                        <div class="question"><p>Zijn er wel eens momenten dat u niet verder wilt leven?</p>
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
                        <div class="question"><p>- Zo ja, ook op dit moment?</p>
                            <div class="checkboxes">
                                <p>    
                                    <input type="radio" name="radio-7">
                                    <label>Ja</label>
                                </p>
                                <p>
                                    <input type="radio" name="radio-7">
                                    <label>Nee</label>
                                </p>
                            </div>
                        </div>
                        <div class="question"><p>Bent u wel eens agressief?</p>
                            <div class="checkboxes">
                                <p>    
                                    <input type="radio" name="radio-8">
                                    <label>Ja</label>
                                </p>
                                <p>
                                    <input type="radio" name="radio-8">
                                    <label>Nee</label>
                                </p>
                            </div>
                        </div>
                        <div class="question"><p>- Voelt u een dreiging om u zelf of anderen iets aan te doen?</p>
                            <div class="checkboxes">
                                <p>    
                                    <input type="radio" name="radio-9">
                                    <label>Ja</label>
                                </p>
                                <p>
                                    <input type="radio" name="radio-9">
                                    <label>Nee</label>
                                </p>
                            </div>
                        </div>
                        <div class="question"><p>- Neemt u maatregelen om de veiligheid van u zelf en anderen te waarborgen?</p>
                            <div class="checkboxes">
                                <div class="question-answer">
                                    <input id="radio" type="radio" name="radio-10">
                                    <label>Ja</label>
                                    <textarea  rows="1" cols="25" id="checkfield" type="text" placeholder="door?"></textarea>
                                </div>
                                <p>
                                    <input type="radio" name="radio-10">
                                    <label>Nee</label>
                                </p>
                            </div>
                        </div>
                        <div class="question"><p>Heeft u moeite met het uiten van gevoelens c.q. problemen?</p>
                            <div class="checkboxes">
                                <p>    
                                    <input type="radio" name="radio-11">
                                    <label>Ja</label>
                                </p>
                                <p>
                                    <input type="radio" name="radio-11">
                                    <label>Nee</label>
                                </p>
                            </div>
                        </div>
                        <div class="question"><p>- Met wie bespreekt u uw gevoelens c.q. problemen?</p><textarea  rows="1" cols="25" type="text"></textarea></div>

                        <div class="observation">
                            <h2>Verpleegkundige observatie bij dit patroon</h2>
                            <div class="question"><div class="observe"><input type="checkbox"><p>Defensieve coping</p></div></div>
                            <div class="question"><div class="observe"><input type="checkbox"><p>Probleemvermijding</p></div></div>
                            <div class="question"><div class="observe"><input type="checkbox"><p>Ineffectieve coping</p></div></div>
                            <div class="question"><div class="observe"><input type="checkbox"><p>Ineffectieve ontkenning</p></div></div>
                            <div class="question"><div class="observe"><input type="checkbox"><p>Posttraumatische reactie</p></div></div>
                            <div class="question"><div class="observe"><input type="checkbox"><p>Verminderd aanpassingsvermogen</p></div></div>
                            <div class="question"><div class="observe"><input type="checkbox"><p>Gezinscoping: ontplooiingsmogelijkheden</p></div></div>
                            <div class="question"><div class="observe"><input type="checkbox"><p>Bedreigde gezinscoping</p></div></div>
                            <div class="question"><div class="observe"><input type="checkbox"><p>Gebrekkige gezinscoping</p></div></div>
                            <div class="question"><div class="observe"><input type="checkbox"><p>Dreiging van suïcidaliteit</p></div></div>
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