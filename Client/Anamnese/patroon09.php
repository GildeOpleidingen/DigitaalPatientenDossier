
<?php
session_start();
include '../../Database/DatabaseConnection.php';

$id = $_GET['id'];

if (isset($_REQUEST['navbutton'])) {
    //TODO: hier actie om data op te slaan in database.
    switch($_REQUEST['navbutton']) {
        case 'next': //action for next here
            header('Location: patroon10.php?id='.$id);
            break;
    
        case 'prev': //action for previous here
            header('Location: patroon08.php?id='.$id);
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
            <div class="pages">9 Seksualiteits- en voorplantingspatroon</div>
                <div class="form">
                    <div class="questionnaire">
                        <div class="question"><p>Is uw seksuele beleving veranderd?</p>
                            <div class="checkboxes">
                                <div class="question-answer">
                                    <input id="radio" type="radio" name="radio-1">
                                    <label>Ja</label>
                                    <textarea  rows="1" cols="25" id="checkfield" type="text" placeholder="door?"></textarea>
                                </div>
                                <p>
                                    <input type="radio" name="radio-1">
                                    <label>Nee</label>
                                </p>
                            </div>
                        </div>
                        <div class="question"><p>- Is er de laatste tijd verandering gekomen in uw seksuele gedrag?</p>
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
                        <div class="question"><p>- Heeft u wisselende seksuele contacten?</p>
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
                        <div class="question"><p>- Houdt u bij uw seksuele activiteiten rekening met veilig vrijen?</p>
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
                        <div class="question"><p>Gebruikt u anticonceptiemiddelen?</p>
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
                        <div class="question"><p>- Heeft u problemen bij het gebruik van anticonceptie-middelen?</p>
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
                        <div class="question"><p>Wat is uw seksuele gerichtheid?</p> 
                            <div class="observation">
                                <div class="question"><div class="observe"><input type="checkbox"><p>Heteroseksueel</p></div></div>
                                <div class="question"><div class="observe"><input type="checkbox"><p>Biseksueel</p></div></div>
                                <div class="question"><div class="observe"><input type="checkbox"><p>Homoseksueel</p></div></div>
                            </div>
                        </div>
                        <div class="question"><p>- Ondervindt u problemen bij u zelf of bij anderen ten aanzien van uw seksuele gerichtheid?</p>
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
                        <div class="question"><p>Heeft u last (gehad) van seksueel overdraagbare aandoeningen (soa)?</p>
                            <div class="checkboxes">
                                <div class="question-answer">
                                    <input id="radio" type="radio" name="radio-5">
                                    <label>Ja</label>
                                    <textarea  rows="1" cols="25" id="checkfield" type="text" placeholder="en wel?"></textarea>
                                </div>
                                <p>
                                    <input type="radio" name="radio-5">
                                    <label>Nee</label>
                                </p>
                            </div>
                        </div>

                        <div class="observation">
                            <h2>Verpleegkundige observatie bij dit patroon</h2>
                            <div class="question"><div class="observe"><input type="checkbox"><p>Gewijzigde seksuele gewoonten</p></div></div>
                            <div class="question"><div class="observe"><input type="checkbox"><p>Seksueel disfunctioneren</p></div></div>
                            <div class="question"><div class="observe"><input type="checkbox"><p>Verkrachtingssyndroom gecompliceerde vorm</p></div></div>
                            <div class="question"><div class="observe"><input type="checkbox"><p>Verkrachtingssyndroom stille vorm</p></div></div>
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