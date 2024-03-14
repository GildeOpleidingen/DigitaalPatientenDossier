<?php
session_start();
include '../../Database/DatabaseConnection.php';
include '../../Functions/Functions.php';
include '../../Functions/AnamneseFunctions.php';

$antwoorden = getPatternAnswers($_SESSION['clientId'], 1);

$boolArrayObservatie = str_split($antwoorden['observatie']);
//id van de medewerker
$medewerker_id = $_SESSION['loggedin_id'];
$clientId = $_SESSION['clientId'];

if (isset($_REQUEST['navbutton'])) {
    $algemene_gezondheid = $_POST['algemene_gezondheid'];
    $gezondheids_bezigheid = $_POST['gezondheids_bezigheid'];
    $rookt = ($_POST['rookt']);
    $rookt_hoeveelheid = $_POST['rookt_hoeveelheid'];
    $drinkt = $_POST['drinkt'];
    $drinkt_hoeveelheid = $_POST['drinkt_hoeveelheid'];
    $besmettelijke_aandoening = $_POST['besmettelijke_aandoening'];
    $besmettelijke_aandoening_welke = $_POST['besmettelijke_aandoening_welke'];
    $alergieen = $_POST['alergieen'];
    $alergieen_welke = $_POST['alergieen_welke'];
    $oorzaak_huidige_toestand = $_POST['oorzaak_huidige_toestand'];
    $oht_actie = $_POST['oht_actie'];
    $oht_hoe_effectief = $_POST['oht_hoe_effectief'];
    $oht_wat_nodig = $_POST['oht_wat_nodig'];
    $oht_wat_belangrijk = $_POST['oht_wat_belangrijk'];
    $oht_reactie_op_advies = $_POST['oht_reactie_op_advies'];
    $preventie= $_POST['oht_reactie_op_advies'];
    // array van checkboxes van observatie tab
    $observatie = array(!empty($_POST['observatie1']), !empty($_POST['observatie2']), !empty($_POST['observatie3']), !empty($_POST['observatie4']), !empty($_POST['observatie5']), !empty($_POST['observatie6']), !empty($_POST['observatie7']), !empty($_POST['observatie8']), !empty($_POST['observatie9']), !empty($_POST['observatie10']));

    $observatie = convertBoolArrayToString($observatie);

    $result = getVragenlijstId($clientId);
    if ($result != null){
        $vragenlijstId = $result;
    } else {
        $result = insertVragenlijst($_SESSION['clientId'], $medewerker_id);
        $vragenlijstId = $result;
    }

    $result = checkIfPatternExists("patroon01gezondheidsbeleving", $vragenlijstId);

    if ($result != null) {
        //update
        $result1 = DatabaseConnection::getConn()->prepare("UPDATE `patroon01gezondheidsbeleving`
            SET
            `algemene_gezondheid`=?,
            `gezondheids_bezigheid`=?,
            `rookt`=?,
            `rookt_hoeveelheid`=?,
            `drinkt`=?,
            `drinkt_hoeveelheid`=?,
            `besmettelijke_aandoening`=?,
            `besmettelijke_aandoening_welke`=?,
            `alergieen`=?,
            `alergieen_welke`=?,
            `oorzaak_huidige_toestand`=?,
            `oht_actie`=?,
            `oht_hoe_effectief`=?,
            `oht_wat_nodig`=?,
            `oht_wat_belangrijk`=?,
            `oht_reactie_op_advies`=?,
            `preventie`=?,
            `observatie`=?
           WHERE `vragenlijstid`=?");
        $result1->bind_param("ssisisisisssssssssi", $algemene_gezondheid, $gezondheids_bezigheid, $rookt, $rookt_hoeveelheid, $drinkt, $drinkt_hoeveelheid, $besmettelijke_aandoening, $besmettelijke_aandoening_welke, $alergieen, $alergieen_welke, $oorzaak_huidige_toestand, $oht_actie, $oht_hoe_effectief, $oht_wat_nodig, $oht_wat_belangrijk, $oht_reactie_op_advies, $preventie, $observatie,$vragenlijst_id);
        $result1->execute();
        $result1 = $result1->get_result();

    }else{
        //hier insert je alle data in patroon01
        $result2 = DatabaseConnection::getConn()->prepare( "INSERT INTO `patroon01gezondheidsbeleving`(`vragenlijstid`, `algemene_gezondheid`, `gezondheids_bezigheid`, `rookt`, `rookt_hoeveelheid`, `drinkt`, `drinkt_hoeveelheid`, `besmettelijke_aandoening`, `besmettelijke_aandoening_welke`, `alergieen`, `alergieen_welke`, `oorzaak_huidige_toestand`, `oht_actie`, `oht_hoe_effectief`, `oht_wat_nodig`, `oht_wat_belangrijk`, `oht_reactie_op_advies`, `preventie`, `observatie`)
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
                    ?,
                    ?,
                    ?,
                    ?,
                    ?,
                    ?)");
        $result2->bind_param("issisisisisssssssss", $vragenlijst_id, $algemene_gezondheid, $gezondheids_bezigheid, $rookt, $rookt_hoeveelheid, $drinkt, $drinkt_hoeveelheid, $besmettelijke_aandoening, $besmettelijke_aandoening_welke, $alergieen, $alergieen_welke, $oorzaak_huidige_toestand, $oht_actie, $oht_hoe_effectief, $oht_wat_nodig, $oht_wat_belangrijk, $oht_reactie_op_advies, $preventie, $observatie);
        $result2->execute();
        $result2 = $result2->get_result();

    }
switch($_REQUEST['navbutton']) {
        case 'next': //action for next here
            header('Location: patroon02.php');
            break;

        case 'prev': //action for previous here
            header('Location: patroon11.php'); //TODO: hier moet naar de hoofdpagina genavigeerd worden.
            break;
    }
    exit;
}

$client = DatabaseConnection::getConn()->prepare("SELECT * FROM client WHERE id = ?");
$client->bind_param("i", $_SESSION['clientId']);
$client->execute();
$client = $client->get_result()->fetch_assoc();

if ($client == null) {
    header("Location: ../../index.php");
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
        include '../../Includes/sidebar.php';
        ?>
            <div class="content">
                <div class="form-content">
                    <div class="pages">1 Patroon van gezondheidsbeleving en -instandhouding</div>
                    <div class="form">
                        <div class="questionnaire">
                            <div class="question"><p>Hoe is uw gezondheid in het algemeen?</p><textarea  rows="1" cols="25" type="text" name="algemene_gezondheid"><?= $antwoorden['algemene_gezondheid']?></textarea></div>
                            <div class="question"><p>Wat doet u om gezond te blijven?</p><textarea  rows="1" cols="25" type="text" name="gezondheids_bezigheid"><?= $antwoorden['gezondheids_bezigheid']?></textarea></div>
                            <div class="question"><p>- Rookt u?</p>
                                <div class="checkboxes">
                                    <div class="question-answer">
                                        <input id="radio" type="radio" name="rookt" value="1" <?= $antwoorden['rookt'] ? "checked" : "" ?>>
                                        <label>Ja</label>
                                        <textarea  rows="1" cols="25" id="checkfield" type="text" placeholder="hoeveel?" name="rookt_hoeveelheid"><?= $antwoorden['rookt_hoeveelheid']?></textarea>
                                    </div>
                                    <p>
                                        <input type="radio" name="rookt" value="0" <?= !$antwoorden['rookt'] ? "checked" : "" ?>>
                                        <label>Nee</label>
                                    </p>
                                </div>
                            </div>
                            <div class="question"><p>- Drinkt u?</p>
                                <div class="checkboxes">
                                    <div class="question-answer">
                                        <input id="radio" type="radio" name="drinkt" value="1" <?= $antwoorden['drinkt'] ? "checked" : "" ?>>
                                        <label>Ja</label>
                                        <textarea  rows="1" cols="25" id="checkfield" type="text" placeholder="hoeveel?" name="drinkt_hoeveelheid"><?= $antwoorden['drinkt_hoeveelheid'] ?></textarea>
                                    </div>
                                    <p>
                                        <input type="radio" name="drinkt" value="0" <?= !$antwoorden['drinkt'] ? "checked" : "" ?>>
                                        <label>Nee</label>
                                    </p>
                                </div>
                            </div>
                            <div class="question"><p>Heeft u momenteel een infectie of overdraagbare besmettelijke aandoening?</p>
                                <div class="checkboxes">
                                    <div class="question-answer">
                                        <input id="radio" type="radio" name="besmettelijke_aandoening" value="1" <?= $antwoorden['besmettelijke_aandoening'] ? "checked" : "" ?>>
                                        <label>Ja</label>
                                        <textarea  rows="1" cols="25" id="checkfield" type="text" placeholder="en wel?" name="besmettelijke_aandoening_welke"><?= $antwoorden['besmettelijke_aandoening_welke'] ?></textarea>
                                    </div>
                                    <p>
                                        <input type="radio" name="besmettelijke_aandoening" value="0" <?= !$antwoorden['besmettelijke_aandoening'] ? "checked" : "" ?>>
                                        <label>Nee</label>
                                    </p>
                                </div>
                            </div>
                            <div class="question"><p>Bent u ergens allergisch voor?</p>
                                <div class="checkboxes">
                                    <div class="question-answer">
                                        <input id="radio" type="radio" name="alergieen" value="1" <?= $antwoorden['alergieen'] ? "checked" : "" ?>>
                                        <label>Ja</label>
                                        <textarea  rows="1" cols="25" id="checkfield" type="text" placeholder="en wel?" name="alergieen_welke"><?= $antwoorden['alergieen_welke'] ?></textarea>
                                    </div>
                                    <p>
                                        <input type="radio" name="alergieen" value="0" <?= !$antwoorden['alergieen'] ? "checked" : "" ?>>
                                        <label>Nee</label>
                                    </p>
                                </div>
                            </div>
                            <div class="question"><p>Wat denkt u dat de oorzaak is van uw huidige situatie/toestand?</p><textarea  rows="1" cols="25" type="text" name="oorzaak_huidige_toestand"><?= $antwoorden['oorzaak_huidige_toestand'] ?></textarea></div>
                            <div class="question"><p>- Wat heeft u eraan gedaan?</p><textarea  rows="1" cols="25" type="text" name="oht_actie"><?= $antwoorden['oht_actie'] ?></textarea></div>
                            <div class="question"><p>- Hoe effectief was dat?</p><textarea  rows="1" cols="25" type="text" name="oht_hoe_effectief"><?= $antwoorden['oht_hoe_effectief'] ?></textarea></div>
                            <div class="question"><p>- Hoe kunnen wij u helpen?</p><textarea  rows="1" cols="25" type="text" name="oht_wat_nodig"><?= $antwoorden['oht_wat_nodig'] ?></textarea></div>
                            <div class="question"><p>- Wat is voor u belangrijk tijdens het verblijf op deze afdeling?</p><textarea  rows="1" cols="25" type="text" name="oht_wat_belangrijk"><?= $antwoorden['oht_wat_belangrijk'] ?></textarea></div>
                            <div class="question"><p>- Vind u het gemakkelijk om dingen te doen of te laten op advies van de arts of verpleegkundige?</p><textarea  rows="1" cols="25" type="text" name="oht_reactie_op_advies"><?= $antwoorden['oht_reactie_op_advies'] ?></textarea></div>
                            <div class="question"><p>Wat moet u in de toekomst doen ter voorkoming van het weer ziek worden?</p><textarea  rows="1" cols="25" type="text" name="preventie"><?= $antwoorden['preventie'] ?></textarea></div>


                            <div class="observation">
                                <h2>Verpleegkundige observatie bij dit patroon</h2>
                                <div class="question"><div class="observe"><input type="checkbox" <?= $boolArrayObservatie[0] ? "checked" : "" ?> name="observatie1"><p>Gezondheidszoekend gedrag</p></div></div>
                                <div class="question"><div class="observe"><input type="checkbox" <?= $boolArrayObservatie[1] ? "checked" : "" ?> name="observatie2"><p>Tekort in gezondheidsonderhoud</p></div></div>
                                <div class="question"><div class="observe"><input type="checkbox" <?= $boolArrayObservatie[2] ? "checked" : "" ?> name="observatie3"><p>(Dreigende) inadequate opvolging van de behandeling</p></div></div>
                                <div class="question"><div class="observe"><input type="checkbox" <?= $boolArrayObservatie[3] ? "checked" : "" ?> name="observatie4"><p>(Dreigend) tekort in gezondheidsinstandhouding</p></div></div>
                                <div class="question"><div class="observe"><input type="checkbox" <?= $boolArrayObservatie[4] ? "checked" : "" ?> name="observatie5"><p>(Dreigende) therapieontrouw</p></div></div>
                                <div class="question"><div class="observe"><input type="checkbox" <?= $boolArrayObservatie[5] ? "checked" : "" ?> name="observatie6"><p>Vergiftigingsgevaar</p></div></div>
                                <div class="question"><div class="observe"><input type="checkbox" <?= $boolArrayObservatie[6] ? "checked" : "" ?> name="observatie7"><p>Infectiegevaar</p></div></div>
                                <div class="question"><div class="observe"><input type="checkbox" <?= $boolArrayObservatie[7] ? "checked" : "" ?> name="observatie8"><p>Gevaar voor letsel (trauma)</p></div></div>
                                <div class="question"><div class="observe"><input type="checkbox" <?= $boolArrayObservatie[8] ? "checked" : "" ?> name="observatie9"><p>Verstikkingsgevaar</p></div></div>
                                <div class="question"><div class="observe"><input type="checkbox" <?= $boolArrayObservatie[9] ? "checked" : "" ?> name="observatie10"><p>Beschermingstekort</p></div></div>
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