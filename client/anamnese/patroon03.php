<?php
session_start();
include '../../database/DatabaseConnection.php';
include_once '../../models/autoload.php';
$Main = new Main();

$antwoorden = $Main->getPatternAnswers($_SESSION['clientId'], 3);

$boolArrayObservatie = isset($antwoorden['observatie']) && $antwoorden['observatie'] !== null ? str_split($antwoorden['observatie']) : [];

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_REQUEST['navbutton'])) {
    //Lees ingevulde gegevens.
    $ontlasting_probleem = $_POST['ontlasting_probleem'];
    $onlasting_op_welke = strval($_POST['op_welke']);
    $ontlasting_probleem_oplossing = strval($_POST['ontlasting_probleem_oplossing']);
    $op_medicijnen = $_POST['op_medicijnen'];
    $op_medicijnen_welke = strval($_POST['op_medicijnen_welke']);
    $urineer_probleem = $_POST['urineer_probleem'];
    $up_incontinentie = $_POST['up_incontinentie'];
    $up_incontinentie_behandeling = $_POST['up_incontinentie_behandeling'];
    $up_incontinentie_behandeling_welke = strval($_POST['up_incontinentie_behandeling_welke']);
    $transpiratie = $_POST['transpiratie'];
    $transpiratie_welke = strval($_POST['transpiratie_welke']);

    // array van checkboxes van observatie tab
    $arr = array(!empty($_POST['observatie1']), !empty($_POST['observatie2']), !empty($_POST['observatie3']), !empty($_POST['observatie4']), !empty($_POST['observatie5']), !empty($_POST['observatie6']), !empty($_POST['observatie7']), !empty($_POST['observatie8']), !empty($_POST['observatie9']), !empty($_POST['observatie10']));
    $observatie = $Main->convertBoolArrayToString($arr);

     //Haal vragenlijst ID op.
    $vragenlijstId = $Main->getVragenlijstId($_SESSION['clientId'], $_SESSION['loggedin_id']);

    // kijken of patroon10 bestaat door te kijken naar vragenlijst id
    $result = DatabaseConnection::getConn()->prepare("
                    SELECT p.id
                    FROM patroon03uitscheiding p
                    WHERE p.vragenlijstid =  ?");
    $result->bind_param("i", $vragenlijstId);
    $result->execute();
    $result = $result->get_result()->fetch_assoc();

    unset($_SESSION['patroonerror']);
    
    //opslaan in database.
    if ($result != null) {
        //update
        $result1 = DatabaseConnection::getConn()->prepare("UPDATE `patroon03uitscheiding`
            SET
            `ontlasting_probleem`= ?,
            `op_welke`= ?,
            `op_preventie`= ?,
            `op_medicijnen`= ?,
            `op_medicijnen_welke`= ?,
            `urineer_probleem`= ?,
            `up_incontinentie`= ?,
            `up_incontinentie_behandeling`= ?,
            `up_incontinentie_behandeling_welke`= ?,
            `transpiratie`= ?,
            `transpiratie_welke`= ?,
            `observatie`= ?
            WHERE `vragenlijstid`=?");
        if ($result1) {
            $result1->bind_param("issisiiisissi", 
            $ontlasting_probleem,
            $onlasting_op_welke,
            $ontlasting_probleem_oplossing,
            $op_medicijnen,
            $op_medicijnen_welke,
            $urineer_probleem,
            $up_incontinentie,
            $up_incontinentie_behandeling,
            $up_incontinentie_behandeling_welke,
            $transpiratie,
            $transpiratie_welke,
            $observatie, 
            $vragenlijstId);
            $result1->execute();
        } else {
            // Handle error
            echo "Error preparing statement: " . DatabaseConnection::getConn()->error;
        }
    } else {
        try{
            $result2 = DatabaseConnection::getConn()->prepare("INSERT INTO `patroon03uitscheiding`(
                    `vragenlijstid`,
                    `ontlasting_probleem`,
                    `op_welke`,
                    `op_preventie`,
                    `op_medicijnen`,
                    `op_medicijnen_welke`,
                    `urineer_probleem`,
                    `up_incontinentie`,
                    `up_incontinentie_behandeling`,
                    `up_incontinentie_behandeling_welke`,
                    `transpiratie`,
                    `transpiratie_welke`,
                    `observatie`)
                VALUES (
                        ?,
                        ?,
                        ?,
                        ?,
                        ?,
                        ?,
                        ?,
                        ?,
                        ?,
                        ?,
                        ?,
                        ?,
                        ?)");
            $result2->bind_param("iissisiiisiss", 
                $vragenlijstId, 
                $ontlasting_probleem,
                $onlasting_op_welke,
                $ontlasting_probleem_oplossing,
                $op_medicijnen,
                $op_medicijnen_welke,
                $urineer_probleem,
                $up_incontinentie,
                $up_incontinentie_behandeling,
                $up_incontinentie_behandeling_welke,
                $transpiratie,
                $transpiratie_welke,
                $observatie);
                
            $result2->execute();
            $result2 = $result2->get_result();
        } catch (Exception $e) {
            // Display the alert box on next of previous page
            $_SESSION['patroonerror'] = 'Er ging iets fout, wijzigingen zijn NIET opgeslagen.';
            $_SESSION['patroonnr'] = '3. Uitscheidingspatroon';
        }
    }

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
    <link rel="Stylesheet" href="../../assets/css/client/patronen.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" 
          rel="stylesheet" 
          integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" 
          crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css" 
          integrity="sha512-Kc323vGBEqzTmouAECnVceyQqyqdsSiqLQISBL29aUW4U/M7pSPA/gEUZQqv1cwx4OnYxTxve5UMg5GT6L4JJg==" 
          crossorigin="anonymous" referrerpolicy="no-referrer" />
    <title>Anamnese</title>
</head>

<body style="overflow: hidden;">
    <form action="" method="post">
        <div class="main">
            <?php
            include '../../includes/n-header.php';
            include '../../includes/n-sidebar.php';
            ?>
            <div class="mt-5 pt-5 content">
                <div class="mt-4 mb-3 bg-white p-3" style="height: 90%; overflow: auto;">
                    <p class="card-text">
                    <div class="form-content">
                        <div class="h4 text-primary">3. Uitscheidingspatroon</div>
                        <div class="form">
                            <div class="questionnaire">
                                <div class="question">
                                    <p>Heeft u problemen met ontlasting?</p>
                                    <div class="checkboxes">
                                        <div class="question-answer">
                                            <input id="radio" type="radio" value="1" name="ontlasting_probleem" <?= (isset($antwoorden['ontlasting_probleem']) && $antwoorden['ontlasting_probleem'] == '1') ? "checked" : "" ?>>
                                            <label>Ja</label>
                                            <textarea rows="1" cols="25" id="checkfield" type="text" placeholder="en wel?" name="op_welke"><?= isset($antwoorden['op_welke']) ? $antwoorden['op_welke'] : '' ?></textarea>
                                        </div>
                                        <p>
                                            <input type="radio" value="0" name="ontlasting_probleem" <?= (!isset($antwoorden['ontlasting_probleem']) || $antwoorden['ontlasting_probleem'] == '0') ? "checked" : "" ?>>
                                            <label>Nee</label>
                                        </p>
                                    </div>
                                </div>
                                <div class="question">
                                    <p>- Wat doet u om deze problemen te bestrijden?</p><textarea rows="1" cols="25" type="text" name="ontlasting_probleem_oplossing"><?= isset($antwoorden['op_preventie']) ? $antwoorden['op_preventie'] : '' ?></textarea>
                                </div>
                                <div class="question">
                                    <p>- Gebruikt u iets om uw stoelgang te reguleren?</p>
                                    <div class="checkboxes">
                                        <div class="question-answer">
                                            <input id="radio" type="radio" value="1" name="op_medicijnen" <?= (isset($antwoorden['op_medicijnen']) && $antwoorden['op_medicijnen'] == '1') ? "checked" : "" ?>>
                                            <label>Ja</label>
                                            <textarea rows="1" cols="25" id="checkfield" type="text" placeholder="en wel?" name="op_medicijnen_welke"><?= isset($antwoorden['op_medicijnen_welke']) ? $antwoorden['op_medicijnen_welke'] : '' ?></textarea>
                                        </div>
                                        <p>
                                            <input type="radio" value="0" name="op_medicijnen" <?= (!isset($antwoorden['op_medicijnen']) || $antwoorden['op_medicijnen'] == '0') ? "checked" : "" ?>>
                                            <label>Nee</label>
                                        </p>
                                    </div>
                                </div>
                                <div class="question">
                                    <p>Heeft u problemen ten aanzien van het urineren?</p>
                                    <div class="checkboxes">
                                        <p>
                                            <input type="radio" value="1" name="urineer_probleem" <?= (isset($antwoorden['urineer_probleem']) && $antwoorden['urineer_probleem'] == '1') ? "checked" : "" ?>>
                                            <label>Ja</label>
                                        </p>
                                        <p>
                                            <input type="radio" value="0" name="urineer_probleem" <?= (!isset($antwoorden['urineer_probleem']) || $antwoorden['urineer_probleem'] == '0') ? "checked" : "" ?>>
                                            <label>Nee</label>
                                        </p>
                                    </div>
                                </div>
                                <div class="question">
                                    <p>- Heeft u last van incontinentie?</p>
                                    <div class="checkboxes">
                                        <p>
                                            <input type="radio" value="1" name="up_incontinentie" <?= (isset($antwoorden['up_incontinentie']) && $antwoorden['up_incontinentie'] == '1') ? "checked" : "" ?>>
                                            <label>Ja</label>
                                        </p>
                                        <p>
                                            <input type="radio" value="0" name="up_incontinentie" <?= (!isset($antwoorden['up_incontinentie']) || $antwoorden['up_incontinentie'] == '0') ? "checked" : "" ?>>
                                            <label>Nee</label>
                                        </p>
                                    </div>
                                </div>
                                <div class="question">
                                    <p>- Bent u hiervoor in behandeling?</p>
                                    <div class="checkboxes">
                                        <div class="question-answer">
                                            <input id="radio" type="radio" value="1" name="up_incontinentie_behandeling" <?= (isset($antwoorden['up_incontinentie_behandeling']) && $antwoorden['up_incontinentie_behandeling'] == '1') ? "checked" : "" ?>>
                                            <label>Ja</label>
                                            <textarea rows="1" cols="25" id="checkfield" type="text" placeholder="en wel?" name="up_incontinentie_behandeling_welke"><?= isset($antwoorden['up_incontinentie_behandeling_welke']) ? $antwoorden['up_incontinentie_behandeling_welke'] : '' ?></textarea>
                                        </div>
                                        <p>
                                            <input type="radio" value="0" name="up_incontinentie_behandeling" <?= (!isset($antwoorden['up_incontinentie_behandeling']) || $antwoorden['up_incontinentie_behandeling'] == '0') ? "checked" : "" ?>>
                                            <label>Nee</label>
                                        </p>
                                    </div>
                                </div>
                                <div class="question">
                                    <p>Hebt u last van transpiratie?</p>
                                    <div class="checkboxes">
                                        <div class="question-answer">
                                            <input id="radio" type="radio" value="1" name="transpiratie" <?= (isset($antwoorden['transpiratie']) && $antwoorden['transpiratie'] == '1') ? "checked" : "" ?>>
                                            <label>Ja</label>
                                            <textarea rows="1" cols="25" id="checkfield" type="text" placeholder="en wel?" name="transpiratie_welke"><?= isset($antwoorden['transpiratie_welke']) ? $antwoorden['transpiratie_welke'] : '' ?></textarea>
                                        </div>
                                        <p>
                                            <input type="radio" value="0" name="transpiratie" <?= (!isset($antwoorden['transpiratie']) || $antwoorden['transpiratie'] == '0') ? "checked" : "" ?>>
                                            <label>Nee</label>
                                        </p>
                                    </div>
                                </div>

                                <div class="observation">
                                    <h2>Verpleegkundige observatie bij dit patroon</h2>
                                    <div class="question">
                                        <div class="observe"><input type="checkbox" <?= (isset($boolArrayObservatie[0]) && $boolArrayObservatie[0] == '1') ? "checked" : "" ?> name="observatie1">
                                            <p>Colon-obstipatie</p>
                                        </div>
                                    </div>
                                    <div class="question">
                                        <div class="observe"><input type="checkbox" <?= (isset($boolArrayObservatie[1]) && $boolArrayObservatie[1] == '1') ? "checked" : "" ?> name="observatie2">
                                            <p>Subjectief ervaren obstipatie</p>
                                        </div>
                                    </div>
                                    <div class="question">
                                        <div class="observe"><input type="checkbox" <?= (isset($boolArrayObservatie[2]) && $boolArrayObservatie[2] == '1') ? "checked" : "" ?> name="observatie3">
                                            <p>Diarree</p>
                                        </div>
                                    </div>
                                    <div class="question">
                                        <div class="observe"><input type="checkbox" <?= (isset($boolArrayObservatie[3]) && $boolArrayObservatie[3] == '1') ? "checked" : "" ?> name="observatie4">
                                            <p>Incontinentie van feces</p>
                                        </div>
                                    </div>
                                    <div class="question">
                                        <div class="observe"><input type="checkbox" <?= (isset($boolArrayObservatie[4]) && $boolArrayObservatie[4] == '1') ? "checked" : "" ?> name="observatie5">
                                            <p>Verstoorde urine-uitscheiding</p>
                                        </div>
                                    </div>
                                    <div class="question">
                                        <div class="observe"><input type="checkbox" <?= (isset($boolArrayObservatie[5]) && $boolArrayObservatie[5] == '1') ? "checked" : "" ?> name="observatie6">
                                            <p>Functionele urine-incontinentie</p>
                                        </div>
                                    </div>
                                    <div class="question">
                                        <div class="observe"><input type="checkbox" <?= (isset($boolArrayObservatie[6]) && $boolArrayObservatie[6] == '1') ? "checked" : "" ?> name="observatie7">
                                            <p>Reflex-urine-incontinentie</p>
                                        </div>
                                    </div>
                                    <div class="question">
                                        <div class="observe"><input type="checkbox" <?= (isset($boolArrayObservatie[7]) && $boolArrayObservatie[7] == '1') ? "checked" : "" ?> name="observatie8">
                                            <p>Stress-urine-incontinentie</p>
                                        </div>
                                    </div>
                                    <div class="question">
                                        <div class="observe"><input type="checkbox" <?= (isset($boolArrayObservatie[8]) && $boolArrayObservatie[8] == '1') ? "checked" : "" ?> name="observatie9">
                                            <p>Volledige urine-incontinentie</p>
                                        </div>
                                    </div>
                                    <div class="question">
                                        <div class="observe"><input type="checkbox" <?= (isset($boolArrayObservatie[9]) && $boolArrayObservatie[9] == '1') ? "checked" : "" ?> name="observatie10">
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
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" 
            integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" 
            crossorigin="anonymous"></script> 
</body>

</html>