<?php
session_start();

include '../../includes/auth.php';
include '../../database/DatabaseConnection.php';
include '../../models/autoload.php';

$Main = new Main();

$antwoorden = $Main->getPatternAnswers($_SESSION['clientId'], 4);
$boolArrayObservatie = isset($antwoorden['observatie']) ? str_split($antwoorden['observatie']) : [];

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_REQUEST['navbutton'])) {
    // Haal alle antwoorden uit het formulier
   $voedingCheckbox = $_POST['voedingCheckbox'] ?? 0;
   $voeding = $voedingCheckbox != 0 && $Main->CheckValue($_POST['voeding'], 0, 4) ? strval($_POST['voeding']) : null;
   
    $aankledenCheckbox = $_POST['aankledenCheckbox'] ?? 0;
   
    $aankleden =  $aankledenCheckbox != 0 && $Main->CheckValue($_POST['aankleden'], 0, 4) ? $_POST['aankleden'] :   null;
  
    $alg_mobiliteitCheckbox = $_POST['alg_mobiliteitCheckbox'] ?? 0;
    $alg_mobiliteit = $alg_mobiliteitCheckbox != 0 && $Main->CheckValue($_POST['alg_mobiliteit'], 0, 4) ? strval($_POST['alg_mobiliteit']) : null;
    $kokenCheckbox = $_POST['kokenCheckbox'] ?? 0;
    $koken = $kokenCheckbox != 0 && $Main->CheckValue($_POST['koken'], 0, 4)? strval($_POST['koken']) : null;
    $huishoudenCheckbox = $_POST['huishoudenCheckbox'] ?? 0;
    $huishouden = $huishoudenCheckbox != 0 && $Main->CheckValue($_POST['huishouden'], 0, 4)? strval($_POST['huishouden']) : null;
    $financienCheckbox = $_POST['financienCheckbox'] ?? 0;
    $financien = $financienCheckbox != 0 && $Main->CheckValue($_POST['financien'], 0, 4) ? strval($_POST['financien']) : null;
    $verzorgingCheckbox = $_POST['verzorgingCheckbox'] ?? 0; 
    $verzorging = $verzorgingCheckbox != 0 && $Main->CheckValue($_POST['verzorging'], 0, 4)? strval($_POST['verzorging']) : null;
    $badenCheckbox = $_POST['badenCheckbox'] ?? 0;
    $baden = $badenCheckbox != 0 && $Main->CheckValue($_POST['baden'], 0, 4)? strval($_POST['baden']) : null;
    $toiletgangCheckbox = $_POST['toiletgangCheckbox'] ?? 0;
    $toiletgang = $toiletgangCheckbox != 0 && $Main->CheckValue($_POST['toiletgang'], 0, 4)? strval($_POST['toiletgang']) : null;
    $uit_bed_komenCheckbox = $_POST['uit_bed_komenCheckbox'] ?? 0;
    $uit_bed_komen = $uit_bed_komenCheckbox != 0 && $Main->CheckValue($_POST['uit_bed_komen'], 0, 4)? strval($_POST['uit_bed_komen']) : null;
    $winkelenCheckbox = $_POST['winkelenCheckbox'] ?? 0;
    $winkelen = $winkelenCheckbox != 0 && $Main->CheckValue($_POST['winkelen'], 0, 4)? strval($_POST['winkelen']) : null;
    $tijd_voor_uzelf_nodig = $_POST['tijd_voor_uzelf_nodig'] ?? 0;
    $tijd_voor_uzelf_nodig_blijktuit = strval($_POST['tijd_voor_uzelf_nodig_blijktuit']);
    $dagelijkse_activiteiten = strval($_POST['dagelijkse_activiteiten']);
    $dagelijkse_gewoontes = $_POST['dagelijkse_gewoontes'] ?? 0;
    $dagelijkse_gewoontes_welke = strval($_POST['dagelijkse_gewoontes_welke'] );
    $lichamelijke_beperking = $_POST['lichamelijke_beperking'] ?? 0;
    $lichamelijke_beperking_welke = strval($_POST['lichamelijke_beperking_welke'] );
    $vermoeidheids_klachten = $_POST['vermoeidheids_klachten'] ?? 0;
    $passiever = $_POST['passiever'] ?? 0;
    $passiever_blijktuit = strval($_POST['passiever_blijktuit']);
    $problemen_starten_dag = $_POST['problemen_starten_dag'] ?? 0;
    $problemen_starten_dag_blijktuit = strval($_POST['problemen_starten_dag_blijktuit']);
    $hobbys = $_POST['hobbys'] ?? 0;
    $hobbys_bestedingstijd = strval($_POST['hobbys_bestedingstijd']);
    $activiteiten_weggevallen = $_POST['activiteiten_weggevallen'] ?? 0;
    $activiteiten_weggevallen_welke = strval($_POST['activiteiten_weggevallen_welke']);

    // array van checkboxes van observatie tab
    $observatie = "";
    for ($i = 1; $i <= 10; $i++) {
        $observatie .= isset($_POST["observatie$i"]) && $_POST["observatie$i"] == 1 ? "1" : "0";
    }

     //Haal vragenlijst ID op.
    $vragenlijstId = $Main->getVragenlijstId($_SESSION['clientId'], $_SESSION['loggedin_id']);

    unset($_SESSION['patroonerror']);
    
    //opslaan in database.
    if ($antwoorden) {
        //update
        $result1 = DatabaseConnection::getConn()->prepare("UPDATE `patroon04activiteiten`
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
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="Stylesheet" href="../../assets/css/client/patronen.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
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
                        <div class="h4 text-primary">4. Activiteitenpatroon</div>
                        <div class="form">
                            <div class="questionnaire">
                                <!-- Zorg ervoor dat dit 0-4 is en als het niet is ingevuld null is en niet 0 -->
                                <div class="question">
                                    <p>In hoeverre bent u in staat de volgende activiteiten te doen?</p>
                                </div>
                                <div class="question">
                                    <p><i>0 - volledige zelfzorg<br>1 - gebruik van hulpmiddelen of plan<br>2 - vereist
                                            assistentie/supervisie van anderen<br>3 - Vereidst gebruik van hulpmiddelen
                                            of plan/methoden en assistentie van anderen<br>4 - is afhankelijk en/of
                                            participeert niet)</i></p>
                                </div>
                                <div class="question">
                                    <div class="observe"><input type="checkbox" name="voedingCheckbox" <?php if (isset($antwoorden['voeding']) && $antwoorden['voeding'] >= 0) {
                                        echo "checked";
                                    } ?>>
                                        <p>Voeding</p>
                                    </div><input type="number" min="0" max="4"
                                        value="<?= isset($antwoorden['voeding']) ? $antwoorden['voeding'] : '' ?>"
                                        name="voeding">
                                </div>
                                <div class="question">
                                    <div class="observe"><input type="checkbox" name="aankledenCheckbox" <?php if (isset($antwoorden['aankleden']) && $antwoorden['aamkleden'] >= 0) {
                                        echo "checked";
                                    } ?>>
                                        <p>Aankleden</p>
                                    </div><input type="number" min="0" max="4"
                                        value="<?= isset($antwoorden['aankleden']) ? $antwoorden['aankleden'] : '' ?>"
                                        name="aankleden">
                                </div>
                                <div class="question">
                                    <div class="observe"><input type="checkbox" name="alg_mobiliteitCheckbox" <?php if (isset($antwoorden['alg_mobiliteit']) && $antwoorden['alg_mobiliteit'] >= 0) {
                                        echo "checked";
                                    } ?>>
                                        <p>Algemene mobiliteit</p>
                                    </div><input type="number" min="0" max="4"
                                        value="<?= isset($antwoorden['alg_mobiliteit']) ? $antwoorden['alg_mobiliteit'] : '' ?>"
                                        name="alg_mobiliteit">
                                </div>
                                <div class="question">
                                    <div class="observe"><input type="checkbox" name="kokenCheckbox" <?php if (isset($antwoorden['koken']) && $antwoorden['koken'] >= 0) {
                                        echo "checked";
                                    } ?>>
                                        <p>Koken</p>
                                    </div><input type="number" min="0" max="4"
                                        value="<?= isset($antwoorden['koken']) ? $antwoorden['koken'] : '' ?>"
                                        name="koken">
                                </div>
                                <div class="question">
                                    <div class="observe"><input type="checkbox" name="huishoudenCheckbox" <?php if (isset($antwoorden['huishouden']) && $antwoorden['huidhouden'] >= 0) {
                                        echo "checked";
                                    } ?>>
                                        <p>Huishouden</p>
                                    </div><input type="number" min="0" max="4"
                                        value="<?= isset($antwoorden['huishouden']) ? $antwoorden['huishouden'] : '' ?>"
                                        name="huishouden">
                                </div>
                                <div class="question">
                                    <div class="observe"><input type="checkbox" name="financienCheckbox" <?php if (isset($antwoorden['financien']) && $antwoorden['financien'] >= 0) {
                                        echo "checked";
                                    } ?>>
                                        <p>Financien</p>
                                    </div><input type="number" min="0" max="4"
                                        value="<?= isset($antwoorden['financien']) ? $antwoorden['financien'] : '' ?>"
                                        name="financien">
                                </div>
                                <div class="question">
                                    <div class="observe"><input type="checkbox" name="verzorgingCheckbox" <?php if (isset($antwoorden['verzorging']) && $antwoorden['verzorging'] >= 0) {
                                        echo "checked";
                                    } ?>>
                                        <p>Verzorging</p>
                                    </div><input type="number" min="0" max="4"
                                        value="<?= isset($antwoorden['verzorging']) ? $antwoorden['verzorging'] : '' ?>"
                                        name="verzorging">
                                </div>
                                <div class="question">
                                    <div class="observe"><input type="checkbox" name="badenCheckbox" <?php if (isset($antwoorden['baden']) && $antwoorden['baden'] >= 0) {
                                        echo "checked";
                                    } ?>>
                                        <p>Baden</p>
                                    </div><input type="number" min="0" max="4"
                                        value="<?= isset($antwoorden['baden']) ? $antwoorden['baden'] : '' ?>"
                                        name="baden">
                                </div>
                                <div class="question">
                                    <div class="observe"><input type="checkbox" name="toiletgangCheckbox" <?php if (isset($antwoorden['toiletgang']) && $antwoorden['toiletgang'] >= 0) {
                                        echo "checked";
                                    } ?>>
                                        <p>Toiletgang</p>
                                    </div><input type="number" min="0" max="4"
                                        value="<?= isset($antwoorden['toiletgang']) ? $antwoorden['toiletgang'] : '' ?>"
                                        name="toiletgang">
                                </div>
                                <div class="question">
                                    <div class="observe"><input type="checkbox" name="uit_bed_komenCheckbox" <?php if (isset($antwoorden['uit_bed_komen']) && $antwoorden['uit_bed_komen'] >= 0) {
                                        echo "checked";
                                    } ?>>
                                        <p>Uit bed komen</p>
                                    </div><input type="number" min="0" max="4"
                                        value="<?= isset($antwoorden['uit_bed_komen']) ? $antwoorden['uit_bed_komen'] : '' ?>"
                                        name="uit_bed_komen">
                                </div>
                                <div class="question">
                                    <div class="observe"><input type="checkbox" name="winkelenCheckbox" <?php if (isset($antwoorden['winkelen']) && $antwoorden['winkelen'] >= 0) {
                                        echo "checked";
                                    } ?>>
                                        <p>Winkelen</p>
                                    </div><input type="number" min="0" max="4"
                                        value="<?= isset($antwoorden['winkelen']) ? $antwoorden['winkelen'] : '' ?>"
                                        name="winkelen">
                                </div>
                                <div class="question">
                                    <p>Neemt u meer tijd voor uzelf wanneer u dat nodig heeft?</p>
                                    <div class="checkboxes">
                                        <div class="question-answer">
                                            <input id="radio" type="radio" value="1" name="tijd_voor_uzelf_nodig"
                                                <?= isset($antwoorden['tijd_voor_uzelf_nodig']) && $antwoorden['tijd_voor_uzelf_nodig'] == 1 ? "checked" : "" ?>>
                                            <label>Ja</label>
                                            <textarea rows="1" cols="25" id="checkfield" type="text"
                                                placeholder="blijkt uit?"
                                                name="tijd_voor_uzelf_nodig_blijktuit"><?= isset($antwoorden['tijd_voor_uzelf_nodig_blijktuit']) ? $antwoorden['tijd_voor_uzelf_nodig_blijktuit'] : '' ?></textarea>
                                        </div>
                                        <p>
                                            <input type="radio" value="0" name="tijd_voor_uzelf_nodig"
                                                <?= !isset($antwoorden['tijd_voor_uzelf_nodig']) || $antwoorden['tijd_voor_uzelf_nodig'] == 0 ? "checked" : "" ?>>
                                            <label>Nee</label>
                                        </p>
                                    </div>
                                </div>
                                <div class="question">
                                    <p>- Wat zijn uw belangrijkste dagelijkse activiteiten?</p><textarea rows="1"
                                        cols="25" type="text"
                                        name="dagelijkse_activiteiten"><?= isset($antwoorden['dagelijkse_activiteiten']) ? $antwoorden['dagelijkse_activiteiten'] : '' ?></textarea>
                                </div>
                                <div class="question">
                                    <p>- Heeft u dagelijkse gewoonten?</p>
                                    <div class="checkboxes">
                                        <div class="question-answer">
                                            <input id="radio" type="radio" value="1" name="dagelijkse_gewoontes"
                                                <?= isset($antwoorden['dagelijkse_gewoontes']) && $antwoorden['dagelijkse_gewoontes'] == 1 ? "checked" : "" ?>>
                                            <label>Ja</label>
                                            <textarea rows="1" cols="25" id="checkfield" type="text"
                                                placeholder="welke?"
                                                name="dagelijkse_gewoontes_welke"><?= isset($antwoorden['dagelijkse_gewoontes_welke']) ? $antwoorden['dagelijkse_gewoontes_welke'] : '' ?></textarea>
                                        </div>
                                        <p>
                                            <input type="radio" value="0" name="dagelijkse_gewoontes"
                                                <?= !isset($antwoorden['dagelijkse_gewoontes']) || $antwoorden['dagelijkse_gewoontes'] == 0 ? "checked" : "" ?>>
                                            <label>Nee</label>
                                        </p>
                                    </div>
                                </div>
                                <div class="question">
                                    <p>Zijn er lichamelijke beperkingen waardoor u in uw activiteiten wordt belemmerd?
                                    </p>
                                    <div class="checkboxes">
                                        <div class="question-answer">
                                            <input id="radio" type="radio" value="1" name="lichamelijke_beperking"
                                                <?= isset($antwoorden['lichamelijke_beperking']) && $antwoorden['lichamelijke_beperking'] == 1 ? "checked" : "" ?>>
                                            <label>Ja</label>
                                            <textarea rows="1" cols="25" id="checkfield" type="text"
                                                placeholder="welke?"
                                                name="lichamelijke_beperking_welke"><?= isset($antwoorden['lichamelijke_beperking_welke']) ? $antwoorden['lichamelijke_beperking_welke'] : '' ?></textarea>
                                        </div>
                                        <p>
                                            <input type="radio" value="0" name="lichamelijke_beperking"
                                                <?= !isset($antwoorden['lichamelijke_beperking']) || $antwoorden['lichamelijke_beperking'] == 0 ? "checked" : "" ?>>
                                            <label>Nee</label>
                                        </p>
                                    </div>
                                </div>
                                <div class="question">
                                    <p>Heeft u vermoeidheidsklachten?</p>
                                    <div class="checkboxes">
                                        <p>
                                            <input type="radio" value="1" name="vermoeidheids_klachten"
                                                <?= isset($antwoorden['vermoeidheids_klachten']) && $antwoorden['vermoeidheids_klachten'] == 1 ? "checked" : "" ?>>
                                            <label>Ja</label>
                                        </p>
                                        <p>
                                            <input type="radio" value="0" name="vermoeidheids_klachten"
                                                <?= !isset($antwoorden['vermoeidheids_klachten']) || $antwoorden['vermoeidheids_klachten'] == 0 ? "checked" : "" ?>>
                                            <label>Nee</label>
                                        </p>
                                    </div>
                                </div>
                                <div class="question">
                                    <p>- Bent u de afgelopen tijd passiever geworden?</p>
                                    <div class="checkboxes">
                                        <div class="question-answer">
                                            <input id="radio" type="radio" value="1" name="passiever"
                                                <?= isset($antwoorden['passiever']) && $antwoorden['passiever'] == 1 ? "checked" : "" ?>>
                                            <label>Ja</label>
                                            <textarea rows="1" cols="25" id="checkfield" type="text"
                                                placeholder="blijkt uit?"
                                                name="passiever_blijktuit"><?= isset($antwoorden['passiever_blijktuit']) ? $antwoorden['passiever_blijktuit'] : '' ?></textarea>
                                        </div>
                                        <p>
                                            <input type="radio" value="0" name="passiever" <?= !isset($antwoorden['passiever']) || $antwoorden['passiever'] == 0 ? "checked" : "" ?>>
                                            <label>Nee</label>
                                        </p>
                                    </div>
                                </div>
                                <div class="question">
                                    <p>- Heeft u problemen met het starten van de dag?</p>
                                    <div class="checkboxes">
                                        <div class="question-answer">
                                            <input id="radio" type="radio" value="1" name="problemen_starten_dag"
                                                <?= isset($antwoorden['problemen_starten_dag']) && $antwoorden['problemen_starten_dag'] == 1 ? "checked" : "" ?>>
                                            <label>Ja</label>
                                            <textarea rows="1" cols="25" id="checkfield" type="text"
                                                placeholder="blijkt uit?"
                                                name="problemen_starten_dag_blijktuit"><?= isset($antwoorden['problemen_starten_dag_blijktuit']) ? $antwoorden['problemen_starten_dag_blijktuit'] : '' ?></textarea>
                                        </div>
                                        <p>
                                            <input type="radio" value="0" name="problemen_starten_dag"
                                                <?= !isset($antwoorden['problemen_starten_dag']) || $antwoorden['problemen_starten_dag'] == 0 ? "checked" : "" ?>>
                                            <label>Nee</label>
                                        </p>
                                    </div>
                                </div>
                                <div class="question">
                                    <p>Heeft u hobby's, doet u aan sport?</p>
                                    <div class="checkboxes">
                                        <p>
                                            <input type="radio" value="1" name="hobbys" <?= isset($antwoorden['hobbys']) && $antwoorden['hobbys'] == 1 ? "checked" : "" ?>>
                                            <label>Ja</label>
                                        </p>
                                        <p>
                                            <input type="radio" value="0" name="hobbys" <?= !isset($antwoorden['hobbys']) || $antwoorden['hobbys'] == 0 ? "checked" : "" ?>>
                                            <label>Nee</label>
                                        </p>
                                    </div>
                                </div>
                                <div class="question">
                                    <p>- Hoeveel tijd per dag besteedt u aan hobby's, vrijetijdsinvulling?</p><textarea
                                        rows="1" cols="25" type="text"
                                        name="hobbys_bestedingstijd"><?= isset($antwoorden['hobbys_bestedingstijd']) ? $antwoorden['hobbys_bestedingstijd'] : '' ?></textarea>
                                </div>
                                <div class="question">
                                    <p>Zijn er activiteiten weggevallen als gevolg van uw huidige problemen?</p>
                                    <div class="checkboxes">
                                        <div class="question-answer">
                                            <input id="radio" type="radio" value="1" name="activiteiten_weggevallen"
                                                <?= isset($antwoorden['activiteiten_weggevallen']) && $antwoorden['activiteiten_weggevallen'] == 1 ? "checked" : "" ?>>
                                            <label>Ja</label>
                                            <textarea rows="1" cols="25" id="checkfield" type="text"
                                                placeholder="en wel?"
                                                name="activiteiten_weggevallen_welke"><?= isset($antwoorden['activiteiten_weggevallen_welke']) ? $antwoorden['activiteiten_weggevallen_welke'] : '' ?></textarea>
                                        </div>
                                        <p>
                                            <input type="radio" value="0" name="activiteiten_weggevallen"
                                                <?= !isset($antwoorden['activiteiten_weggevallen']) || $antwoorden['activiteiten_weggevallen'] == 0 ? "checked" : "" ?>>
                                            <label>Nee</label>
                                        </p>
                                    </div>
                                </div>

                                <div class="observation">
                                    <h2>Verpleegkundige observatie bij dit patroon</h2>
                                    <div class="question">
                                        <div class="observe"><input type="checkbox" <?= isset($boolArrayObservatie[0]) && $boolArrayObservatie[0] == 1 ? "checked" : "" ?> value="1" name="observatie1">
                                            <p>(Dreigend) verminderd activiteitsvermogen</p>
                                        </div>
                                    </div>
                                    <div class="question">
                                        <div class="observe"><input type="checkbox" <?= isset($boolArrayObservatie[1]) && $boolArrayObservatie[1] == 1 ? "checked" : "" ?> value="1" name="observatie2">
                                            <p>Oververmoeidheid</p>
                                        </div>
                                    </div>
                                    <div class="question">
                                        <div class="observe"><input type="checkbox" <?= isset($boolArrayObservatie[2]) && $boolArrayObservatie[2] == 1 ? "checked" : "" ?> value="1" name="observatie3">
                                            <p>Mobiliteitstekort</p>
                                        </div>
                                    </div>
                                    <div class="question">
                                        <div class="observe"><input type="checkbox" <?= isset($boolArrayObservatie[3]) && $boolArrayObservatie[3] == 1 ? "checked" : "" ?> value="1" name="observatie4">
                                            <p>Ontspanningstekort</p>
                                        </div>
                                    </div>
                                    <div class="question">
                                        <div class="observe"><input type="checkbox" <?= isset($boolArrayObservatie[4]) && $boolArrayObservatie[4] == 1 ? "checked" : "" ?> value="1" name="observatie5">
                                            <p>Moeheid</p>
                                        </div>
                                    </div>
                                    <div class="question">
                                        <div class="observe"><input type="checkbox" <?= isset($boolArrayObservatie[5]) && $boolArrayObservatie[5] == 1? "checked" : "" ?> value="1" name="observatie6">
                                            <p>Verminderd huishoudvermogen</p>
                                        </div>
                                    </div>
                                    <div class="question">
                                        <div class="observe"><input type="checkbox" <?= isset($boolArrayObservatie[6]) && $boolArrayObservatie[6] == 1 ? "checked" : "" ?> value="1" name="observatie7">
                                            <p>Volledig tekort aan persoonlijke zorg</p>
                                        </div>
                                    </div>
                                </div>
                                <div class="observation">
                                    <div class="question">
                                        <p>Zelfstandigheidstekort in:</p>
                                    </div>
                                    <div class="question">
                                        <div class="observe"><input type="checkbox" <?= isset($boolArrayObservatie[7]) && $boolArrayObservatie[7] == 1 ? "checked" : "" ?> value="1" name="observatie8">
                                            <p>Wassen</p>
                                        </div>
                                    </div>
                                    <div class="question">
                                        <div class="observe"><input type="checkbox" <?= isset($boolArrayObservatie[8]) && $boolArrayObservatie[8] == 1 ? "checked" : "" ?> value="1" name="observatie9">
                                            <p>Kleding/verzorging</p>
                                        </div>
                                    </div>
                                    <div class="question">
                                        <div class="observe"><input type="checkbox" <?= isset($boolArrayObservatie[9]) && $boolArrayObservatie[9] == 1 ? "checked" : "" ?> value="1" name="observatie10">
                                            <p>Eten</p>
                                        </div>
                                    </div>
                                    <div class="question">
                                        <div class="observe"><input type="checkbox" <?= isset($boolArrayObservatie[10]) && $boolArrayObservatie[10] == 1  ? "checked" : "" ?> value="1" name="observatie11">
                                            <p>Toiletgang</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="submit">
                            <button name="navbutton" class="btn btn-secondary" type="submit"
                                value="prev">Vorige</button>
                            <button name="navbutton" class="btn btn-secondary" type="submit"
                                value="next">Volgende</button>
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

