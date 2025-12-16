<?php
session_start();
include '../../includes/auth.php';
include '../../database/DatabaseConnection.php';
include_once '../../models/autoload.php';
$Main = new Main();

$antwoorden = $Main->getPatternAnswers($_SESSION['clientId'], 4);
$boolArrayObservatie = isset($antwoorden['observatie']) && $antwoorden['observatie'] !== null ? str_split($antwoorden['observatie']) : [];

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_REQUEST['navbutton'])) {
    // Observatie array (6 checkboxes → string zoals "101010")
    $observatieArray = [];
    for ($i = 1; $i <= 6; $i++) {
        $observatieArray[] = isset($_POST["observatie$i"]) && $_POST["observatie$i"] == 1 ? "1" : "0";
    }
    $observatie = implode("", $observatieArray);

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

    if (isset($_REQUEST['navbutton'])) {
        switch ($_REQUEST['navbutton']) {
            case 'next': //action for next here
                header('Location: patroon05.php');
                break;

            case 'prev': //action for previous here
                header('Location: patroon03.php');
                break;
        }
        exit;
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
                                    <div class="observe"><input type="checkbox" name="voedingCheckbox" value="1" <?php if ($antwoorden['voeding'] == 1) {
                                        echo "checked";
                                    } ?>>
                                        <p>Voeding</p>
                                    </div><input type="number" min="0" max="4"
                                        value="<?= isset($antwoorden['voeding']) ? $antwoorden['voeding'] : '' ?>"
                                        name="voeding">
                                </div>
                                <div class="question">
                                    <div class="observe"><input type="checkbox" value="1" name="aankledenCheckbox" <?php if ($antwoorden['aankleden'] == 1) {
                                        echo "checked";
                                    } ?>>
                                        <p>Aankleden</p>
                                    </div><input type="number" min="0" max="4"
                                        value="<?= isset($antwoorden['aankleden']) ? $antwoorden['aankleden'] : '' ?>"
                                        name="aankleden">
                                </div>
                                <div class="question">
                                    <div class="observe"><input type="checkbox" name="alg_mobiliteitCheckbox" value="1" <?php if ($antwoorden['alg_mobiliteit'] == 1) {
                                        echo "checked";
                                    } ?>>
                                        <p>Algemene mobiliteit</p>
                                    </div><input type="number" min="0" max="4"
                                        value="<?= isset($antwoorden['alg_mobiliteit']) ? $antwoorden['alg_mobiliteit'] : '' ?>"
                                        name="alg_mobiliteit">
                                </div>
                                <div class="question">
                                    <div class="observe"><input type="checkbox" name="kokenCheckbox" <?php if (isset($antwoorden['koken']) == "0" || isset($antwoorden['koken']) > 0) {
                                        echo "checked";
                                    } ?>>
                                        <p>Koken</p>
                                    </div><input type="number" min="0" max="4"
                                        value="<?= isset($antwoorden['koken']) ? $antwoorden['koken'] : '' ?>"
                                        name="koken">
                                </div>
                                <div class="question">
                                    <div class="observe"><input type="checkbox" name="huishoudenCheckbox" <?php if (isset($antwoorden['huishouden']) == "0" || isset($antwoorden['huishouden']) > 0) {
                                        echo "checked";
                                    } ?>>
                                        <p>Huishouden</p>
                                    </div><input type="number" min="0" max="4"
                                        value="<?= isset($antwoorden['huishouden']) ? $antwoorden['huishouden'] : '' ?>"
                                        name="huishouden">
                                </div>
                                <div class="question">
                                    <div class="observe"><input type="checkbox" name="financienCheckbox" <?php if (isset($antwoorden['financien']) == "0" || isset($antwoorden['financien']) > 0) {
                                        echo "checked";
                                    } ?>>
                                        <p>Financiën</p>
                                    </div><input type="number" min="0" max="4"
                                        value="<?= isset($antwoorden['financien']) ? $antwoorden['financien'] : '' ?>"
                                        name="financien">
                                </div>
                                <div class="question">
                                    <div class="observe"><input type="checkbox" name="verzorgingCheckbox" <?php if (isset($antwoorden['verzorging']) == "0" || isset($antwoorden['verzorging']) > 0) {
                                        echo "checked";
                                    } ?>>
                                        <p>Verzorging</p>
                                    </div><input type="number" min="0" max="4"
                                        value="<?= isset($antwoorden['verzorging']) ? $antwoorden['verzorging'] : '' ?>"
                                        name="verzorging">
                                </div>
                                <div class="question">
                                    <div class="observe"><input type="checkbox" name="badenCheckbox" <?php if (isset($antwoorden['baden']) == "0" || isset($antwoorden['baden']) > 0) {
                                        echo "checked";
                                    } ?>>
                                        <p>Baden</p>
                                    </div><input type="number" min="0" max="4"
                                        value="<?= isset($antwoorden['baden']) ? $antwoorden['baden'] : '' ?>"
                                        name="baden">
                                </div>
                                <div class="question">
                                    <div class="observe"><input type="checkbox" name="toiletgangCheckbox" <?php if (isset($antwoorden['toiletgang']) == "0" || isset($antwoorden['toiletgang']) > 0) {
                                        echo "checked";
                                    } ?>>
                                        <p>Toiletgang</p>
                                    </div><input type="number" min="0" max="4"
                                        value="<?= isset($antwoorden['toiletgang']) ? $antwoorden['toiletgang'] : '' ?>"
                                        name="toiletgang">
                                </div>
                                <div class="question">
                                    <div class="observe"><input type="checkbox" name="uit_bed_komenCheckbox" <?php if (isset($antwoorden['uit_bed_komen']) == "0" || isset($antwoorden['uit_bed_komen']) > 0) {
                                        echo "checked";
                                    } ?>>
                                        <p>Uit bed komen</p>
                                    </div><input type="number" min="0" max="4"
                                        value="<?= isset($antwoorden['uit_bed_komen']) ? $antwoorden['uit_bed_komen'] : '' ?>"
                                        name="uit_bed_komen">
                                </div>
                                <div class="question">
                                    <div class="observe"><input type="checkbox" name="winkelenCheckbox" <?php if (isset($antwoorden['winkelen']) == "0" || isset($antwoorden['winkelen']) > 0) {
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
                                            <input id="radio" type="radio" name="tijd_voor_uzelf_nodig" value="1"
                                                <?= $antwoorden['tijd_voor_uzelf_nodig'] == 1 ? "checked" : "" ?>>
                                            <label>Ja</label>
                                            <textarea rows="1" cols="25" id="checkfield" type="text"
                                                placeholder="blijkt uit?"
                                                name="tijd_voor_uzelf_nodig_blijktuit"><?= isset($antwoorden['tijd_voor_uzelf_nodig_blijktuit']) ? $antwoorden['tijd_voor_uzelf_nodig_blijktuit'] : '' ?></textarea>
                                        </div>
                                        <p>
                                            <input type="radio" name="tijd_voor_uzelf_nodig" value="0"
                                                <?= $antwoorden['tijd_voor_uzelf_nodig'] == 0 ? "checked" : "" ?>>
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
                                            <input id="radio" type="radio" name="dagelijkse_gewoontes" value="1"
                                                <?= $antwoorden['dagelijkse_gewoontes'] == 1 ? "checked" : "" ?>>
                                            <label>Ja</label>
                                            <textarea rows="1" cols="25" id="checkfield" type="text"
                                                placeholder="welke?"
                                                name="dagelijkse_gewoontes_welke"><?= isset($antwoorden['dagelijkse_gewoontes_welke']) ? $antwoorden['dagelijkse_gewoontes_welke'] : '' ?></textarea>
                                        </div>
                                        <p>
                                            <input type="radio" name="dagelijkse_gewoontes" value="0"
                                                <?= $antwoorden['dagelijkse_gewoontes'] == 0 ? "checked" : "" ?>>
                                            <label>Nee</label>
                                        </p>
                                    </div>
                                </div>
                                <div class="question">
                                    <p>Zijn er lichamelijke beperkingen waardoor u in uw activiteiten wordt belemmerd?
                                    </p>
                                    <div class="checkboxes">
                                        <div class="question-answer">
                                            <input id="radio" type="radio" name="lichamelijke_beperking" value="1"
                                                <?= $antwoorden['lichamelijke_beperking'] == 1 ? "checked" : "" ?>>
                                            <label>Ja</label>
                                            <textarea rows="1" cols="25" id="checkfield" type="text"
                                                placeholder="welke?"
                                                name="lichamelijke_beperking_welke"><?= isset($antwoorden['lichamelijke_beperkingen_welke']) ? $antwoorden['lichamelijke_beperkingen_welke'] : '' ?></textarea>
                                        </div>
                                        <p>
                                            <input type="radio" name="lichamelijke_beperking" value="0"
                                                <?= $antwoorden['lichamelijke_beperking'] == 0 ? "checked" : "" ?>>
                                            <label>Nee</label>
                                        </p>
                                    </div>
                                </div>
                                <div class="question">
                                    <p>Heeft u vermoeidheidsklachten?</p>
                                    <div class="checkboxes">
                                        <p>
                                            <input type="radio" name="vermoeidheids_klachten" value="1"
                                                <?= $antwoorden['vermoeidheids_klachten'] == 1 ? "checked" : "" ?>>
                                            <label>Ja</label>
                                        </p>
                                        <p>
                                            <input type="radio" name="vermoeidheids_klachten" value="0"
                                                <?= $antwoorden['vermoeidheids_klachten'] == 0 ? "checked" : "" ?>>
                                            <label>Nee</label>
                                        </p>
                                    </div>
                                </div>
                                <div class="question">
                                    <p>- Bent u de afgelopen tijd passiever geworden?</p>
                                    <div class="checkboxes">
                                        <div class="question-answer">
                                            <input id="radio" type="radio" name="passiever" value="1"
                                                <?= $antwoorden['passiever'] == 1 ? "checked" : "" ?>>
                                            <label>Ja</label>
                                            <textarea rows="1" cols="25" id="checkfield" type="text"
                                                placeholder="blijkt uit?"
                                                name="passiever_blijktuit"><?= isset($antwoorden['passiever_blijktuit']) ? $antwoorden['passiever_blijktuit'] : '' ?></textarea>
                                        </div>
                                        <p>
                                            <input type="radio" name="passiever" value="0" <?= $antwoorden['passiever'] == 0 ? "checked" : "" ?>>
                                            <label>Nee</label>
                                        </p>
                                    </div>
                                </div>
                                <div class="question">
                                    <p>- Heeft u problemen met het starten van de dag?</p>
                                    <div class="checkboxes">
                                        <div class="question-answer">
                                            <input id="radio" type="radio" name="problemen_starten_dag" value="1"
                                                <?= $antwoorden['problemen_starten_dag'] == 1 ? "checked" : "" ?>>
                                            <label>Ja</label>
                                            <textarea rows="1" cols="25" id="checkfield" type="text"
                                                placeholder="blijkt uit?"
                                                name="problemen_starten_dag_blijktuit"><?= isset($antwoorden['problemen_starten_dag_blijktuit']) ? $antwoorden['problemen_starten_dag_blijktuit'] : '' ?></textarea>
                                        </div>
                                        <p>
                                            <input type="radio" name="problemen_starten_dag" value="0"
                                                <?= $antwoorden['problemen_starten_dag'] == 0 ? "checked" : "" ?>>
                                            <label>Nee</label>
                                        </p>
                                    </div>
                                </div>
                                <div class="question">
                                    <p>Heeft u hobby's, doet u aan sport?</p>
                                    <div class="checkboxes">
                                        <p>
                                            <input type="radio" name="hobbys" value="1" <?= $antwoorden['hobbys'] == 1 ? "checked" : "" ?>>
                                            <label>Ja</label>
                                        </p>
                                        <p>
                                            <input type="radio" name="hobbys" value="0" <?= $antwoorden['hobbys'] == 0 ? "checked" : "" ?>>
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
                                            <input id="radio" type="radio" name="activiteiten_weggevallen" value="1"
                                                <?= $antwoorden['activiteiten_weggevallen'] == 1 ? "checked" : "" ?>>
                                            <label>Ja</label>
                                            <textarea rows="1" cols="25" id="checkfield" type="text"
                                                placeholder="en wel?"
                                                name="activiteiten_weggevallen_welke"><?= isset($antwoorden['activiteiten_weggevallen_welke']) ? $antwoorden['activiteiten_weggevallen_welke'] : '' ?></textarea>
                                        </div>
                                        <p>
                                            <input type="radio" name="activiteiten_weggevallen" value="0"
                                                <?= $antwoorden['activiteiten_weggevallen'] == 0 ? "checked" : "" ?>>
                                            <label>Nee</label>
                                        </p>
                                    </div>
                                </div>

                                <div class="observation">
                                    <h2>Verpleegkundige observatie bij dit patroon</h2>
                                    <div class="question">
                                        <div class="observe"><input type="checkbox" <?= isset($boolArrayObservatie[0]) && $boolArrayObservatie[0] == '1' ? "checked" : "" ?> value="1" name="observatie1">
                                            <p>(Dreigend) verminderd activiteitsvermogen</p>
                                        </div>
                                    </div>
                                    <div class="question">
                                        <div class="observe"><input type="checkbox" <?= isset($boolArrayObservatie[1]) && $boolArrayObservatie[1] == '1' ? "checked" : "" ?> value="1" name="observatie2">
                                            <p>Oververmoeidheid</p>
                                        </div>
                                    </div>
                                    <div class="question">
                                        <div class="observe"><input type="checkbox" <?= isset($boolArrayObservatie[2]) && $boolArrayObservatie[2] == '1' ? "checked" : "" ?> value="1" name="observatie3">
                                            <p>Mobiliteitstekort</p>
                                        </div>
                                    </div>
                                    <div class="question">
                                        <div class="observe"><input type="checkbox" <?= isset($boolArrayObservatie[3]) && $boolArrayObservatie[3] == '1' ? "checked" : "" ?> value="1" name="observatie4">
                                            <p>Ontspanningstekort</p>
                                        </div>
                                    </div>
                                    <div class="question">
                                        <div class="observe"><input type="checkbox" <?= isset($boolArrayObservatie[4]) && $boolArrayObservatie[4] == '1' ? "checked" : "" ?> value="1" name="observatie5">
                                            <p>Moeheid</p>
                                        </div>
                                    </div>
                                    <div class="question">
                                        <div class="observe"><input type="checkbox" <?= isset($boolArrayObservatie[5]) && $boolArrayObservatie[5] == '1' ? "checked" : "" ?> value="1" name="observatie6">
                                            <p>Verminderd huishoudvermogen</p>
                                        </div>
                                    </div>
                                    <div class="question">
                                        <div class="observe"><input type="checkbox" <?= isset($boolArrayObservatie[6]) && $boolArrayObservatie[6] == '1' ? "checked" : "" ?> value="1" name="observatie7">
                                            <p>Volledig tekort aan persoonlijke zorg</p>
                                        </div>
                                    </div>
                                </div>
                                <div class="observation">
                                    <div class="question">
                                        <p>Zelfstandigheidstekort in:</p>
                                    </div>
                                    <div class="question">
                                        <div class="observe"><input type="checkbox" <?= isset($boolArrayObservatie[7]) ? "checked" : "" ?> name="observatie8">
                                            <p>Wassen</p>
                                        </div>
                                    </div>
                                    <div class="question">
                                        <div class="observe"><input type="checkbox" <?= isset($boolArrayObservatie[8]) ? "checked" : "" ?> name="observatie9">
                                            <p>Kleding/verzorging</p>
                                        </div>
                                    </div>
                                    <div class="question">
                                        <div class="observe"><input type="checkbox" <?= isset($boolArrayObservatie[9]) ? "checked" : "" ?> name="observatie10">
                                            <p>Eten</p>
                                        </div>
                                    </div>
                                    <div class="question">
                                        <div class="observe"><input type="checkbox" <?= isset($boolArrayObservatie[10]) ? "checked" : "" ?> name="observatie11">
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