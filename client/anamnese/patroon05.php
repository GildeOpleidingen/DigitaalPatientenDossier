<?php
session_start();
include '../../database/DatabaseConnection.php';
include_once '../../models/autoload.php';
$Main = new Main();

$antwoorden = $Main->getPatternAnswers($_SESSION['clientId'], 5);

$boolArrayInslaapmiddel = isset($antwoorden['gebruik_inslaapmiddel_welke']) && $antwoorden['gebruik_inslaapmiddel_welke'] !== null ? str_split($antwoorden['gebruik_inslaapmiddel_welke']) : array_fill(0, 6, '0');
$boolArrayObservatie = isset($antwoorden['observatie']) && $antwoorden['observatie'] != null ? str_split($antwoorden['observatie']) : [];

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_REQUEST['navbutton'])) {
    $verandering_inslaaptijd = $_POST['verandering_inslaaptijd'];
    $verandering_inslaaptijd_blijktuit = strval($_POST['verandering_inslaaptijd_blijktuit']);
    $verandering_kwaliteit_slapen = $_POST['verandering_kwaliteit_slapen'];
    $verandering_kwaliteit_slapen_blijktuit = strval($_POST['verandering_kwaliteit_slapen_blijktuit']);
    $gebruik_inslaapmiddel = $_POST['gebruik_inslaapmiddel'];
    
    $arr = array(
        isset($_POST['inslaapmiddel1']) ? '1' : '0',
        isset($_POST['inslaapmiddel2']) ? '1' : '0',
        isset($_POST['inslaapmiddel3']) ? '1' : '0',
        isset($_POST['inslaapmiddel4']) ? '1' : '0',
        isset($_POST['inslaapmiddel5']) ? '1' : '0',
        isset($_POST['inslaapmiddel6']) ? '1' : '0'
    );
    $gebruik_inslaapmiddel_welke = implode('', $arr);
    $gebruik_inslaapmiddel_anders = isset($_POST['gebruik_inslaapmiddel_anders']) ? strval($_POST['gebruik_inslaapmiddel_anders']) : '';
    
    $slaapduur = $_POST['slaapduur'];
    $uitgerust_wakker = $_POST['uitgerust_wakker'];
    $dromen_nachtmerries = $_POST['dromen_nachtmerries'];
    $rustperiodes_overdag = $_POST['rustperiodes_overdag'];
    $gemakkelijk_ontspannen = $_POST['gemakkelijk_ontspannen'];
    
    $arr = array(!empty($_POST['observatie1']));
    $observatie = $Main->convertBoolArrayToString($arr);

    $vragenlijstId = $Main->getVragenlijstId($_SESSION['clientId'], $_SESSION['loggedin_id']);
    
    $result = DatabaseConnection::getConn()->prepare("
        SELECT p.id
        FROM patroon05slaaprust p
        WHERE p.vragenlijstid = ?");
    $result->bind_param("i", $vragenlijstId);
    $result->execute();
    $result = $result->get_result()->fetch_assoc();

    unset($_SESSION['patroonerror']);

    if ($result != null) {
        $result1 = DatabaseConnection::getConn()->prepare("UPDATE `patroon05slaaprust` 
            SET
            `verandering_inslaaptijd` = ?,
            `verandering_inslaaptijd_blijktuit` = ?,
            `verandering_kwaliteit_slapen` = ?,
            `verandering_kwaliteit_slapen_blijktuit` = ?,
            `gebruik_inslaapmiddel` = ?,
            `gebruik_inslaapmiddel_welke` = ?,
            `gebruik_inslaapmiddel_anders` = ?,
            `slaapduur` = ?,
            `uitgerust_wakker` = ?,
            `dromen_nachtmerries` = ?,
            `rustperiodes_overdag` = ?,
            `gemakkelijk_ontspannen` = ?,
            `observatie` = ?
            WHERE `vragenlijstid` = ?");
        if ($result1) {
            $result1->bind_param("isisisisiiiiis",
                $verandering_inslaaptijd,
                $verandering_inslaaptijd_blijktuit,
                $verandering_kwaliteit_slapen,
                $verandering_kwaliteit_slapen_blijktuit,
                $gebruik_inslaapmiddel,
                $gebruik_inslaapmiddel_welke,
                $gebruik_inslaapmiddel_anders,
                $slaapduur,
                $uitgerust_wakker,
                $dromen_nachtmerries,
                $rustperiodes_overdag,
                $gemakkelijk_ontspannen,
                $observatie,
                $vragenlijstId);
            $result1->execute();
        } else {
            echo "Error preparing statement: " . DatabaseConnection::getConn()->error;
        }
    } else {
        try {
            $result2 = DatabaseConnection::getConn()->prepare("INSERT INTO `patroon05slaaprust`(
                `vragenlijstid`,
                `verandering_inslaaptijd`,
                `verandering_inslaaptijd_blijktuit`,
                `verandering_kwaliteit_slapen`,
                `verandering_kwaliteit_slapen_blijktuit`,
                `gebruik_inslaapmiddel`,
                `gebruik_inslaapmiddel_welke`,
                `gebruik_inslaapmiddel_anders`,
                `slaapduur`,
                `uitgerust_wakker`,
                `dromen_nachtmerries`,
                `rustperiodes_overdag`,
                `gemakkelijk_ontspannen`,
                `observatie`)
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
            $result2->bind_param("isisisisiiiiis",
                $vragenlijstId,
                $verandering_inslaaptijd,
                $verandering_inslaaptijd_blijktuit,
                $verandering_kwaliteit_slapen,
                $verandering_kwaliteit_slapen_blijktuit,
                $gebruik_inslaapmiddel,
                $gebruik_inslaapmiddel_welke,
                $gebruik_inslaapmiddel_anders,
                $slaapduur,
                $uitgerust_wakker,
                $dromen_nachtmerries,
                $rustperiodes_overdag,
                $gemakkelijk_ontspannen,
                $observatie);
            
            $result2->execute();
        } catch (Exception $e) {
            $_SESSION['patroonerror'] = 'Er ging iets fout, wijzigingen zijn NIET opgeslagen.';
            $_SESSION['patroonnr'] = '5. Slaap- en rustpatroon';
        }
    }

    switch ($_REQUEST['navbutton']) {
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
                        <?php if(isset($_SESSION['patroonerror'])){?>
                            <div class="alert alert-warning">
                                <strong>Waarschuwing!</strong> <?php echo $_SESSION['patroonerror'] ?> in <?php echo $_SESSION['patroonnr'] ?>
                            </div>
                        <?php  }?>
                        <div class="h4 text-primary">5. Slaap- en rustpatroon</div>
                        <div class="form">
                            <div class="questionnaire">
                                <div class="question">
                                    <p>Is er in de afgelopen periode verandering in de de duur van uw slaap gekomen?</p>
                                    <div class="checkboxes">
                                        <div class="question-answer">
                                            <input id="radio" type="radio" value="1" name="verandering_inslaaptijd" <?= (isset($antwoorden['verandering_inslaaptijd']) && $antwoorden['verandering_inslaaptijd'] == '1') ? "checked" : "" ?>>
                                            <label>Ja</label>
                                            <textarea rows="1" cols="25" id="checkfield" type="text" placeholder="en wel?" name="verandering_inslaaptijd_blijktuit"><?= isset($antwoorden['verandering_inslaaptijd_blijktuit']) ? $antwoorden['verandering_inslaaptijd_blijktuit'] : '' ?></textarea>
                                        </div>
                                        <p>
                                            <input type="radio" value="0" name="verandering_inslaaptijd" <?= (!isset($antwoorden['verandering_inslaaptijd']) || $antwoorden['verandering_inslaaptijd'] == '0') ? "checked" : "" ?>>
                                            <label>Nee</label>
                                        </p>
                                    </div>
                                </div>
                                <div class="question">
                                    <p>- Is er verandering ontstaan in de kwaliteit van uw slaap (in- en/of doorslaapprobleem)?</p>
                                    <div class="checkboxes">
                                        <div class="question-answer">
                                            <input id="radio" type="radio" value="1" name="verandering_kwaliteit_slapen" <?= (isset($antwoorden['verandering_kwaliteit_slapen']) && $antwoorden['verandering_kwaliteit_slapen'] == '1') ? "checked" : "" ?>>
                                            <label>Ja</label>
                                            <textarea rows="1" cols="25" id="checkfield" type="text" placeholder="en wel?" name="verandering_kwaliteit_slapen_blijktuit"><?= isset($antwoorden['verandering_kwaliteit_slapen_blijktuit']) ? $antwoorden['verandering_kwaliteit_slapen_blijktuit'] : '' ?></textarea>
                                        </div>
                                        <p>
                                            <input type="radio" value="0" name="verandering_kwaliteit_slapen" <?= (!isset($antwoorden['verandering_kwaliteit_slapen']) || $antwoorden['verandering_kwaliteit_slapen'] == '0') ? "checked" : "" ?>>
                                            <label>Nee</label>
                                        </p>
                                    </div>
                                </div>
                                <div class="question">
                                    <p>- Doet u iets om (in) te kunnen slapen?</p>
                                    <div class="checkboxes">
                                        <div class="question-answer">
                                            <input id="radio" type="radio" value="1" name="gebruik_inslaapmiddel" <?= (isset($antwoorden['gebruik_inslaapmiddel']) && $antwoorden['gebruik_inslaapmiddel'] == '1') ? "checked" : "" ?>>
                                            <label>Ja</label>
                                            <div id="checkfield">
                                                <div class="question">
                                                    <div class="observe"><input type="checkbox" <?= (isset($boolArrayInslaapmiddel[0]) && $boolArrayInslaapmiddel[0] === '1') ? "checked" : "" ?> name="inslaapmiddel1">
                                                        <p>Medicijngebruik</p>
                                                    </div>
                                                </div>
                                                <div class="question">
                                                    <div class="observe"><input type="checkbox" <?= (isset($boolArrayInslaapmiddel[1]) && $boolArrayInslaapmiddel[1] === '1') ? "checked" : "" ?> name="inslaapmiddel2">
                                                        <p>Beweging</p>
                                                    </div>
                                                </div>
                                                <div class="question">
                                                    <div class="observe"><input type="checkbox" <?= (isset($boolArrayInslaapmiddel[2]) && $boolArrayInslaapmiddel[2] === '1') ? "checked" : "" ?> name="inslaapmiddel3">
                                                        <p>Alcohol/drugs</p>
                                                    </div>
                                                </div>
                                                <div class="question">
                                                    <div class="observe"><input type="checkbox" <?= (isset($boolArrayInslaapmiddel[3]) && $boolArrayInslaapmiddel[3] === '1') ? "checked" : "" ?> name="inslaapmiddel4">
                                                        <p>Eten/drinken</p>
                                                    </div>
                                                </div>
                                                <div class="question">
                                                    <div class="observe"><input type="checkbox" <?= (isset($boolArrayInslaapmiddel[4]) && $boolArrayInslaapmiddel[4] === '1') ? "checked" : "" ?> name="inslaapmiddel5">
                                                        <p>Douche/bad</p>
                                                    </div>
                                                </div>

                                            </div>
                                        </div>
                                        <p>
                                            <input type="radio" value="0" name="gebruik_inslaapmiddel" <?= (!isset($antwoorden['gebruik_inslaapmiddel']) || $antwoorden['gebruik_inslaapmiddel'] == '0') ? "checked" : "" ?>>
                                            <label>Nee</label>
                                        </p>
                                    </div>
                                </div>
                                <div class="question">
                                    <p>- Hoe lang slaapt u nomaal?</p>
                                    <p><input type="number" step=0.5 min="0" max="24" value="<?= isset($antwoorden['slaapduur']) ? $antwoorden['slaapduur'] : '' ?>" name="slaapduur"> uur</p>
                                </div>
                                <div class="question">
                                    <p>- Voelt u zich uitgerust als u wakker wordt?</p>
                                    <div class="checkboxes">
                                        <p>
                                            <input type="radio" value="1" name="uitgerust_wakker" <?= (isset($antwoorden['uitgerust_wakker']) && $antwoorden['uitgerust_wakker'] == '1') ? "checked" : "" ?>>
                                            <label>Ja</label>
                                        </p>
                                        <p>
                                            <input type="radio" value="0" name="uitgerust_wakker" <?= (!isset($antwoorden['uitgerust_wakker']) || $antwoorden['uitgerust_wakker'] == '0') ? "checked" : "" ?>>
                                            <label>Nee</label>
                                        </p>
                                    </div>
                                </div>
                                <div class="question">
                                    <p>Heeft u last van dromen, nachtmerries?</p>
                                    <div class="checkboxes">
                                        <p>
                                            <input type="radio" value="1" name="dromen_nachtmerries" <?= (isset($antwoorden['dromen_nachtmerries']) && $antwoorden['dromen_nachtmerries'] == '1') ? "checked" : "" ?>>
                                            <label>Ja</label>
                                        </p>
                                        <p>
                                            <input type="radio" value="0" name="dromen_nachtmerries" <?= (!isset($antwoorden['dromen_nachtmerries']) || $antwoorden['dromen_nachtmerries'] == '0') ? "checked" : "" ?>>
                                            <label>Nee</label>
                                        </p>
                                    </div>
                                </div>
                                <div class="question">
                                    <p>- Neemt u rustperioden overdag?</p>
                                    <div class="checkboxes">
                                        <p>
                                            <input type="radio" value="1" name="rustperiodes_overdag" <?= (isset($antwoorden['rustperiodes_overdag']) && $antwoorden['rustperiodes_overdag'] == '1') ? "checked" : "" ?>>
                                            <label>Ja</label>
                                        </p>
                                        <p>
                                            <input type="radio" value="0" name="rustperiodes_overdag" <?= (!isset($antwoorden['rustperiodes_overdag']) || $antwoorden['rustperiodes_overdag'] == '0') ? "checked" : "" ?>>
                                            <label>Nee</label>
                                        </p>
                                    </div>
                                </div>
                                <div class="question">
                                    <p>- Kunt u zich gemakkelijk ontspannen?</p>
                                    <div class="checkboxes">
                                        <p>
                                            <input type="radio" value="1" name="gemakkelijk_ontspannen" <?= (isset($antwoorden['gemakkelijk_ontspannen']) && $antwoorden['gemakkelijk_ontspannen'] == '1') ? "checked" : "" ?>>
                                            <label>Ja</label>
                                        </p>
                                        <p>
                                            <input type="radio" value="0" name="gemakkelijk_ontspannen" <?= (!isset($antwoorden['gemakkelijk_ontspannen']) || $antwoorden['gemakkelijk_ontspannen'] == '0') ? "checked" : "" ?>>
                                            <label>Nee</label>
                                        </p>
                                    </div>
                                </div>

                                <div class="observation">
                                    <h2>Verpleegkundige observatie bij dit patroon</h2>
                                    <div class="question">
                                        <div class="observe"><input type="checkbox" <?= (isset($boolArrayObservatie[0]) && $boolArrayObservatie[0] == '1') ? "checked" : "" ?> name="observatie1">
                                            <p>Verstoord slaap- en rustpatroon</p>
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