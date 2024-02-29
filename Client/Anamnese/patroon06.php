
<?php
session_start();
include '../../Database/DatabaseConnection.php';

$id = $_GET['id'];

if (isset($_REQUEST['navbutton'])) {
    //TODO: hier actie om data op te slaan in database.
    switch($_REQUEST['navbutton']) {
        case 'next': //action for next here
            header('Location: patroon07.php?id='.$id);
            break;
    
        case 'prev': //action for previous here
            header('Location: patroon05.php?id='.$id);
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
            <div class="pages">6 Cognitie- en waarnemingspatroon</div>
                <div class="form">
                    <div class="questionnaire">
                        <div class="question"><p>Heeft u moeite met horen?</p>
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
                        <div class="question"><p>- Hoort u stemmen die op dat moment door personen in uw omgeving niet gehoord (kunnen) worden?</p>
                            <div class="checkboxes">
                                <div class="question-answer">
                                    <input id="radio" type="radio" name="radio-2">
                                    <label>Ja</label>
                                    <textarea  rows="1" cols="25" id="checkfield" type="text" placeholder="wat hoort u?"></textarea>
                                </div>
                                <p>
                                    <input type="radio" name="radio-2">
                                    <label>Nee</label>
                                </p>
                            </div>
                        </div>
                        <div class="question"><p>Heeft u moeite met zien?</p>
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
                        <div class="question"><p>- Ziet u personen, dieren, objecten die op dat moment door personen in uw omgeving niet gezien (kunnen) worden?</p>
                            <div class="checkboxes">
                                <div class="question-answer">
                                    <input id="radio" type="radio" name="radio-4">
                                    <label>Ja</label>
                                    <textarea  rows="1" cols="25" id="checkfield" type="text" placeholder="wat ziet u?"></textarea>
                                </div>
                                <p>
                                    <input type="radio" name="radio-4">
                                    <label>Nee</label>
                                </p>
                            </div>
                        </div>
                        <div class="question"><p>Ruikt u iets dat op dat moment door personen in uw omgeving niet geroken (kan) worden?</p>
                            <div class="checkboxes">
                                <div class="question-answer">
                                    <input id="radio" type="radio" name="radio-5">
                                    <label>Ja</label>
                                    <textarea  rows="1" cols="25" id="checkfield" type="text" placeholder="wat ruikt u?"></textarea>
                                </div>
                                <p>
                                    <input type="radio" name="radio-5">
                                    <label>Nee</label>
                                </p>
                            </div>
                        </div>
                        <div class="question"><p>Zijn er de afgelopen tijd veranderingen opgetreden in uw denken?</p>
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
                        <div class="question"><p>Heeft u moeite met spreken?</p>
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
                        <div class="question"><p>- Welke taal spreekt u thuis?</p><textarea  rows="1" cols="25" type="text"></textarea></div>
                        <div class="question"><p>Zijn er de afgelopen tijd veranderingen opgetreden in uw concentratievermogen?</p>
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
                        <div class="question"><p>Kunt u moeilijker dagelijkse beslissingen nemen?</p>
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
                        <div class="question"><p>Zijn er de afgelopen tijd veranderingen opgetreden in uw geheugen?</p>
                            <div class="checkboxes">
                                <p>    
                                    <input type="radio" name="radio-10">
                                    <label>Ja</label>
                                </p>
                                <p>
                                    <input type="radio" name="radio-10">
                                    <label>Nee</label>
                                </p>
                            </div>
                        </div>
                        <div class="question"><p>Zijn er de afgelopen tijd veranderingen opgetreden in uw oriëntatie?</p>
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
                        <div class="question"><p>Gebruikt u medicatie die uw oriëntatie, reactievermogen of denken beïnvloeden?</p>
                            <div class="checkboxes">
                                <div class="question-answer">
                                    <input id="radio" type="radio" name="radio-12">
                                    <label>Ja</label>
                                    <textarea  rows="1" cols="25" id="checkfield" type="text" placeholder="welke?"></textarea>
                                </div>
                                <p>
                                    <input type="radio" name="radio-12">
                                    <label>Nee</label>
                                </p>
                            </div>
                        </div>
                        <div class="question"><p>Gebruikt u verdovende/stimulerende middelen?</p>
                            <div class="checkboxes">
                                <div class="question-answer">
                                    <input id="radio" type="radio" name="radio-13">
                                    <label>Ja</label>
                                    <div id="checkfield">
                                        <div class="question"><div class="observe"><input type="checkbox"><p>Softdrugs</p></div><textarea  rows="1" cols="25" type="text"></textarea></div>
                                        <div class="question"><div class="observe"><input type="checkbox"><p>Harddrugs</p></div><textarea  rows="1" cols="25" type="text"></textarea></div>
                                        <div class="question"><div class="observe"><input type="checkbox"><p>Alcohol</p></div><textarea  rows="1" cols="25" type="text"></textarea></div>
                                        <div class="question"><div class="observe"><input type="checkbox"><p>Anders, namelijk:</p></div><textarea  rows="1" cols="25" type="text"></textarea></div>
                                    </div>
                                </div>
                                <p>
                                    <input type="radio" name="radio-13">
                                    <label>Nee</label>
                                </p>
                            </div>
                        </div>
                        <div class="question"><p>Heeft u pijnklachten?</p>
                            <div class="checkboxes">
                                <p>    
                                    <input type="radio" name="radio-14">
                                    <label>Ja</label>
                                </p>
                                <p>
                                    <input type="radio" name="radio-14">
                                    <label>Nee</label>
                                </p>
                            </div>
                        </div>
                        <div class="question"><p>- Waar, wanneer, soort pijn?</p><textarea  rows="1" cols="25" type="text"></textarea></div>
                        <div class="question"><p>- Wat doet u doorgaans tegen de pijn?</p><textarea  rows="1" cols="25" type="text"></textarea></div>
                        <div class="question"><p>- Wat doet u om pijn/ongemak zoveel mogelijk te voorkomen?</p><textarea  rows="1" cols="25" type="text"></textarea></div>
                        
                        <div class="observation">
                            <h2>Verpleegkundige observatie bij dit patroon</h2>
                            <div class="question"><div class="observe"><input type="checkbox"><p>Wijziging in de waarneming</p></div></div>
                            <div class="question"><div class="observe"><input type="checkbox"><p>Verstoord denken</p></div></div>
                            <div class="question"><div class="observe"><input type="checkbox"><p>Kennistekort</p></div></div>
                            <div class="question"><div class="observe"><input type="checkbox"><p>Dreigend cognitietekort</p></div></div>
                            <div class="question"><div class="observe"><input type="checkbox"><p>Beslisconflict</p></div></div>
                            <div class="question"><div class="observe"><input type="checkbox"><p>Achterdocht</p></div></div>
                            <div class="question"><div class="observe"><input type="checkbox"><p>Acute verwardheid</p></div></div>
                            <div class="question"><div class="observe"><input type="checkbox"><p>Pijn (specificeer type en locatie)</p></div></div>
                            <div class="question"><div class="observe"><input type="checkbox"><p>Chronische pijn (specificeer type en locatie)</p></div></div>
                            <div class="question"><div class="observe"><input type="checkbox"><p>Middelenmisbruik:</p></div></div>
                            <div class="question"><div class="observe"><input type="checkbox"><p>Alcohol</p></div></div>
                            <div class="question"><div class="observe"><input type="checkbox"><p>Drugs</p></div></div>
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