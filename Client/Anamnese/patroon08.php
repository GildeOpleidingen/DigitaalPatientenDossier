
<?php
session_start();
include '../../Database/DatabaseConnection.php';

$id = $_GET['id'];

if (isset($_REQUEST['navbutton'])) {
    //TODO: hier actie om data op te slaan in database.
    switch($_REQUEST['navbutton']) {
        case 'next': //action for next here
            header('Location: patroon09.php?id='.$id);
            break;
    
        case 'prev': //action for previous here
            header('Location: patroon07.php?id='.$id);
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
            <div class="pages">8 Rollen- en relatiepatroon</div>
                <div class="form">
                    <div class="questionnaire">
                    <div class="question"><p>Bent u getrouwd/samenwonend?</p>
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
                        <div class="question"><p>- Heeft u kinderen?</p>
                            <div class="checkboxes">
                                <p>    
                                    <input type="radio" name="radio-2">
                                    <label>Ja</label>
                                </p>
                                <p>
                                    <input type="radio" name="radio-2">
                                    <label>Nee</label>
                                </p>
                            </div>
                        </div>
                        <div class="question"><p>- Bent u tevreden over uw thuissituatie?</p>
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
                        <div class="question"><p>Heeft u een vrienden-/familiekring waar u steun aan heeft?</p>
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
                        <div class="question"><p>Wat is uw beroep of huidige bron van inkomsten?</p><textarea  rows="1" cols="25" type="text"></textarea></div>
                        <div class="question"><p>- Is er de afgelopen tijd een verandering geweest in uw financiële situatie?</p>
                            <div class="checkboxes">
                                <div class="question-answer">
                                    <input id="radio" type="radio" name="radio-5">
                                    <label>Ja</label>
                                    <textarea  rows="1" cols="25" id="checkfield" type="text" placeholder="welke?"></textarea>
                                </div>
                                <p>
                                    <input type="radio" name="radio-5">
                                    <label>Nee</label>
                                </p>
                            </div>
                        </div>
                        <div class="question"><p>- Verwacht u in de nabije toekomst een verandering in uw financiële situatie?</p>
                            <div class="checkboxes">
                                <div class="question-answer">
                                    <input id="radio" type="radio" name="radio-6">
                                    <label>Ja</label>
                                    <textarea  rows="1" cols="25" id="checkfield" type="text" placeholder="welke?"></textarea>
                                </div>
                                <p>
                                    <input type="radio" name="radio-6">
                                    <label>Nee</label>
                                </p>
                            </div>
                        </div>
                        <div class="question"><p>Wat is uw opleiding?</p><textarea  rows="1" cols="25" type="text"></textarea></div>
                        <div class="question"><p>Is er de laatste tijd verandering gekomen in uw sociale contacten?</p>
                            <div class="checkboxes">
                                <div class="question-answer">
                                    <input id="radio" type="radio" name="radio-7">
                                    <label>Ja</label>
                                    <textarea  rows="1" cols="25" id="checkfield" type="text" placeholder="welke?"></textarea>
                                </div>
                                <p>
                                    <input type="radio" name="radio-7">
                                    <label>Nee</label>
                                </p>
                            </div>
                        </div>
                        <div class="question"><p>Komt u uit een groot gezin?</p>
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
                        <div class="question"><p>- Wat was u plaats in dat gezin?</p><textarea  rows="1" cols="25" type="text"></textarea></div>
                        <div class="question"><p>- Hoe verliepen de onderlinge contacten?</p><textarea  rows="1" cols="25" type="text"></textarea></div>
                        <div class="question"><p>- Was er sprake van agressie in dat gezin?</p>
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
                        <div class="question"><p>Bent u lid van verenigingen?</p>
                            <div class="checkboxes">
                                <div class="question-answer">
                                    <input id="radio" type="radio" name="radio-10">
                                    <label>Ja</label>
                                    <textarea  rows="1" cols="25" id="checkfield" type="text" placeholder="welke?"></textarea>
                                </div>
                                <p>
                                    <input type="radio" name="radio-10">
                                    <label>Nee</label>
                                </p>
                            </div>
                        </div>
                        <div class="question"><p>- Hoe verliepen de contacten met derden (collega's, kennissen, verenigingsgenoten)?</p><textarea  rows="1" cols="25" type="text"></textarea></div>
                        <div class="question"><p>Heeft u de laatst tijd een verlies geleden (werk, personen, enzovoort)?</p>
                            <div class="checkboxes">
                                <div class="question-answer">
                                    <input id="radio" type="radio" name="radio-11">
                                    <label>Ja</label>
                                    <textarea  rows="1" cols="25" id="checkfield" type="text" placeholder="en wel?"></textarea>
                                </div>
                                <p>
                                    <input type="radio" name="radio-11">
                                    <label>Nee</label>
                                </p>
                            </div>
                        </div>

                        <div class="observation">
                            <h2>Verpleegkundige observatie bij dit patroon</h2>
                            <div class="question"><div class="observe"><input type="checkbox"><p>Verstoorde verbale communicatie (afwijkende groei en ontwikkeling in communicatieve vaardigheden)</p></div></div>
                            <div class="question"><div class="observe"><input type="checkbox"><p>Anticiperende rouw</p></div></div>
                            <div class="question"><div class="observe"><input type="checkbox"><p>Disfunctionele rouw</p></div></div>
                            <div class="question"><div class="observe"><input type="checkbox"><p>Gewijzigde gezinsprocessen</p></div></div>
                            <div class="question"><div class="observe"><input type="checkbox"><p>(Dreigend) ouderschapstekort</p></div></div>
                            <div class="question"><div class="observe"><input type="checkbox"><p>Ouderrolconflict</p></div></div>
                            <div class="question"><div class="observe"><input type="checkbox"><p>Inadequate sociale interacties</p></div></div>
                            <div class="question"><div class="observe"><input type="checkbox"><p>Afwijkende groei en ontikkeling in sociale vaardigheden</p></div></div>
                            <div class="question"><div class="observe"><input type="checkbox"><p>Sociaal isolement</p></div></div>
                            <div class="question"><div class="observe"><input type="checkbox"><p>Verstoorde rolvervulling</p></div></div>
                            <div class="question"><div class="observe"><input type="checkbox"><p>Onopgelost onafhankelijkheids-/afhankelijkheidsconflict</p></div></div>
                            <div class="question"><div class="observe"><input type="checkbox"><p>Sociale afwijzing</p></div></div>
                            <div class="question"><div class="observe"><input type="checkbox"><p>(Dreigende) overbelasting van de mantelzorg)</p></div></div>
                            <div class="question"><div class="observe"><input type="checkbox"><p>Mantelzorgtekort</p></div></div>
                            <div class="question"><div class="observe"><input type="checkbox"><p>Dreigend geweld:</p></div></div>
                            <div class="question"><div class="observe"><input type="checkbox"><p>gericht op andere</p></div></div>
                            <div class="question"><div class="observe"><input type="checkbox"><p>gericht op voorwerpen (meubilair, enzovoort)</p></div></div>
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