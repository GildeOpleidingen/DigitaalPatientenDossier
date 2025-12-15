<?php
session_start();
include '../../database/DatabaseConnection.php';
include_once '../../models/autoload.php';
$Main = new Main();

$antwoorden = $Main->getPatternAnswers($_SESSION['clientId'], 7);

$boolArrayGevoelOpDitMoment = str_split($antwoorden['gevoel_op_dit_moment']);
$boolArrayGevoelMomenteel = str_split($antwoorden['gevoel_momenteel']);
$boolArrayLichamelijkeEnergie = str_split($antwoorden['lichamelijke_energie']);
$boolArrayObservatie = isset($antwoorden['observatie']) && $antwoorden['observatie'] != null ? str_split($antwoorden['observatie']) : [];

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_REQUEST['navbutton'])) {
    //Lees ingevulde gegevens.
    $zelfbeschrijving = trim(strval($_POST['zelfbeschrijving']));
    $opkomen_voor_uzelf = $_POST['opkomen_voor_uzelf'] ?? 0;
    $wel_niet_opkomen_blijktuit = trim(strval($_POST['wel_niet_opkomen_blijktuit']));
    $verandering_stemming = $_POST['verandering_stemming'];
    $verandering_stemming_welke = trim(strval($_POST['verandering_stemming_welke']));
    //array van checkboxes van gevoel_op_dit_moment tab
    $gevoel_op_dit_momentString = "";
    for ($i = 1; $i <= 10; $i++) {
        $gevoel_op_dit_momentString .= isset($_POST["gevoel$i"]) && $_POST["gevoel$i"] == 'on' ? "1" : "0";
    }
    $gevoel_op_dit_moment_anders = trim(strval($_POST['gevoel_op_dit_moment_anders']));
    $verandering_concentratie = $_POST['verandering_concentratie'] ?? 0;
    $verandering_denkpatroon = $_POST['verandering_denkpatroon'] ?? 0;
    $ervaring_voorheen = $_POST['ervaring_voorheen'] ?? 0;
    $verandering_uiterlijk = $_POST['verandering_uiterlijk'] ?? 0;
    $sensaties = $_POST['sensaties'] ?? 0;
    $sensaties_welk_gevoel = trim(strval($_POST['sensaties_welk_gevoel']));
    //array van checkboxes van gevoel_momenteel tab
    $gevoel_momenteelString = "";
    for ($i = 1; $i <= 3; $i++) {
        $gevoel_momenteelString .= isset($_POST["gevoelMomenteel$i"]) && $_POST["gevoelMomenteel$i"] == 'on' ? "1" : "0";
    }
    //array van checkboxes van lichamelijkeEnergie tab
    $lichamelijkeEnergieString = "";
    for ($i = 1; $i <= 3; $i++) {
        $lichamelijkeEnergieString .= isset($_POST["lichamelijkeEnergie$i"]) && $_POST["lichamelijkeEnergie$i"] == 'on' ? "1" : "0";
    }
    $zelfverzorging = trim(strval($_POST['zelfverzorging']));
    // array van checkboxes van observatie tab
    $observatieString = "";
    for ($i = 1; $i <= 19; $i++) {
        $observatieString .= isset($_POST["observatie$i"]) && $_POST["observatie$i"] == 'on' ? "1" : "0";
    }

    //Haal vragenlijst ID op.
    $vragenlijstId = $Main->getVragenlijstId($_SESSION['clientId'], $_SESSION['loggedin_id']);
    // kijken of patroon7 bestaat door te kijken naar vragenlijst id
    $result = DatabaseConnection::getConn()->prepare("
                    SELECT p.id
                    FROM patroon07zelfbeleving p
                    WHERE p.vragenlijstid = ?");
    $result->bind_param("i", $vragenlijstId);
    $result->execute();
    $result = $result->get_result()->fetch_assoc();

    unset($_SESSION['patroonerror']);

    //opslaan in database.
    if ($result != null) {
        // //update
        $result1 = DatabaseConnection::getConn()->prepare("UPDATE patroon07zelfbeleving 
            SET 
            zelfbeschrijving = ?,
            opkomen_voor_uzelf = ?,
            wel_niet_opkomen_blijktuit = ?,
            verandering_stemming = ?,
            verandering_stemming_welke = ?,
            gevoel_op_dit_moment = ?,
            gevoel_op_dit_moment_anders = ?,
            verandering_concentratie = ?,
            verandering_denkpatroon = ?,
            ervaring_voorheen = ?,
            verandering_uiterlijk = ?,
            sensaties = ?,
            sensaties_welk_gevoel = ?,
            gevoel_momenteel = ?,
            lichamelijke_energie = ?,
            zelfverzorging = ?,
            observatie = ?
            WHERE vragenlijstid = ?");
        if ($result1) {
            $result1->bind_param(
                "sisisssiiiiisssssi",
                $zelfbeschrijving,
                $opkomen_voor_uzelf,
                $wel_niet_opkomen_blijktuit,
                $verandering_stemming,
                $verandering_stemming_welke,
                $gevoel_op_dit_momentString,
                $gevoel_op_dit_moment_anders,
                $verandering_concentratie,
                $verandering_denkpatroon,
                $ervaring_voorheen,
                $verandering_uiterlijk,
                $sensaties,
                $sensaties_welk_gevoel,
                $gevoel_momenteelString,
                $lichamelijkeEnergieString,
                $zelfverzorging,
                $observatieString,
                $vragenlijstId
            );
            $result1->execute();
        } else {
            // Handle error
            $_SESSION['patroonerror'] = 'Er ging iets fout (wijzigen), wijzigingen zijn NIET opgeslagen.';
            $_SESSION['patroonnr'] = '7. Zelfbelevingspatroon';
        }
    } else {
        try {
            $result2 = DatabaseConnection::getConn()->prepare("INSERT INTO patroon07zelfbeleving (
                zelfbeschrijving,
                opkomen_voor_uzelf,
                wel_niet_opkomen_blijktuit,
                verandering_stemming,
                verandering_stemming_welke,
                gevoel_op_dit_moment,
                gevoel_op_dit_moment_anders,
                verandering_concentratie,
                verandering_denkpatroon,
                ervaring_voorheen,
                verandering_uiterlijk,
                sensaties,
                sensaties_welk_gevoel,
                gevoel_momenteel,
                lichamelijke_energie,
                zelfverzorging,
                observatie,
                vragenlijstid)
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
                ?)");
            $result2->bind_param(
                "sisisssiiiiisssssi",
                $zelfbeschrijving,
                $opkomen_voor_uzelf,
                $wel_niet_opkomen_blijktuit,
                $verandering_stemming,
                $verandering_stemming_welke,
                $gevoel_op_dit_momentString,
                $gevoel_op_dit_moment_anders,
                $verandering_concentratie,
                $verandering_denkpatroon,
                $ervaring_voorheen,
                $verandering_uiterlijk,
                $sensaties,
                $sensaties_welk_gevoel,
                $gevoel_momenteelString,
                $lichamelijkeEnergieString,
                $zelfverzorging,
                $observatieString,
                $vragenlijstId
            );

            $result2->execute();
            $result2 = $result2->get_result();
        } catch (Exception $e) {
            // Display the alert box on next of previous page
           $_SESSION['patroonerror'] = 'Er ging iets fout (toevoegen), wijzigingen zijn NIET opgeslagen.';
            $_SESSION['patroonnr'] = '7. Zelfbelevingspatroon';
        }
    }

    switch ($_REQUEST['navbutton']) {
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
                        <div class="h4 text-primary">7. Zelfbelevingspatroon</div>
                        <div class="form">
                            <div class="questionnaire">
                                <div class="question">
                                    <p>Kunt u uzelf, in het kort, beschrijven?</p><textarea rows="1" cols="25" type="text" name="zelfbeschrijving"><?= isset($antwoorden['zelfbeschrijving']) ? $antwoorden['zelfbeschrijving'] : '' ?></textarea>
                                </div>
                                <div class="question">
                                    <p>Kunt u voor uzelf opkomen?</p>
                                    <div class="checkboxes">
                                        <p>
                                            <input type="radio" value="1" name="opkomen_voor_uzelf" <?= (isset($antwoorden['opkomen_voor_uzelf']) && $antwoorden['opkomen_voor_uzelf'] == '1') ? "checked" : "" ?>>
                                            <label>Ja</label>
                                        </p>
                                        <p>
                                            <input type="radio" value="0" name="opkomen_voor_uzelf" <?= (!isset($antwoorden['opkomen_voor_uzelf']) || $antwoorden['opkomen_voor_uzelf'] == '0') ? "checked" : "" ?>>
                                            <label>Nee</label>
                                        </p>
                                    </div>
                                </div>
                                <div class="question">
                                    <p>Waar blijkt dat uit?</p><textarea rows="1" cols="25" type="text" name="wel_niet_opkomen_blijktuit"><?= isset($antwoorden['wel_niet_opkomen_blijktuit']) ? $antwoorden['wel_niet_opkomen_blijktuit'] : '' ?></textarea>
                                </div>
                                <div class="question">
                                    <p>Is uw stemming de laatste tijd veranderd?</p>
                                    <div class="checkboxes">
                                        <div class="question-answer">
                                            <input id="radio" type="radio" value="1" name="verandering_stemming" <?= (isset($antwoorden['verandering_stemming']) && $antwoorden['verandering_stemming'] == '1') ? "checked" : "" ?>>
                                            <label>Ja</label>
                                            <textarea rows="1" cols="25" id="checkfield" type="text" placeholder="en wel?" name="verandering_stemming_welke"> <?= isset($antwoorden['verandering_stemming_welke']) ? $antwoorden['verandering_stemming_welke'] : '' ?> </textarea>
                                        </div>
                                        <p>
                                            <input type="radio" value="0" name="verandering_stemming" <?= (!isset($antwoorden['verandering_stemming']) || $antwoorden['verandering_stemming'] == '0') ? "checked" : "" ?>>
                                            <label>Nee</label>
                                        </p>
                                    </div>
                                </div>

                                <div class="question">
                                    <p>- Hoe voelt u zich op dit moment?</p> 
                                    <div class="observation">
                                        <div class="question"><div class="observe"><input type="checkbox" <?= (isset($boolArrayGevoelOpDitMoment[0]) && $boolArrayGevoelOpDitMoment[0] == '1') ? "checked" : "" ?> name="gevoel1"><p>Neerslachtig</p></div></div>
                                        <div class="question"><div class="observe"><input type="checkbox" <?= (isset($boolArrayGevoelOpDitMoment[1]) && $boolArrayGevoelOpDitMoment[1] == '1') ? "checked" : "" ?> name="gevoel2"><p>Wanhopig</p></div></div>
                                        <div class="question"><div class="observe"><input type="checkbox" <?= (isset($boolArrayGevoelOpDitMoment[2]) && $boolArrayGevoelOpDitMoment[2] == '1') ? "checked" : "" ?> name="gevoel3"><p>Machteloos</p></div></div>
                                        <div class="question"><div class="observe"><input type="checkbox" <?= (isset($boolArrayGevoelOpDitMoment[3]) && $boolArrayGevoelOpDitMoment[3] == '1') ? "checked" : "" ?> name="gevoel4"><p>Opgewekt</p></div></div>
                                        <div class="question"><div class="observe"><input type="checkbox" <?= (isset($boolArrayGevoelOpDitMoment[4]) && $boolArrayGevoelOpDitMoment[4] == '1') ? "checked" : "" ?> name="gevoel5"><p>Somber</p></div></div>
                                        <div class="question"><div class="observe"><input type="checkbox" <?= (isset($boolArrayGevoelOpDitMoment[5]) && $boolArrayGevoelOpDitMoment[5] == '1') ? "checked" : "" ?> name="gevoel6"><p>Eufoor</p></div></div>
                                        <div class="question"><div class="observe"><input type="checkbox" <?= (isset($boolArrayGevoelOpDitMoment[6]) && $boolArrayGevoelOpDitMoment[6] == '1') ? "checked" : "" ?> name="gevoel7"><p>Labiel</p></div></div>
                                        <div class="question"><div class="observe"><input type="checkbox" <?= (isset($boolArrayGevoelOpDitMoment[7]) && $boolArrayGevoelOpDitMoment[7] == '1') ? "checked" : "" ?> name="gevoel8"><p>Gespannen</p></div></div>
                                        <div class="question"><div class="observe"><input type="checkbox" <?= (isset($boolArrayGevoelOpDitMoment[8]) && $boolArrayGevoelOpDitMoment[8] == '1') ? "checked" : "" ?> name="gevoel9"><p>Verdrietig</p></div></div>
                                        <div class="question"><div class="observe"><input type="checkbox" <?= (isset($boolArrayGevoelOpDitMoment[9]) && $boolArrayGevoelOpDitMoment[9] == '1') ? "checked" : "" ?> name="gevoel10"><p>Anders, namelijk:</p></div><textarea  rows="1" cols="25" type="text" name="gevoel_op_dit_moment_anders"><?= isset($antwoorden['anders']) ?></textarea></div>
                                    </div>
                                </div>

                                <div class="question">
                                    <p>- Is er de afgelopen tijd iets veranderd in uw concentratievermogen ten gevolgen van u stemming?</p>
                                    <div class="checkboxes">
                                        <p>
                                            <input type="radio" value="1" name="verandering_concentratie" <?= (isset($antwoorden['verandering_concentratie']) && $antwoorden['verandering_concentratie'] == '1') ? "checked" : "" ?>>
                                            <label>Ja</label>
                                        </p>
                                        <p>
                                            <input type="radio" value="0" name="verandering_concentratie" <?= (!isset($antwoorden['verandering_concentratie']) || $antwoorden['verandering_concentratie'] == '0') ? "checked" : "" ?>>
                                            <label>Nee</label>
                                        </p>
                                    </div>
                                </div>
                                <div class="question">
                                    <p>- Is er de afgelopen tijd iets veranderd in uw denkpatroon ten gevolgen van u stemming?</p>
                                    <div class="checkboxes">
                                        <p>
                                            <input type="radio" value="1" name="verandering_denkpatroon" <?= (isset($antwoorden['verandering_denkpatroon']) && $antwoorden['verandering_denkpatroon'] == '1') ? "checked" : "" ?>>
                                            <label>Ja</label>
                                        </p>
                                        <p>
                                            <input type="radio" value="0" name="verandering_denkpatroon" <?= (!isset($antwoorden['verandering_denkpatroon']) || $antwoorden['verandering_denkpatroon'] == '0') ? "checked" : "" ?>>
                                            <label>Nee</label>
                                        </p>
                                    </div>
                                </div>
                                <div class="question">
                                    <p>Ervaart u uzelf nu anders dan voorheen?</p>
                                    <div class="checkboxes">
                                        <p>
                                            <input type="radio" value="1" name="ervaring_voorheen" <?= (isset($antwoorden['ervaring_voorheen']) && $antwoorden['ervaring_voorheen'] =='1') ? "checked" : "" ?>>
                                            <label>Ja</label>
                                        </p>
                                        <p>
                                            <input type="radio" value="0" name="ervaring_voorheen" <?= (!isset($antwoorden['ervaring_voorheen']) || $antwoorden['ervaring_voorheen'] == '0') ? "checked" : "" ?>>
                                            <label>Nee</label>
                                        </p>
                                    </div>
                                </div>
                                <div class="question">
                                    <p>- Zijn er veranderingen in uw uiterlijk en/of mogelijkheden waardoor u zich anders voelt?</p>
                                    <div class="checkboxes">
                                        <p>
                                            <input type="radio" value="1" name="verandering_uiterlijk" <?= (isset($antwoorden['verandering_uiterlijk']) && $antwoorden['verandering_uiterlijk'] == '1') ? "checked" : "" ?>>
                                            <label>Ja</label>
                                        </p>
                                        <p>
                                            <input type="radio" value="0" name="verandering_uiterlijk" <?= (!isset($antwoorden['verandering_uiterlijk']) | $antwoorden['verandering_uiterlijk'] == '0') ? "checked" : "" ?>>
                                            <label>Nee</label>
                                        </p>
                                    </div>
                                </div>
                                <div class="question">
                                    <p>- Voelt u (lichamelijke) sensaties?</p>
                                    <div class="checkboxes">
                                        <div class="question-answer">
                                            <input id="radio" type="radio" value="1" name="sensaties" <?= (isset($antwoorden['sensaties']) && $antwoorden['sensaties'] == '1') ? "checked" : "" ?>>
                                            <label>Ja</label>
                                            <textarea rows="1" cols="25" id="checkfield" type="text" placeholder="wat voelt u?" name="sensaties_welk_gevoel"> <?= isset($antwoorden['sensaties_welk_gevoel']) ? $antwoorden['sensaties_welk_gevoel'] : '' ?> </textarea>
                                        </div>
                                        <p>
                                            <input type="radio" value="0" name="sensaties" <?= (!isset($antwoorden['sensaties']) | $antwoorden['sensaties'] == '0') ? "checked" : "" ?>>
                                            <label>Nee</label>
                                        </p>
                                    </div>
                                </div>
                                <div class="question">
                                    <p>- Hoe voelt u zich momenteel?</p>
                                    <div class="observation">
                                        <div class="question">
                                            <div class="observe"><input type="checkbox" <?= (isset($boolArrayGevoelMomenteel[0]) && $boolArrayGevoelMomenteel[0] == '1') ? "checked" : "" ?> name="gevoelMomenteel1">
                                                <p>Sterk</p>
                                            </div>
                                        </div>
                                        <div class="question">
                                            <div class="observe"><input type="checkbox" <?= (isset($boolArrayGevoelMomenteel[1]) && $boolArrayGevoelMomenteel[1] == '1') ? "checked" : "" ?> name="gevoelMomenteel2">
                                                <p>Zwak</p>
                                            </div>
                                        </div>
                                        <div class="question">
                                            <div class="observe"><input type="checkbox" <?= (isset($boolArrayGevoelMomenteel[2]) && $boolArrayGevoelMomenteel[2] == '1') ? "checked" : "" ?> name="gevoelMomenteel3">
                                                <p>Krachteloos</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="question">
                                    <p>- Hoe staat het met uw lichamelijke energie?</p>
                                    <div class="observation">
                                        <div class="question">
                                            <div class="observe"><input type="checkbox" <?= (isset($boolArrayLichamelijkeEnergie[0]) && $boolArrayLichamelijkeEnergie[0] == '1') ? "checked" : "" ?> name="lichamelijkeEnergie1">
                                                <p>Genoeg</p>
                                            </div>
                                        </div>
                                        <div class="question">
                                            <div class="observe"><input type="checkbox" <?= (isset($boolArrayLichamelijkeEnergie[1]) && $boolArrayLichamelijkeEnergie[1] == '1') ? "checked" : "" ?> name="lichamelijkeEnergie2">
                                                <p>Te veel</p>
                                            </div>
                                        </div>
                                        <div class="question">
                                            <div class="observe"><input type="checkbox" <?= (isset($boolArrayLichamelijkeEnergie[2]) && $boolArrayLichamelijkeEnergie[2] == '1') ? "checked" : "" ?> name="lichamelijkeEnergie3">
                                                <p>Te weinig</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="question">
                                    <p>Zorgt u goed voor u zelf, of vindt u dat het beter kan?</p><textarea rows="1" cols="25" type="text" name="zelfverzorging"><?= isset($antwoorden['zelfverzorging']) ? $antwoorden['zelfverzorging'] : '' ?></textarea>
                                </div>

                                <div class="observation">
                                    <h2>Verpleegkundige observatie bij dit patroon</h2>
                                    <div class="question">
                                        <div class="observe"><input type="checkbox" <?= (isset($boolArrayObservatie[0]) && $boolArrayObservatie[0] == '1') ? "checked" : "" ?> name="observatie1">
                                            <p>Lichte angst</p>
                                        </div>
                                    </div>
                                    <div class="question">
                                        <div class="observe"><input type="checkbox" <?= (isset($boolArrayObservatie[1]) && $boolArrayObservatie[1] == '1') ? "checked" : "" ?> name="observatie2">
                                            <p>Matige angst</p>
                                        </div>
                                    </div>
                                    <div class="question">
                                        <div class="observe"><input type="checkbox" <?= (isset($boolArrayObservatie[2]) && $boolArrayObservatie[2] == '1') ? "checked" : "" ?> name="observatie3">
                                            <p>Hevige (paniek) angst</p>
                                        </div>
                                    </div>
                                    <div class="question">
                                        <div class="observe"><input type="checkbox" <?= (isset($boolArrayObservatie[3]) && $boolArrayObservatie[3] == '1') ? "checked" : "" ?> name="observatie4">
                                            <p>Lichte anticiperende angst</p>
                                        </div>
                                    </div>
                                    <div class="question">
                                        <div class="observe"><input type="checkbox" <?= (isset($boolArrayObservatie[4]) && $boolArrayObservatie[4] == '1') ? "checked" : "" ?> name="observatie5">
                                            <p>Matige anticiperende angst</p>
                                        </div>
                                    </div>
                                    <div class="question">
                                        <div class="observe"><input type="checkbox" <?= (isset($boolArrayObservatie[5]) && $boolArrayObservatie[5] == '1') ? "checked" : "" ?> name="observatie6">
                                            <p>Hevige anticiperende angst</p>
                                        </div>
                                    </div>
                                    <div class="question">
                                        <div class="observe"><input type="checkbox" <?= (isset($boolArrayObservatie[6]) && $boolArrayObservatie[6] == '1') ? "checked" : "" ?> name="observatie7">
                                            <p>Vrees</p>
                                        </div>
                                    </div>
                                    <div class="question">
                                        <div class="observe"><input type="checkbox" <?= (isset($boolArrayObservatie[7]) && $boolArrayObservatie[7] == '1') ? "checked" : "" ?> name="observatie8">
                                            <p>Reactieve depressie</p>
                                        </div>
                                    </div>
                                    <div class="question">
                                        <div class="observe"><input type="checkbox" <?= (isset($boolArrayObservatie[8]) && $boolArrayObservatie[8] == '1') ? "checked" : "" ?> name="observatie9">
                                            <p>Moedeloosheid</p>
                                        </div>
                                    </div>
                                    <div class="question">
                                        <div class="observe"><input type="checkbox" <?= (isset($boolArrayObservatie[9]) && $boolArrayObservatie[9] == '1') ? "checked" : "" ?> name="observatie10">
                                            <p>Identiteitsstoornis</p>
                                        </div>
                                    </div>
                                    <div class="question">
                                        <div class="observe"><input type="checkbox" <?= (isset($boolArrayObservatie[10]) && $boolArrayObservatie[10] == '1') ? "checked" : "" ?> name="observatie11">
                                            <p>Lichte machteloosheid</p>
                                        </div>
                                    </div>
                                    <div class="question">
                                        <div class="observe"><input type="checkbox" <?= (isset($boolArrayObservatie[11]) && $boolArrayObservatie[11] == '1') ? "checked" : "" ?> name="observatie12">
                                            <p>Matige machteloosheid</p>
                                        </div>
                                    </div>
                                    <div class="question">
                                        <div class="observe"><input type="checkbox" <?= (isset($boolArrayObservatie[12]) && $boolArrayObservatie[12] == '1') ? "checked" : "" ?> name="observatie13">
                                            <p>Ernstige machteloosheid</p>
                                        </div>
                                    </div>
                                    <div class="question">
                                        <div class="observe"><input type="checkbox" <?= (isset($boolArrayObservatie[13]) && $boolArrayObservatie[13] == '1') ? "checked" : "" ?> name="observatie14">
                                            <p>Geringe zelfachting</p>
                                        </div>
                                    </div>
                                    <div class="question">
                                        <div class="observe"><input type="checkbox" <?= (isset($boolArrayObservatie[14]) && $boolArrayObservatie[14] == '1') ? "checked" : "" ?> name="observatie15">
                                            <p>Chronisch geringe zelfachting</p>
                                        </div>
                                    </div>
                                    <div class="question">
                                        <div class="observe"><input type="checkbox" <?= (isset($boolArrayObservatie[15]) && $boolArrayObservatie[15] == '1') ? "checked" : "" ?> name="observatie16">
                                            <p>Reactief geringe zelfachting</p>
                                        </div>
                                    </div>
                                    <div class="question">
                                        <div class="observe"><input type="checkbox" <?= (isset($boolArrayObservatie[16]) && $boolArrayObservatie[16] == '1') ? "checked" : "" ?> name="observatie17">
                                            <p>Verstoord lichaamsbeeld</p>
                                        </div>
                                    </div>
                                    <div class="question">
                                        <div class="observe"><input type="checkbox" <?= (isset($boolArrayObservatie[17]) && $boolArrayObservatie[17] == '1') ? "checked" : "" ?> name="observatie18">
                                            <p>Hopeloosheid</p>
                                        </div>
                                    </div>
                                    <div class="question">
                                        <div class="observe"><input type="checkbox" <?= (isset($boolArrayObservatie[18]) && $boolArrayObservatie[18] == '1') ? "checked" : "" ?> name="observatie19">
                                            <p>Dreigende zelfverminking (automutilatie)</p>
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