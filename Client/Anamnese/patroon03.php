
<?php
session_start();
include '../../Database/DatabaseConnection.php';
include '../../Functions/Functions.php';

$antwoorden = getPatternAnswers($_SESSION['clientId'], 3);

$boolArrayObservatie = str_split($antwoorden['observatie']);

if (isset($_REQUEST['navbutton'])) {
    $clientId = $_GET['id'];
    //TODO: hier actie om data op te slaan in database.
    switch($_REQUEST['navbutton']) {
        case 'next': //action for next here
            header('Location: patroon04.php?id='.$clientId);
            break;
    
        case 'prev': //action for previous here
            header('Location: patroon02.php?id='.$clientId);
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
            <div class="pages">3 Uitscheidingspatroon</div>
                <div class="form">
                    <div class="questionnaire">
                        <div class="question"><p>Heeft u problemen met ontlasting?</p>
                            <div class="checkboxes">
                                <div class="question-answer">
                                    <input id="radio" type="radio" name="ontlasting_probleem" <?= $antwoorden['ontlasting_probleem'] ? "checked" : "" ?>>
                                    <label>Ja</label>
                                    <textarea  rows="1" cols="25" id="checkfield" type="text" placeholder="en wel?" name="op_welke"><?= $antwoorden['op_welke'] ?? ""?></textarea>
                                </div>
                                <p>
                                    <input type="radio" name="ontlasting_probleem" <?= !$antwoorden['ontlasting_probleem'] ? "checked" : "" ?>>
                                    <label>Nee</label>
                                </p>
                            </div>
                        </div>
                        <div class="question"><p>- Wat doet u om deze problemen te bestrijden?</p><textarea  rows="1" cols="25" type="text" name="ontlasting_probleem_oplossing"><?= $antwoorden['op_preventie'] ?? "" ?></textarea></div>
                        <div class="question"><p>- Gebruikt u iets om uw stoelgang te reguleren?</p>
                            <div class="checkboxes">
                                <div class="question-answer">
                                    <input id="radio" type="radio" name="op_medicijnen" <?= $antwoorden['op_medicijnen'] ? "checked" : "" ?>>
                                    <label>Ja</label>
                                    <textarea  rows="1" cols="25" id="checkfield" type="text" placeholder="en wel?" name="op_medicijnen_welke"><?= $antwoorden['op_medicijnen_welke'] ?? ""?></textarea>
                                </div>
                                <p>
                                    <input type="radio" name="op_medicijnen" <?= !$antwoorden['op_medicijnen'] ? "checked" : "" ?>>
                                    <label>Nee</label>
                                </p>
                            </div>
                        </div>
                        <div class="question"><p>Heeft u problemen ten aanzien van het urineren?</p>
                            <div class="checkboxes">
                                <p>    
                                    <input type="radio" name="urineer_probleem" <?= $antwoorden['urineer_probleem'] ? "checked" : "" ?>>
                                    <label>Ja</label>
                                </p>
                                <p>
                                    <input type="radio" name="urineer_probleem" <?= !$antwoorden['urineer_probleem'] ? "checked" : "" ?>>
                                    <label>Nee</label>
                                </p>
                            </div>
                        </div>
                        <div class="question"><p>- Heeft u last van incontinentie?</p>
                            <div class="checkboxes">
                                <p>    
                                    <input type="radio" name="up_incontinentie" <?= $antwoorden['up_incontinentie'] ? "checked" : "" ?>>
                                    <label>Ja</label>
                                </p>
                                <p>
                                    <input type="radio" name="up_incontinentie" <?= !$antwoorden['up_incontinentie'] ? "checked" : "" ?>>
                                    <label>Nee</label>
                                </p>
                            </div>
                        </div>
                        <div class="question"><p>- Bent u hiervoor in behandeling?</p>
                            <div class="checkboxes">
                                <div class="question-answer">
                                    <input id="radio" type="radio" name="up_incontinentie_behandeling" <?= $antwoorden['up_incontinentie_behandeling'] ? "checked" : "" ?>>
                                    <label>Ja</label>
                                    <textarea  rows="1" cols="25" id="checkfield" type="text" placeholder="en wel?" name="up_incontinentie_behandeling"><?= $antwoorden['up_incontinentie_behandeling_welke'] ?? "" ?></textarea>
                                </div>
                                <p>
                                    <input type="radio" name="up_incontinentie_behandeling" <?= !$antwoorden['up_incontinentie_behandeling'] ? "checked" : "" ?>>
                                    <label>Nee</label>
                                </p>
                            </div>
                        </div>
                        <div class="question"><p>Hebt u last van transpiratie?</p>
                            <div class="checkboxes">
                                <div class="question-answer">
                                    <input id="radio" type="radio" name="transpiratie" <?= $antwoorden['transpiratie'] ? "checked" : "" ?>>
                                    <label>Ja</label>
                                    <textarea  rows="1" cols="25" id="checkfield" type="text" placeholder="en wel?" name="transpiratie_welke"><?= $antwoorden['transpiratie_welke'] ?? "" ?></textarea>
                                </div>
                                <p>
                                    <input type="radio" name="transpiratie" <?= !$antwoorden['transpiratie'] ? "checked" : "" ?>>
                                    <label>Nee</label>
                                </p>
                            </div>
                        </div>

                        <div class="observation">
                            <h2>Verpleegkundige observatie bij dit patroon</h2>
                            <div class="question"><div class="observe"><input type="checkbox" <?= $boolArrayObservatie[0] ? "checked" : ""?>><p>Colon-obstipatie</p></div></div>
                            <div class="question"><div class="observe"><input type="checkbox" <?= $boolArrayObservatie[1] ? "checked" : ""?>><p>Subjectief ervaren obstipatie</p></div></div>
                            <div class="question"><div class="observe"><input type="checkbox" <?= $boolArrayObservatie[2] ? "checked" : ""?>><p>Diarree</p></div></div>
                            <div class="question"><div class="observe"><input type="checkbox" <?= $boolArrayObservatie[3] ? "checked" : ""?>><p>Incontinentie van feces</p></div></div>
                            <div class="question"><div class="observe"><input type="checkbox" <?= $boolArrayObservatie[4] ? "checked" : ""?>><p>Verstoorde urine-uitscheiding</p></div></div>
                            <div class="question"><div class="observe"><input type="checkbox" <?= $boolArrayObservatie[5] ? "checked" : ""?>><p>Functionele urine-incontinentie</p></div></div>
                            <div class="question"><div class="observe"><input type="checkbox" <?= $boolArrayObservatie[6] ? "checked" : ""?>><p>Reflex-urine-incontinentie</p></div></div>
                            <div class="question"><div class="observe"><input type="checkbox" <?= $boolArrayObservatie[7] ? "checked" : ""?>><p>Stress-urine-incontinentie</p></div></div>
                            <div class="question"><div class="observe"><input type="checkbox" <?= $boolArrayObservatie[8] ? "checked" : ""?>><p>Volledige urine-incontinentie</p></div></div>
                            <div class="question"><div class="observe"><input type="checkbox" <?= $boolArrayObservatie[9] ? "checked" : ""?>><p>Urineretentie</p></div></div>
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