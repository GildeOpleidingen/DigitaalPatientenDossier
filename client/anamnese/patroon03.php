<?php
session_start();
include '../../database/DatabaseConnection.php';
include_once '../../classes/Main.php';
$Main = new Main();

$antwoorden = $Main->getPatternAnswers($_SESSION['clientId'], 3);

$boolArrayObservatie = str_split($antwoorden['observatie']);

if (isset($_REQUEST['navbutton'])) {
    //TODO: hier actie om data op te slaan in database.
    switch ($_REQUEST['navbutton']) {
        case 'next': //action for next here
            header('Location: patroon04.php');
            break;

        case 'prev': //action for previous here
            header('Location: patroon02.php');
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
            <div class="content">
                <div class="mt-4 mb-3 bg-white p-3" style="height: 96%; overflow: auto;">
                    <p class="card-text">
                    <div class="form-content">
                        <div class="h4 text-primary">3. Uitscheidingspatroon</div>
                        <div class="form">
                            <div class="questionnaire">
                                <div class="question">
                                    <p>Heeft u problemen met ontlasting?</p>
                                    <div class="checkboxes">
                                        <div class="question-answer">
                                            <input id="radio" type="radio" name="ontlasting_probleem" <?= isset($antwoorden['ontlasting_probleem']) ? "checked" : "" ?>>
                                            <label>Ja</label>
                                            <textarea rows="1" cols="25" id="checkfield" type="text" placeholder="en wel?" name="op_welke"><?= isset($antwoorden['op_welke']) ?></textarea>
                                        </div>
                                        <p>
                                            <input type="radio" name="ontlasting_probleem" <?= !isset($antwoorden['ontlasting_probleem']) ? "checked" : "" ?>>
                                            <label>Nee</label>
                                        </p>
                                    </div>
                                </div>
                                <div class="question">
                                    <p>- Wat doet u om deze problemen te bestrijden?</p><textarea rows="1" cols="25" type="text" name="ontlasting_probleem_oplossing"><?= isset($antwoorden['op_preventie']) ?></textarea>
                                </div>
                                <div class="question">
                                    <p>- Gebruikt u iets om uw stoelgang te reguleren?</p>
                                    <div class="checkboxes">
                                        <div class="question-answer">
                                            <input id="radio" type="radio" name="op_medicijnen" <?= isset($antwoorden['op_medicijnen']) ? "checked" : "" ?>>
                                            <label>Ja</label>
                                            <textarea rows="1" cols="25" id="checkfield" type="text" placeholder="en wel?" name="op_medicijnen_welke"><?= isset($antwoorden['op_medicijnen_welke']) ?></textarea>
                                        </div>
                                        <p>
                                            <input type="radio" name="op_medicijnen" <?= !isset($antwoorden['op_medicijnen']) ? "checked" : "" ?>>
                                            <label>Nee</label>
                                        </p>
                                    </div>
                                </div>
                                <div class="question">
                                    <p>Heeft u problemen ten aanzien van het urineren?</p>
                                    <div class="checkboxes">
                                        <p>
                                            <input type="radio" name="urineer_probleem" <?= isset($antwoorden['urineer_probleem']) ? "checked" : "" ?>>
                                            <label>Ja</label>
                                        </p>
                                        <p>
                                            <input type="radio" name="urineer_probleem" <?= !isset($antwoorden['urineer_probleem']) ? "checked" : "" ?>>
                                            <label>Nee</label>
                                        </p>
                                    </div>
                                </div>
                                <div class="question">
                                    <p>- Heeft u last van incontinentie?</p>
                                    <div class="checkboxes">
                                        <p>
                                            <input type="radio" name="up_incontinentie" <?= isset($antwoorden['up_incontinentie']) ? "checked" : "" ?>>
                                            <label>Ja</label>
                                        </p>
                                        <p>
                                            <input type="radio" name="up_incontinentie" <?= !isset($antwoorden['up_incontinentie']) ? "checked" : "" ?>>
                                            <label>Nee</label>
                                        </p>
                                    </div>
                                </div>
                                <div class="question">
                                    <p>- Bent u hiervoor in behandeling?</p>
                                    <div class="checkboxes">
                                        <div class="question-answer">
                                            <input id="radio" type="radio" name="up_incontinentie_behandeling" <?= isset($antwoorden['up_incontinentie_behandeling']) ? "checked" : "" ?>>
                                            <label>Ja</label>
                                            <textarea rows="1" cols="25" id="checkfield" type="text" placeholder="en wel?" name="up_incontinentie_behandeling"><?= isset($antwoorden['up_incontinentie_behandeling_welke']) ?></textarea>
                                        </div>
                                        <p>
                                            <input type="radio" name="up_incontinentie_behandeling" <?= !isset($antwoorden['up_incontinentie_behandeling']) ? "checked" : "" ?>>
                                            <label>Nee</label>
                                        </p>
                                    </div>
                                </div>
                                <div class="question">
                                    <p>Hebt u last van transpiratie?</p>
                                    <div class="checkboxes">
                                        <div class="question-answer">
                                            <input id="radio" type="radio" name="transpiratie" <?= isset($antwoorden['transpiratie']) ? "checked" : "" ?>>
                                            <label>Ja</label>
                                            <textarea rows="1" cols="25" id="checkfield" type="text" placeholder="en wel?" name="transpiratie_welke"><?= isset($antwoorden['transpiratie_welke']) ?></textarea>
                                        </div>
                                        <p>
                                            <input type="radio" name="transpiratie" <?= !isset($antwoorden['transpiratie']) ? "checked" : "" ?>>
                                            <label>Nee</label>
                                        </p>
                                    </div>
                                </div>

                                <div class="observation">
                                    <h2>Verpleegkundige observatie bij dit patroon</h2>
                                    <div class="question">
                                        <div class="observe"><input type="checkbox" <?= isset($boolArrayObservatie[0]) ? "checked" : "" ?> name="observatie1">
                                            <p>Colon-obstipatie</p>
                                        </div>
                                    </div>
                                    <div class="question">
                                        <div class="observe"><input type="checkbox" <?= isset($boolArrayObservatie[1]) ? "checked" : "" ?> name="observatie2">
                                            <p>Subjectief ervaren obstipatie</p>
                                        </div>
                                    </div>
                                    <div class="question">
                                        <div class="observe"><input type="checkbox" <?= isset($boolArrayObservatie[2]) ? "checked" : "" ?> name="observatie3">
                                            <p>Diarree</p>
                                        </div>
                                    </div>
                                    <div class="question">
                                        <div class="observe"><input type="checkbox" <?= isset($boolArrayObservatie[3]) ? "checked" : "" ?> name="observatie4">
                                            <p>Incontinentie van feces</p>
                                        </div>
                                    </div>
                                    <div class="question">
                                        <div class="observe"><input type="checkbox" <?= isset($boolArrayObservatie[4]) ? "checked" : "" ?> name="observatie5">
                                            <p>Verstoorde urine-uitscheiding</p>
                                        </div>
                                    </div>
                                    <div class="question">
                                        <div class="observe"><input type="checkbox" <?= isset($boolArrayObservatie[5]) ? "checked" : "" ?> name="observatie6">
                                            <p>Functionele urine-incontinentie</p>
                                        </div>
                                    </div>
                                    <div class="question">
                                        <div class="observe"><input type="checkbox" <?= isset($boolArrayObservatie[6]) ? "checked" : "" ?> name="observatie7">
                                            <p>Reflex-urine-incontinentie</p>
                                        </div>
                                    </div>
                                    <div class="question">
                                        <div class="observe"><input type="checkbox" <?= isset($boolArrayObservatie[7]) ? "checked" : "" ?> name="observatie8">
                                            <p>Stress-urine-incontinentie</p>
                                        </div>
                                    </div>
                                    <div class="question">
                                        <div class="observe"><input type="checkbox" <?= isset($boolArrayObservatie[8]) ? "checked" : "" ?> name="observatie9">
                                            <p>Volledige urine-incontinentie</p>
                                        </div>
                                    </div>
                                    <div class="question">
                                        <div class="observe"><input type="checkbox" <?= isset($boolArrayObservatie[9]) ? "checked" : "" ?> name="observatie10">
                                            <p>Urineretentie</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="submit">
                            <button name="navbutton" class="btn btn-secondary" type="submit" value="prev">Vorige</button>
                            <button name="navbutton" class="btn btn-secondary" type="submit" value="next">Volgende</button>
                        </div>
                    </div>
                </div>
            </div>
    </form>
</body>

</html>