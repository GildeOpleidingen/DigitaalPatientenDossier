
<?php
session_start();
include '../../Database/DatabaseConnection.php';

$id = $_GET['id'];

if (isset($_REQUEST['navbutton'])) {
    //TODO: hier actie om data op te slaan in database.
    switch($_REQUEST['navbutton']) {
        case 'next': //action for next here
            header('Location: patroon01.php?id='.$id); //TODO: hier moet naar de hoofdpagina genavigeerd worden.
            break;
    
        case 'prev': //action for previous here
            header('Location: patroon10.php?id='.$id);
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
    <div class="main-content">
        <?php
            include '../../Includes/header.php';
        ?>
        <?php
            include '../../Includes/sidebar.php';
        ?>

        <div class="content">
            <div class="form-content">
            <div class="pages">11 Stressverwerkingspatroon (probleemhantering)</div>
                <div class="form">
                    <div class="questionnaire">
                        <div class="question"><p>Bent u gelovig?</p>
                            <div class="checkboxes">
                                <div class="question-answer">
                                    <input id="radio" type="radio" name="radio-1">
                                    <label>Ja</label>
                                    <div id="checkfield">
                                        <div class="question"><div class="observe"><input type="checkbox"><p>R-K</p></div></div>
                                        <div class="question"><div class="observe"><input type="checkbox"><p>Nederlands hervormd</p></div></div>
                                        <div class="question"><div class="observe"><input type="checkbox"><p>Gereformeerd</p></div></div>
                                        <div class="question"><div class="observe"><input type="checkbox"><p>Moslim</p></div></div>
                                        <div class="question"><div class="observe"><input type="checkbox"><p>Joods</p></div></div>
                                        <div class="question"><div class="observe"><input type="checkbox"><p>Anders, namelijk:</p></div><textarea  rows="1" cols="25" type="text"></textarea></div>
                                    </div>
                                </div>
                                <p>
                                    <input type="radio" name="radio-1">
                                    <label>Nee</label>
                                </p>
                            </div>
                        </div>
                        <div class="question"><p>- Heeft u behoefte aan religieuze activiteiten?</p>
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
                        <div class="question"><p>- Zijn er gebruiken ten aanzien van uw geloofsovertuiging waar rekening mee gehouden moet worden?</p>
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
                        <div class="question"><p>Ja, wanneer?</p><textarea  rows="1" cols="25" type="text"></textarea></div>
                        <div class="question"><p>Komen uw waarden en normen overeen met maatschappelijke waarden en normen?</p>
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
                        <div class="question"><p>Wat is uw etnische achtergrond</p><textarea  rows="1" cols="25" type="text"></textarea></div>
                        <div class="question"><p>- Zijn er gebruiken met betrekking tot uw etnische achtergrond waar rekening mee gehouden moet worden?</p>
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
                        <div class="question"><p>Ja, wanneer?</p><textarea  rows="1" cols="25" type="text"></textarea></div>

                        <div class="observation">
                            <h2>Verpleegkundige observatie bij dit patroon</h2>
                            <div class="question"><div class="observe"><input type="checkbox"><p>Geestelijke nood</p></div></div>
                            <div class="question"><div class="observe"><input type="checkbox"><p>Verandering in waarden en normen</p></div></div>
                            <div class="question"><div class="observe"><input type="checkbox"><p>Verandering in rolopvatting met betrekking tot etnische achtergrond</p></div></div>
                            <div class="question"><div class="observe"><input type="checkbox"><p>Verandering in rolinvulling met betrekking tot etnische achtergrond</p></div></div>
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