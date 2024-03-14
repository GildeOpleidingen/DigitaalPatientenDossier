<?php
session_start();
include '../../Database/DatabaseConnection.php';
include '../../Functions/Functions.php';
include '../../Functions/AnamneseFunctions.php';

$antwoorden = getPatternAnswers($_SESSION['clientId'], 7);

$boolArrayGevoelOpDitMoment = str_split($antwoorden['gevoel_op_dit_moment']);
$boolArrayGevoelMomenteel = str_split($antwoorden['gevoel_momenteel']);
$boolArrayLichamelijkeEnergie = str_split($antwoorden['lichamelijke_energie']);
$boolArrayObservatie = str_split($antwoorden['observatie']);

$medewerkerId = $_SESSION['loggedin_id'];
$clientId = $_SESSION['clientId'];

if (isset($_REQUEST['navbutton'])) {
    $zelfbeschrijving = $_POST['zelfbeschrijving']?? "";
    $opkomen_voor_uzelf = $_POST['opkomen_voor_uzelf'];
    $wel_niet_opkomen_blijktuit = $_POST['wel_niet_opkomen_blijktuit'] ?? "";
    $verandering_stemming = $_POST['verandering_stemming'];
    $verandering_stemming_welke = $_POST['verandering_stemming_welke'] ?? "";
    $gevoel = array(!empty($_POST['gevoel1']), !empty($_POST['gevoel2']), !empty($_POST['gevoel3']), !empty($_POST['gevoel4']), !empty($_POST['gevoel5']), !empty($_POST['gevoel6']), !empty($_POST['gevoel7']), !empty($_POST['gevoel8']), !empty($_POST['gevoel9']), !empty($_POST['gevoel10']));
    $gevoelAnders = $_POST['gevoel_op_dit_moment_anders'] ?? "";
    $verandering_concentratie = $_POST['verandering_concentratie'];
    $verandering_denkpatroon = $_POST['verandering_denkpatroon'];
    $verandering_uiterlijk = $_POST['verandering_uiterlijk'];
    $sensaties = $_POST['sensaties'];
    $sensaties_welk_gevoel = $_POST['sensaties_welk_gevoel'] ?? "";
    $gevoelMomenteel = array(!empty($_POST['gevoelMomenteel1']), !empty($_POST['gevoelMomenteel2']), !empty($_POST['gevoelMomenteel3']));
    $lichamelijkeEnergie = array(!empty($_POST['lichamelijkeEnergie1']), !empty($_POST['lichamelijkeEnergie2']), !empty($_POST['lichamelijkeEnergie3']));
    $zelfverzorging = $_POST['zelfverzorging'];

    // array van checkboxes van observatie tab
    $observatie = array(!empty($_POST['observatie1']), !empty($_POST['observatie2']), !empty($_POST['observatie3']), !empty($_POST['observatie4']), !empty($_POST['observatie5']), !empty($_POST['observatie6']), !empty($_POST['observatie7']), !empty($_POST['observatie8']), !empty($_POST['observatie9']), !empty($_POST['observatie10']), !empty($_POST['observatie11']), !empty($_POST['observatie12']), !empty($_POST['observatie13']), !empty($_POST['observatie14']), !empty($_POST['observatie15']), !empty($_POST['observatie16']), !empty($_POST['observatie17']), !empty($_POST['observatie18']), !empty($_POST['observatie19']));

    $gevoel = convertBoolArrayToString($gevoel);
    $gevoelMomenteel = convertBoolArrayToString($gevoelMomenteel);
    $lichamelijkeEnergie = convertBoolArrayToString($lichamelijkeEnergie);
    $observatie = convertBoolArrayToString($observatie);

    $result = getVragenlijstId($clientId);

    if ($result != null){
        $vragenlijstId = $result;
    } else {
        $result = insertVragenlijst($_SESSION['clientId'], $medewerkerId);
        $vragenlijstId = $result;
    }
    $result = checkIfPatternExists("patroon07zelfbeleving", $vragenlijstId);

    if ($result != null) {
        //update
        $result1 = DatabaseConnection::getConn()->prepare("UPDATE `patroon07zelfbeleving` SET
        `zelfbeschrijving`=?,
        `opkomen_voor_uzelf`=?,
        `wel_niet_opkomen_blijktuit`=?,
        `verandering_stemming`=?,
        `verandering_stemming_welke`=?,
        `gevoel_op_dit_moment`=?,
        `gevoel_op_dit_moment_anders`=?,
        `verandering_concentratie`=?,
        `verandering_denkpatroon`=?,
        `verandering_uiterlijk`=?,
        `sensaties`=?,
        `sensaties_welk_gevoel`=?,
        `gevoel_momenteel`=?,
        `lichamelijke_energie`=?,
        `zelfverzorging`=?,
        `observatie`=?
           WHERE `vragenlijstid`=?");
        $result1->bind_param("sisisssiiiisssssi", $zelfbeschrijving, $opkomen_voor_uzelf, $wel_niet_opkomen_blijktuit, $verandering_stemming, $verandering_stemming_welke, $gevoel, $gevoelAnders, $verandering_concentratie, $verandering_denkpatroon, $verandering_uiterlijk, $sensaties, $sensaties_welk_gevoel, $gevoelMomenteel, $lichamelijkeEnergie, $zelfverzorging, $observatie, $vragenlijstId);
        $result1->execute();
        $result1 = $result1->get_result();

    }else{
        //hier insert je alle data in patroon02
        $result2 = DatabaseConnection::getConn()->prepare( "INSERT INTO `patroon07zelfbeleving`(
            `vragenlijstid`,
            `zelfbeschrijving`,
            `opkomen_voor_uzelf`,
            `wel_niet_opkomen_blijktuit`,
            `verandering_stemming`,
            `verandering_stemming_welke`,
            `gevoel_op_dit_moment`,
            `gevoel_op_dit_moment_anders`,
            `verandering_concentratie`,
            `verandering_denkpatroon`,
            `verandering_uiterlijk`,
            `sensaties`,
            `sensaties_welk_gevoel`,
            `gevoel_momenteel`,
            `lichamelijke_energie`,
            `zelfverzorging`,
            `observatie`)
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
        $result2->bind_param("isisisssiiiisssss", $vragenlijstId, $zelfbeschrijving, $opkomen_voor_uzelf, $wel_niet_opkomen_blijktuit, $verandering_stemming, $verandering_stemming_welke, $gevoel, $gevoelAnders, $verandering_concentratie, $verandering_denkpatroon, $verandering_uiterlijk, $sensaties, $sensaties_welk_gevoel, $gevoelMomenteel, $lichamelijkeEnergie, $zelfverzorging, $observatie);
        $result2->execute();
        $result2 = $result2->get_result();

        print_r($result2);
    }

    switch($_REQUEST['navbutton']) {
        case 'next': //action for next here
            header('Location: patroon08.php');
            break;

        case 'prev': //action for previous here
            header('Location: patroon06.php');
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
        include '../../Includes/sidebar.php';
        ?>
        <div class="content">
            <div class="form-content">
            <div class="pages">7 Zelfbelevingspatroon</div>
                <div class="form">
                    <div class="questionnaire">
                        <div class="question"><p>Kunt u uzelf, in het kort, beschijven?</p><textarea name="zelfbeschrijving" rows="1" cols="25" type="text"><?= $antwoorden['zelfbeschrijving'] ?></textarea></div>
                        <div class="question"><p>Kunt u voor uzelf opkomen?</p>
                            <div class="checkboxes">
                                <p>    
                                    <input type="radio" name="opkomen_voor_uzelf" value="1" <?= $antwoorden['opkomen_voor_uzelf'] ? "checked" : "" ?>>
                                    <label>Ja</label>
                                </p>
                                <p>
                                    <input type="radio" name="opkomen_voor_uzelf" value="0" <?= !$antwoorden['opkomen_voor_uzelf'] ? "checked" : "" ?>>
                                    <label>Nee</label>
                                </p>
                            </div>
                        </div>
                        <div class="question"><p>Waar blijkt dat uit?</p><textarea  rows="1" cols="25" type="text" name="wel_niet_opkomen_blijktuit"><?= $antwoorden['wel_niet_opkomen_blijktuit'] ?></textarea></div>
                        <div class="question"><p>Is uw stemming de laatste tijd veranderd?</p>
                            <div class="checkboxes">
                                <div class="question-answer">
                                    <input id="radio" type="radio" name="verandering_stemming" value="1" <?= $antwoorden['verandering_stemming'] ? "checked" : "" ?>>
                                    <label>Ja</label>
                                    <textarea  rows="1" cols="25" id="checkfield" type="text" placeholder="en wel?" name="verandering_stemming_welke" <?= $antwoorden['verandering_stemming_welke'] ?>></textarea>
                                </div>
                                <p>
                                    <input type="radio" name="verandering_stemming" value="0" <?= !$antwoorden['verandering_stemming'] ? "checked" : "" ?>>
                                    <label>Nee</label>
                                </p>
                            </div>
                        </div>
                        <div class="question"><p>- Hoe voelt u zich op dit moment?</p> 
                            <div class="observation">
                                <div class="question"><div class="observe"><input type="checkbox" <?= @$boolArrayGevoelOpDitMoment[0] ? "checked" : "" ?> name="gevoel1"><p>Neerslachtig</p></div></div>
                                <div class="question"><div class="observe"><input type="checkbox" <?= @$boolArrayGevoelOpDitMoment[1] ? "checked" : "" ?> name="gevoel2"><p>Wanhopig</p></div></div>
                                <div class="question"><div class="observe"><input type="checkbox" <?= @$boolArrayGevoelOpDitMoment[2] ? "checked" : "" ?> name="gevoel3"><p>Machteloos</p></div></div>
                                <div class="question"><div class="observe"><input type="checkbox" <?= @$boolArrayGevoelOpDitMoment[3] ? "checked" : "" ?> name="gevoel4"><p>Opgewekt</p></div></div>
                                <div class="question"><div class="observe"><input type="checkbox" <?= @$boolArrayGevoelOpDitMoment[4] ? "checked" : "" ?> name="gevoel5"><p>Somber</p></div></div>
                                <div class="question"><div class="observe"><input type="checkbox" <?= @$boolArrayGevoelOpDitMoment[5] ? "checked" : "" ?> name="gevoel6"><p>Eufoor</p></div></div>
                                <div class="question"><div class="observe"><input type="checkbox" <?= @$boolArrayGevoelOpDitMoment[6] ? "checked" : "" ?> name="gevoel7"><p>Labiel</p></div></div>
                                <div class="question"><div class="observe"><input type="checkbox" <?= @$boolArrayGevoelOpDitMoment[7] ? "checked" : "" ?> name="gevoel8"><p>Gespannen</p></div></div>
                                <div class="question"><div class="observe"><input type="checkbox" <?= @$boolArrayGevoelOpDitMoment[8] ? "checked" : "" ?> name="gevoel9"><p>Verdrietig</p></div></div>
                                <div class="question"><div class="observe"><input type="checkbox" <?= @$boolArrayGevoelOpDitMoment[9] ? "checked" : "" ?> name="gevoel10"><p>Anders, namelijk:</p></div><textarea rows="1" cols="25" type="text" name="gevoel_op_dit_moment_anders"><?= $antwoorden['gevoel_op_dit_moment_anders']?></textarea></div>
                            </div>
                        </div>
                        <div class="question"><p>- Is er de afgelopen tijd iets veranderd in uw concentratievermogen ten gevolgen van u stemming?</p>
                            <div class="checkboxes">
                                <p>    
                                    <input type="radio" name="verandering_concentratie" value="1" <?= $antwoorden['verandering_concentratie'] ? "checked" : "" ?>>
                                    <label>Ja</label>
                                </p>
                                <p>
                                    <input type="radio" name="verandering_concentratie" value="0" <?= !$antwoorden['verandering_concentratie'] ? "checked" : "" ?>>
                                    <label>Nee</label>
                                </p>
                            </div>
                        </div>
                        <div class="question"><p>- Is er de afgelopen tijd iets veranderd in uw denkpatroon ten gevolgen van u stemming?</p>
                            <div class="checkboxes">
                                <p>    
                                    <input type="radio" name="verandering_denkpatroon" value="1" <?= $antwoorden['verandering_denkpatroon'] ? "checked" : "" ?>>
                                    <label>Ja</label>
                                </p>
                                <p>
                                    <input type="radio" name="verandering_denkpatroon" value="0" <?= !$antwoorden['verandering_denkpatroon'] ? "checked" : "" ?>>
                                    <label>Nee</label>
                                </p>
                            </div>
                        </div>
                        <div class="question"><p>- Zijn er veranderingen in uw uiterlijk en/of mogelijkheden waardoor u zich anders voelt?</p>
                            <div class="checkboxes">
                                <p>    
                                    <input type="radio" name="verandering_uiterlijk" value="1" <?= $antwoorden['verandering_uiterlijk'] ? "checked" : "" ?>>
                                    <label>Ja</label>
                                </p>
                                <p>
                                    <input type="radio" name="verandering_uiterlijk" value="0" <?= !$antwoorden['verandering_uiterlijk'] ? "checked" : "" ?>>
                                    <label>Nee</label>
                                </p>
                            </div>
                        </div>
                        <div class="question"><p>- Voelt u (lichamelijke) sensaties?</p>
                            <div class="checkboxes">
                                <div class="question-answer">
                                    <input id="radio" type="radio" name="sensaties" value="1" <?= $antwoorden['sensaties'] ? "checked" : "" ?>>
                                    <label>Ja</label>
                                    <textarea  rows="1" cols="25" id="checkfield" type="text" placeholder="wat voelt u?" name="sensaties_welk_gevoel"> <?= $antwoorden['sensaties_welk_gevoel'] ?> </textarea>
                                </div>
                                <p>
                                    <input type="radio" name="sensaties" value="0" <?= !$antwoorden['sensaties'] ? "checked" : "" ?>>
                                    <label>Nee</label>
                                </p>
                            </div>
                        </div>
                        <div class="question"><p>- Hoe voelt u zich momenteel?</p> 
                            <div class="observation">
                                <div class="question"><div class="observe"><input type="checkbox" <?= @$boolArrayGevoelMomenteel[0] ? "checked" : "" ?> name="gevoelMomenteel1"><p>Sterk</p></div></div>
                                <div class="question"><div class="observe"><input type="checkbox" <?= @$boolArrayGevoelMomenteel[1] ? "checked" : "" ?> name="gevoelMomenteel2"><p>Zwak</p></div></div>
                                <div class="question"><div class="observe"><input type="checkbox" <?= @$boolArrayGevoelMomenteel[2] ? "checked" : "" ?> name="gevoelMomenteel3"><p>Krachteloos</p></div></div>
                            </div>
                        </div>
                        <div class="question"><p>- Hoe staat het met uw lichamelijke energie?</p> 
                            <div class="observation">
                                <div class="question"><div class="observe"><input type="checkbox" <?= @$boolArrayLichamelijkeEnergie[0] ? "checked" : "" ?> name="lichamelijkeEnergie1"><p>Genoeg</p></div></div>
                                <div class="question"><div class="observe"><input type="checkbox" <?= @$boolArrayLichamelijkeEnergie[1] ? "checked" : "" ?> name="lichamelijkeEnergie2"><p>Te veel</p></div></div>
                                <div class="question"><div class="observe"><input type="checkbox" <?= @$boolArrayLichamelijkeEnergie[2] ? "checked" : "" ?> name="lichamelijkeEnergie3"><p>Te weinig</p></div></div>
                            </div>
                        </div>
                        <div class="question"><p>Zorgt u goed voor u zelf, of vindt u dat het beter kan?</p><textarea  rows="1" cols="25" type="text" name="zelfverzorging"><?= $antwoorden['zelfverzorging'] ?></textarea></div>

                        <div class="observation">
                            <h2>Verpleegkundige observatie bij dit patroon</h2>
                            <div class="question"><div class="observe"><input type="checkbox" <?= @$boolArrayObservatie[0] ? "checked" : ""?> name="observatie1"><p>Lichte angst</p></div></div>
                            <div class="question"><div class="observe"><input type="checkbox" <?= @$boolArrayObservatie[1] ? "checked" : ""?> name="observatie2"><p>Matige angst</p></div></div>
                            <div class="question"><div class="observe"><input type="checkbox" <?= @$boolArrayObservatie[2] ? "checked" : ""?> name="observatie3"><p>Hevige (paniek) angst</p></div></div>
                            <div class="question"><div class="observe"><input type="checkbox" <?= @$boolArrayObservatie[3] ? "checked" : ""?> name="observatie4"><p>Lichte anticiperende angst</p></div></div>
                            <div class="question"><div class="observe"><input type="checkbox" <?= @$boolArrayObservatie[4] ? "checked" : ""?> name="observatie5"><p>Matige anticiperende angst</p></div></div>
                            <div class="question"><div class="observe"><input type="checkbox" <?= @$boolArrayObservatie[5] ? "checked" : ""?> name="observatie6"><p>Hevige anticiperende angst</p></div></div>
                            <div class="question"><div class="observe"><input type="checkbox" <?= @$boolArrayObservatie[6] ? "checked" : ""?> name="observatie7"><p>Vrees</p></div></div>
                            <div class="question"><div class="observe"><input type="checkbox" <?= @$boolArrayObservatie[7] ? "checked" : ""?> name="observatie8"><p>Reactieve depressie</p></div></div>
                            <div class="question"><div class="observe"><input type="checkbox" <?= @$boolArrayObservatie[8] ? "checked" : ""?> name="observatie9"><p>Moedeloosheid</p></div></div>
                            <div class="question"><div class="observe"><input type="checkbox" <?= @$boolArrayObservatie[9] ? "checked" : ""?> name="observatie10"><p>Identiteitsstoornis</p></div></div>
                            <div class="question"><div class="observe"><input type="checkbox" <?= @$boolArrayObservatie[10] ? "checked" : "" ?> name="observatie11"><p>Lichte machteloosheid</p></div></div>
                            <div class="question"><div class="observe"><input type="checkbox" <?= @$boolArrayObservatie[11] ? "checked" : "" ?> name="observatie12"><p>Matige machteloosheid</p></div></div>
                            <div class="question"><div class="observe"><input type="checkbox" <?= @$boolArrayObservatie[12] ? "checked" : "" ?> name="observatie13"><p>Ernstige machteloosheid</p></div></div>
                            <div class="question"><div class="observe"><input type="checkbox" <?= @$boolArrayObservatie[13] ? "checked" : "" ?> name="observatie14"><p>Geringe zelfachting</p></div></div>
                            <div class="question"><div class="observe"><input type="checkbox" <?= @$boolArrayObservatie[14] ? "checked" : "" ?> name="observatie15"><p>Chronisch geringe zelfachting</p></div></div>
                            <div class="question"><div class="observe"><input type="checkbox" <?= @$boolArrayObservatie[15] ? "checked" : "" ?> name="observatie16"><p>Reactief geringe zelfachting</p></div></div>
                            <div class="question"><div class="observe"><input type="checkbox" <?= @$boolArrayObservatie[16] ? "checked" : "" ?> name="observatie17"><p>Verstoord lichaamsbeeld</p></div></div>
                            <div class="question"><div class="observe"><input type="checkbox" <?= @$boolArrayObservatie[17] ? "checked" : "" ?> name="observatie18"><p>Hopeloosheid</p></div></div>
                            <div class="question"><div class="observe"><input type="checkbox" <?= @$boolArrayObservatie[18] ? "checked" : "" ?> name="observatie19"><p>Dreigende zelfverminking (automutilatie)</p></div></div>
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