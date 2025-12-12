<?php
session_start();
include '../../database/DatabaseConnection.php';
include_once '../../models/autoload.php';
$Main = new Main();

$antwoorden = $Main->getPatternAnswers($_SESSION['clientId'], 11);

$boolArrayGeloof = isset($antwoorden['geloof_welk']) && $antwoorden['geloof_welk'] !== null ? str_split($antwoorden['geloof_welk']) : [];
$boolArrayObservatie = isset($antwoorden['observatie']) && $antwoorden['observatie'] !== null ? str_split($antwoorden['observatie']) : [];

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_REQUEST['navbutton'])) {
    // TODO: hier actie om data op te slaan in database.
    //Lees ingevulde gegevens.
    $gelovig = $_POST['gelovig'] ?? 0;    
    $geloof_anders = strval($_POST['geloof_anders']);
    $behoefte_religieuze_activiteit = $_POST['behoefte_religieuze_activiteit'];
    $gebruiken_tav_geloofsovertuiging = $_POST['gebruiken_tav_geloofsovertuiging'];
    $gebruiken_tav_geloofsovertuiging_welke = strval($_POST['gebruiken_tav_geloofsovertuiging_welke']);
    $gebruiken_tav_geloofsovertuiging_wanneer = strval($_POST['gebruiken_tav_geloofsovertuiging_wanneer']);
    $overeenkomst_waarden_normen = $_POST['overeenkomst_waarden_normen'];
    $etnische_achtergrond = strval($_POST['etnische_achtergrond']);
    $gebruiken_mbt_etnische_achtergrond = $_POST['gebruiken_mbt_etnische_achtergrond'];
    $gebruiken_mbt_etnische_achtergrond_welke = strval($_POST['gebruiken_mbt_etnische_achtergrond_welke']);
    $gebruiken_mbt_etnische_achtergrond_wanneer = strval($_POST['gebruiken_mbt_etnische_achtergrond_wanneer']);

    // array van checkboxes van gelovig tab
    $arr = array(!empty($_POST['geloof1']), !empty($_POST['geloof2']), !empty($_POST['geloof3']), !empty($_POST['geloof4']), !empty($_POST['geloof5']), !empty($_POST['geloof6']));
    $gelovig_welk = $Main->convertBoolArrayToString($arr);

    // array van checkboxes van observatie tab
    $arr = array(!empty($_POST['observatie1']), !empty($_POST['observatie2']), !empty($_POST['observatie3']), !empty($_POST['observatie4']));
    $observatie = $Main->convertBoolArrayToString($arr);
   
   
    //Haal vragenlijst ID op.
    $vragenlijstId = $Main->getVragenlijstId($_SESSION['clientId'], $_SESSION['loggedin_id']);
    // kijken of patroon11 bestaat door te kijken naar vragenlijst id
    $result = DatabaseConnection::getConn()->prepare("
                    SELECT p.id
                    FROM patroon11waardelevensovertuiging p
                    WHERE p.vragenlijstid =  ?");
    $result->bind_param("i", $vragenlijstId);
    $result->execute();
    $result = $result->get_result()->fetch_assoc();

    unset($_SESSION['patroonerror']);

    if ($result != null) {
        //update
        $result1 = DatabaseConnection::getConn()->prepare("UPDATE `patroon11waardelevensovertuiging`
            SET
            `gelovig`= ?,
            `geloof_welk`= ?,
            `geloof_anders`= ?,
            `behoefte_religieuze_activiteit`= ?,
            `gebruiken_tav_geloofsovertuiging`= ?,
            `gebruiken_tav_geloofsovertuiging_welke`= ?,
            `gebruiken_tav_geloofsovertuiging_wanneer`= ?,
            `overeenkomst_waarden_normen`= ?,
            `etnische_achtergrond`= ?,
            `gebruiken_mbt_etnische_achtergrond`= ?,
            `gebruiken_mbt_etnische_achtergrond_welke`= ?,
            `gebruiken_mbt_etnische_achtergrond_wanneer`= ?,
            `observatie`= ?
            WHERE `vragenlijstid`=?");
        if ($result1) {
            $result1->bind_param("issiissisisssi", $gelovig, $gelovig_welk, $geloof_anders, $behoefte_religieuze_activiteit, $gebruiken_tav_geloofsovertuiging,$gebruiken_tav_geloofsovertuiging_welke, $gebruiken_tav_geloofsovertuiging_wanneer, $overeenkomst_waarden_normen,$etnische_achtergrond,$gebruiken_mbt_etnische_achtergrond,$gebruiken_mbt_etnische_achtergrond_welke, $gebruiken_mbt_etnische_achtergrond_wanneer, $observatie, $vragenlijstId);
            $result1->execute();
        } else {
            // Handle error
            echo "Error preparing statement: " . DatabaseConnection::getConn()->error;
        }
    } else {
        //hier insert je alle data in patroon02
        try{
            $result2 = DatabaseConnection::getConn()->prepare("INSERT INTO `patroon11waardelevensovertuiging`(
                    `vragenlijstid`,
                    `gelovig`,
                    `geloof_welk`,
                    `geloof_anders`,
                    `behoefte_religieuze_activiteit`,
                    `gebruiken_tav_geloofsovertuiging`,
                    `gebruiken_tav_geloofsovertuiging_welke`,
                    `gebruiken_tav_geloofsovertuiging_wanneer`,
                    `overeenkomst_waarden_normen`,
                    `etnische_achtergrond`,
                    `gebruiken_mbt_etnische_achtergrond`,
                    `gebruiken_mbt_etnische_achtergrond_welke`,
                    `gebruiken_mbt_etnische_achtergrond_wanneer`,
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
                        ?,
                        ?)");
            $result2->bind_param("iissiissisisss", $vragenlijstId, $gelovig, $gelovig_welk, $geloof_anders, $behoefte_religieuze_activiteit, $gebruiken_tav_geloofsovertuiging,$gebruiken_tav_geloofsovertuiging_welke, $gebruiken_tav_geloofsovertuiging_wanneer, $overeenkomst_waarden_normen,$etnische_achtergrond,$gebruiken_mbt_etnische_achtergrond,$gebruiken_mbt_etnische_achtergrond_welke, $gebruiken_mbt_etnische_achtergrond_wanneer, $observatie);
            $result2->execute();
            $result2 = $result2->get_result();
        } catch (Exception $e) {
            // Display the alert box on next of previous page
            $_SESSION['patroonerror'] = 'Er ging iets fout, wijzigingen zijn NIET opgeslagen.';
            $_SESSION['patroonnr'] = '11. Stressverwerkingspatroon (probleemhantering)';
        }
    }

    switch ($_REQUEST['navbutton']) {
        case 'next': //action for next here
            header('Location: patroon01.php'); //TODO: hier moet naar de hoofdpagina genavigeerd worden.
            break;

        case 'prev': //action for previous here
            header('Location: patroon10.php');
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
                        <div class="h4 text-primary">11. Stressverwerkingspatroon (probleemhantering)</div>
                        <div class="form">
                            <div class="questionnaire">
                                <div class="question">
                                    <p>Bent u gelovig?</p>
                                    <div class="checkboxes">
                                        <div class="question-answer">
                                            <input id="radio" type="radio" value="1" name="gelovig" <?= (isset($antwoorden['gelovig']) && $antwoorden['gelovig'] == '1') ? "checked" : "" ?>>
                                            <label>Ja</label>
                                            <div id="checkfield">
                                                <div class="question">
                                                    <div class="observe"><input type="checkbox" <?= (isset($boolArrayGeloof[0]) && $boolArrayGeloof[0] == '1') ? "checked" : "" ?> name="geloof1">
                                                        <p>R-K</p>
                                                    </div>
                                                </div>
                                                <div class="question">
                                                    <div class="observe"><input type="checkbox" <?= (isset($boolArrayGeloof[1]) && $boolArrayGeloof[1] == '1') ? "checked" : "" ?> name="geloof2">
                                                        <p>Nederlands hervormd</p>
                                                    </div>
                                                </div>
                                                <div class="question">
                                                    <div class="observe"><input type="checkbox" <?= (isset($boolArrayGeloof[2]) && $boolArrayGeloof[2] == '1') ? "checked" : "" ?> name="geloof3">
                                                        <p>Gereformeerd</p>
                                                    </div>
                                                </div>
                                                <div class="question">
                                                    <div class="observe"><input type="checkbox" <?= (isset($boolArrayGeloof[3]) && $boolArrayGeloof[3] == '1') ? "checked" : "" ?> name="geloof4">
                                                        <p>Moslim</p>
                                                    </div>
                                                </div>
                                                <div class="question">
                                                    <div class="observe"><input type="checkbox" <?= (isset($boolArrayGeloof[4]) && $boolArrayGeloof[4] == '1') ? "checked" : "" ?> name="geloof5">
                                                        <p>Joods</p>
                                                    </div>
                                                </div>
                                                <div class="question">
                                                    <div class="observe"><input type="checkbox" <?= (isset($boolArrayGeloof[5]) && $boolArrayGeloof[5] == '1') ? "checked" : "" ?> name="geloof6">
                                                        <p>Anders, namelijk:</p>
                                                    </div><textarea rows="1" cols="25" type="text" name="geloof_anders"><?= isset($antwoorden['geloof_anders']) ? $antwoorden['geloof_anders'] : '' ?></textarea>
                                                </div>
                                            </div>
                                        </div>
                                        <p>
                                            <input type="radio" value="0" name="gelovig" <?= (!isset($antwoorden['gelovig']) || $antwoorden['gelovig'] == '0') ? "checked" : "" ?>>
                                            <label>Nee</label>
                                        </p>
                                    </div>
                                </div>
                                <div class="question">
                                    <p>- Heeft u behoefte aan religieuze activiteiten?</p>
                                    <div class="checkboxes">
                                        <p>
                                            <input type="radio" value="1" name="behoefte_religieuze_activiteit" <?= (isset($antwoorden['behoefte_religieuze_activiteit']) && $antwoorden['behoefte_religieuze_activiteit'] =='1') ? "checked" : "" ?>>
                                            <label>Ja</label>
                                        </p>
                                        <p>
                                            <input type="radio" value="0" name="behoefte_religieuze_activiteit" <?= (!isset($antwoorden['behoefte_religieuze_activiteit']) || $antwoorden['behoefte_religieuze_activiteit'] == '0') ? "checked" : "" ?>>
                                            <label>Nee</label>
                                        </p>
                                    </div>
                                </div>
                                <div class="question">
                                    <p>- Zijn er gebruiken ten aanzien van uw geloofsovertuiging waar rekening mee gehouden moet worden?</p>
                                    <div class="checkboxes">
                                        <div class="question-answer">
                                            <input id="radio" type="radio" value="1" name="gebruiken_tav_geloofsovertuiging" <?= (isset($antwoorden['gebruiken_tav_geloofsovertuiging']) && $antwoorden['gebruiken_tav_geloofsovertuiging'] == '1') ? "checked" : "" ?>>
                                            <label>Ja</label>
                                            <textarea rows="1" cols="25" id="checkfield" type="text" placeholder="welke?" name="gebruiken_tav_geloofsovertuiging_welke"><?= isset($antwoorden['gebruiken_tav_geloofsovertuiging_welke']) ? $antwoorden['gebruiken_tav_geloofsovertuiging_welke'] : '' ?></textarea>
                                        </div>
                                        <p>
                                            <input type="radio" value="0" name="gebruiken_tav_geloofsovertuiging" <?= (!isset($antwoorden['gebruiken_tav_geloofsovertuiging']) || $antwoorden['gebruiken_tav_geloofsovertuiging'] == '0') ? "checked" : "" ?>>
                                            <label>Nee</label>
                                        </p>
                                    </div>
                                </div>
                                <div class="question">
                                    <p>Ja, wanneer?</p><textarea rows="1" cols="25" type="text" name="gebruiken_tav_geloofsovertuiging_wanneer"><?= isset($antwoorden['gebruiken_tav_geloofsovertuiging_wanneer']) ? $antwoorden['gebruiken_tav_geloofsovertuiging_wanneer'] : '' ?></textarea>
                                </div>
                                <div class="question">
                                    <p>Komen uw waarden en normen overeen met maatschappelijke waarden en normen?</p>
                                    <div class="checkboxes">
                                        <p>
                                            <input type="radio" value="1" name="overeenkomst_waarden_normen" <?= (isset($antwoorden['overeenkomst_waarden_normen']) && $antwoorden['overeenkomst_waarden_normen'] == '1') ? "checked" : "" ?>>
                                            <label>Ja</label>
                                        </p>
                                        <p>
                                            <input type="radio" value="0" name="overeenkomst_waarden_normen" <?= (!isset($antwoorden['overeenkomst_waarden_normen']) || $antwoorden['overeenkomst_waarden_normen'] == '0') ? "checked" : "" ?>>
                                            <label>Nee</label>
                                        </p>
                                    </div>
                                </div>
                                <div class="question">
                                    <p>Wat is uw ethische achtergrond?</p><textarea rows="1" cols="25" type="text" name="etnische_achtergrond"><?= isset($antwoorden['etnische_achtergrond']) ? $antwoorden['etnische_achtergrond'] : '' ?></textarea>
                                </div>
                                <div class="question">
                                    <p>- Zijn er gebruiken met betrekking tot uw ethische achtergrond waar rekening mee gehouden moet worden?</p>
                                    <div class="checkboxes">
                                        <div class="question-answer">
                                            <input id="radio" type="radio" value="1" name="gebruiken_mbt_etnische_achtergrond" <?= (isset($antwoorden['gebruiken_mbt_etnische_achtergrond']) && $antwoorden['gebruiken_mbt_etnische_achtergrond'] =='1') ? "checked" : "" ?>>
                                            <label>Ja</label>
                                            <textarea rows="1" cols="25" id="checkfield" type="text" placeholder="welke?" name="gebruiken_mbt_etnische_achtergrond_welke"><?= isset($antwoorden['gebruiken_mbt_etnische_achtergrond_welke']) ? $antwoorden['gebruiken_mbt_etnische_achtergrond_welke'] : '' ?></textarea>
                                        </div>
                                        <p>
                                            <input type="radio" value="0" name="gebruiken_mbt_etnische_achtergrond" <?= (!isset($antwoorden['gebruiken_mbt_etnische_achtergrond']) || $antwoorden['gebruiken_mbt_etnische_achtergrond'] == '0') ? "checked" : "" ?>>
                                            <label>Nee</label>
                                        </p>
                                    </div>
                                </div>
                                <div class="question">
                                    <p>Ja, wanneer?</p><textarea rows="1" cols="25" type="text" name="gebruiken_mbt_etnische_achtergrond_wanneer"><?= isset($antwoorden['gebruiken_mbt_etnische_achtergrond']) ? $antwoorden['gebruiken_mbt_etnische_achtergrond_wanneer'] : '' ?></textarea>
                                </div>

                                <div class="observation">
                                    <h2>Verpleegkundige observatie bij dit patroon</h2>
                                    <div class="question">
                                        <div class="observe"><input type="checkbox" <?= (isset($boolArrayObservatie[0]) && $boolArrayObservatie[0] == '1') ? "checked" : "" ?> name="observatie1">
                                            <p>Geestelijke nood</p>
                                        </div>
                                    </div>
                                    <div class="question">
                                        <div class="observe"><input type="checkbox" <?= (isset($boolArrayObservatie[1]) && $boolArrayObservatie[1] == '1') ? "checked" : "" ?> name="observatie2">
                                            <p>Verandering in waarden en normen</p>
                                        </div>
                                    </div>
                                    <div class="question">
                                        <div class="observe"><input type="checkbox" <?= (isset($boolArrayObservatie[2]) && $boolArrayObservatie[2] == '1') ? "checked" : "" ?> name="observatie3">
                                            <p>Verandering in rolopvatting met betrekking tot ethische achtergrond</p>
                                        </div>
                                    </div>
                                    <div class="question">
                                        <div class="observe"><input type="checkbox" <?= (isset($boolArrayObservatie[3]) && $boolArrayObservatie[3] == '1') ? "checked" : "" ?> name="observatie4">
                                            <p>Verandering in rolinvulling met betrekking tot ethische achtergrond</p>
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