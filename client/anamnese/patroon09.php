<?php
session_start();
include '../../database/DatabaseConnection.php';
include_once '../../models/autoload.php';
$Main = new Main();

$antwoorden = $Main->getPatternAnswers($_SESSION['clientId'], 9);

$boolArrayGerichtheid = isset($antwoorden['seksuele_gerichtheid']) && $antwoorden['seksuele_gerichtheid'] != null ? str_split($antwoorden['seksuele_gerichtheid']) : [];
$boolArrayObservatie = isset($antwoorden['observatie']) && $antwoorden['observatie'] != null ? str_split($antwoorden['observatie']) : [];

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_REQUEST['navbutton'])) {
    //Lees ingevulde gegevens.
    $verandering_seksuele_beleving = $_POST['verandering_seksuele_beleving'];
    $verandering_seksuele_beleving_door = strval($_POST['verandering_seksuele_beleving_door']);
    $verandering_seksueel_gedrag = $_POST['verandering_seksueel_gedrag'];
    $wisselende_contacten = $_POST['wisselende_contacten'];
    $veilig_vrijen = $_POST['veilig_vrijen'];
    $anticonceptiemiddel = $_POST['anticonceptiemiddel'];
    $anticonceptiemiddel_welke = strval($_POST['anticonceptiemiddel_welke']);
    $anticonceptiemiddel_problemen = $_POST['anticonceptiemiddel_problemen'];

    // array van checkboxes van seksuele gerichtheid tab
    $arr = array(!empty($_POST['gerichtheid1']), !empty($_POST['gerichtheid2']), !empty($_POST['gerichtheid3']));
    $seksuele_gerichtheid = $Main->convertBoolArrayToString($arr);

    $seksuele_gerichtheid_problemen = $_POST['seksuele_gerichtheid_problemen'];
    $soa = $_POST['soa'];
    $soa_welke = strval($_POST['soa_welke']);

    // array van checkboxes van observatie tab
    $arr = array(!empty($_POST['observatie1']), !empty($_POST['observatie2']), !empty($_POST['observatie3']), !empty($_POST['observatie4']));
    $observatie = $Main->convertBoolArrayToString($arr);

    //Haal vragenlijst ID op.
    $vragenlijstId = $Main->getVragenlijstId($_SESSION['clientId'], $_SESSION['loggedin_id']);
    // kijken of patroon10 bestaat door te kijken naar vragenlijst id
    $result = DatabaseConnection::getConn()->prepare("
                    SELECT p.id
                    FROM patroon09seksualiteitvoorplanting p
                    WHERE p.vragenlijstid =  ?");
    $result->bind_param("i", $vragenlijstId);
    $result->execute();
    $result = $result->get_result()->fetch_assoc();

    unset($_SESSION['patroonerror']);

    //opslaan in database.
    if ($result != null) {
        //update
        $result1 = DatabaseConnection::getConn()->prepare("UPDATE `patroon09seksualiteitvoorplanting`
            SET
            `verandering_seksuele_beleving` = ?,
            `verandering_seksuele_beleving_door`= ?,
            `verandering_seksueel_gedrag`= ?,
            `wisselende_contacten`= ?,
            `veilig_vrijen`= ?,
            `anticonceptiemiddel`= ?,
            `anticonceptiemiddel_welke`= ?,
            `anticonceptiemiddel_problemen`= ?,
            `seksuele_gerichtheid`= ?,
            `seksuele_gerichtheid_problemen`= ?,
            `soa`= ?,
            `soa_welke`= ?,
            `observatie`= ?
            WHERE `vragenlijstid`=?");
        if ($result1) {
            $result1->bind_param(
                "isiiiisisiissi",
                $verandering_seksuele_beleving,
                $verandering_seksuele_beleving_door,
                $verandering_seksueel_gedrag,
                $wisselende_contacten,
                $veilig_vrijen,
                $anticonceptiemiddel,
                $anticonceptiemiddel_welke,
                $anticonceptiemiddel_problemen,
                $seksuele_gerichtheid,
                $seksuele_gerichtheid_problemen,
                $soa,
                $soa_welke,
                $observatie,
                $vragenlijstId
            );
            $result1->execute();
        } else {
            // Handle error
            echo "Error preparing statement: " . DatabaseConnection::getConn()->error;
        }
    } else {
        try {
            $result2 = DatabaseConnection::getConn()->prepare("INSERT INTO `patroon09seksualiteitvoorplanting`(
                    `vragenlijstid`,
                    `verandering_seksuele_beleving`,
                    `verandering_seksuele_beleving_door`,
                    `verandering_seksueel_gedrag`,
                    `wisselende_contacten`,
                    `veilig_vrijen`,
                    `anticonceptiemiddel`,
                    `anticonceptiemiddel_welke`,
                    `anticonceptiemiddel_problemen`,
                    `seksuele_gerichtheid`,
                    `seksuele_gerichtheid_problemen`,
                    `soa`,
                    `soa_welke`,
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
            $result2->bind_param(
                "iisiiiisisiiss",
                $vragenlijstId,
                $verandering_seksuele_beleving,
                $verandering_seksuele_beleving_door,
                $verandering_seksueel_gedrag,
                $wisselende_contacten,
                $veilig_vrijen,
                $anticonceptiemiddel,
                $anticonceptiemiddel_welke,
                $anticonceptiemiddel_problemen,
                $seksuele_gerichtheid,
                $seksuele_gerichtheid_problemen,
                $soa,
                $soa_welke,
                $observatie
            );

            $result2->execute();
            $result2 = $result2->get_result();
        } catch (Exception $e) {
            // Display the alert box on next of previous page
            $_SESSION['patroonerror'] = 'Er ging iets fout, wijzigingen zijn NIET opgeslagen.';
            $_SESSION['patroonnr'] = '9. Seksualiteits- en voorplantingspatroon';
        }
    }

    switch ($_REQUEST['navbutton']) {
        case 'next': //action for next here
            header('Location: patroon10.php');
            break;

        case 'prev': //action for previous here
            header('Location: patroon08.php');
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
            ?>
            <?php
            include '../../includes/n-sidebar.php';
            ?>
            <div class="mt-5 pt-5 content">
                <div class="mt-4 mb-3 bg-white p-3" style="height: 90%; overflow: auto;">
                    <p class="card-text">
                    <div class="form-content">
                        <?php if (isset($_SESSION['patroonerror'])) { ?>
                            <div class="alert alert-warning">
                                <strong>Waarschuwing!</strong> <?php echo $_SESSION['patroonerror'] ?> in
                                <?php echo $_SESSION['patroonnr'] ?>
                            </div>
                        <?php } ?>
                        <div class="h4 text-primary">9. Seksualiteits- en voorplantingspatroon</div>
                        <div class="form">
                            <div class="questionnaire">
                                <div class="question">
                                    <p>Is uw seksuele beleving veranderd?</p>
                                    <div class="checkboxes">
                                        <div class="question-answer">
                                            <input id="radio" type="radio" value="1"
                                                name="verandering_seksuele_beleving"
                                                <?= (isset($antwoorden['verandering_seksuele_beleving']) && $antwoorden['verandering_seksuele_beleving'] == '1') ? "checked" : "" ?>>
                                            <label>Ja</label>
                                            <textarea rows="1" cols="25" id="checkfield" type="text" placeholder="door?"
                                                name="verandering_seksuele_beleving_door"><?= isset($antwoorden['verandering_seksuele_beleving_door']) ? $antwoorden['verandering_seksuele_beleving_door'] : '' ?></textarea>
                                        </div>
                                        <p>
                                            <input type="radio" value="0" name="verandering_seksuele_beleving"
                                                <?= (!isset($antwoorden['verandering_seksuele_beleving']) || $antwoorden['verandering_seksuele_beleving'] == '0') ? "checked" : "" ?>>
                                            <label>Nee</label>
                                        </p>
                                    </div>
                                </div>
                                <div class="question">
                                    <p>- Is er de laatste tijd verandering gekomen in uw seksuele gedrag?</p>
                                    <div class="checkboxes">
                                        <p>
                                            <input type="radio" value="1" name="verandering_seksueel_gedrag"
                                                <?= (isset($antwoorden['verandering_seksueel_gedrag']) && $antwoorden['verandering_seksueel_gedrag'] == '1') ? "checked" : "" ?>>
                                            <label>Ja</label>
                                        </p>
                                        <p>
                                            <input type="radio" value="0" name="verandering_seksueel_gedrag"
                                                <?= (!isset($antwoorden['verandering_seksueel_gedrag']) || $antwoorden['verandering_seksueel_gedrag'] == '0') ? "checked" : "" ?>>
                                            <label>Nee</label>
                                        </p>
                                    </div>
                                </div>
                                <div class="question">
                                    <p>- Heeft u wisselende seksuele contacten?</p>
                                    <div class="checkboxes">
                                        <p>
                                            <input type="radio" value="1" name="wisselende_contacten"
                                                <?= (isset($antwoorden['wisselende_contacten']) && $antwoorden['wisselende_contacten'] == '1') ? "checked" : "" ?>>
                                            <label>Ja</label>
                                        </p>
                                        <p>
                                            <input type="radio" value="0" name="wisselende_contacten"
                                                <?= (!isset($antwoorden['wisselende_contacten']) || $antwoorden['wisselende_contacten'] == '0') ? "checked" : "" ?>>
                                            <label>Nee</label>
                                        </p>
                                    </div>
                                </div>
                                <div class="question">
                                    <p>- Houdt u bij uw seksuele activiteiten rekening met veilig vrijen?</p>
                                    <div class="checkboxes">
                                        <p>
                                            <input type="radio" value="1" name="veilig_vrijen"
                                                <?= (isset($antwoorden['veilig_vrijen']) && $antwoorden['veilig_vrijen'] == '1') ? "checked" : "" ?>>
                                            <label>Ja</label>
                                        </p>
                                        <p>
                                            <input type="radio" value="0" name="veilig_vrijen"
                                                <?= (!isset($antwoorden['veilig_vrijen']) || $antwoorden['veilig_vrijen'] == '0') ? "checked" : "" ?>>
                                            <label>Nee</label>
                                        </p>
                                    </div>
                                </div>
                                <div class="question">
                                    <p>Gebruikt u anticonceptiemiddelen?</p>
                                    <div class="checkboxes">
                                        <div class="question-answer">
                                            <input id="radio" type="radio" value="1" name="anticonceptiemiddel"
                                                <?= (isset($antwoorden['anticonceptiemiddel']) && $antwoorden['anticonceptiemiddel'] == '1') ? "checked" : "" ?>>
                                            <label>Ja</label>
                                            <textarea rows="1" cols="25" id="checkfield" type="text"
                                                placeholder="welke?"
                                                name="anticonceptiemiddel_welke"> <?= isset($antwoorden['anticonceptiemiddel_welke']) ? $antwoorden['anticonceptiemiddel_welke'] : '' ?></textarea>
                                        </div>
                                        <p>
                                            <input type="radio" value="0" name="anticonceptiemiddel"
                                                <?= (!isset($antwoorden['anticonceptiemiddel']) || $antwoorden['anticonceptiemiddel'] == '0') ? "checked" : "" ?>>
                                            <label>Nee</label>
                                        </p>
                                    </div>
                                </div>
                                <div class="question">
                                    <p>- Heeft u problemen bij het gebruik van anticonceptie-middelen?</p>
                                    <div class="checkboxes">
                                        <p>
                                            <input type="radio" value="1" name="anticonceptiemiddel_problemen"
                                                <?= (isset($antwoorden['anticonceptiemiddel_problemen']) && $antwoorden['anticonceptiemiddel_problemen'] == '1') ? "checked" : "" ?>>
                                            <label>Ja</label>
                                        </p>
                                        <p>
                                            <input type="radio" value="0" name="anticonceptiemiddel_problemen"
                                                <?= (!isset($antwoorden['anticonceptiemiddel_problemen']) || $antwoorden['anticonceptiemiddel_problemen'] == '0') ? "checked" : "" ?>>
                                            <label>Nee</label>
                                        </p>
                                    </div>
                                </div>
                                <div class="question">
                                    <p>Wat is uw seksuele gerichtheid?</p>
                                    <div class="observation">
                                        <div class="question">
                                            <div class="observe"><input type="checkbox"
                                                    <?= (isset($boolArrayGerichtheid[0]) && $boolArrayGerichtheid[0] == '1') ? "checked" : "" ?>
                                                    name="gerichtheid1">
                                                <p>Heteroseksueel</p>
                                            </div>
                                        </div>
                                        <div class="question">
                                            <div class="observe"><input type="checkbox"
                                                    <?= (isset($boolArrayGerichtheid[1]) && $boolArrayGerichtheid[1] == '1') ? "checked" : "" ?>
                                                    name="gerichtheid2">
                                                <p>Biseksueel</p>
                                            </div>
                                        </div>
                                        <div class="question">
                                            <div class="observe"><input type="checkbox"
                                                    <?= (isset($boolArrayGerichtheid[2]) && $boolArrayGerichtheid[2] == '1') ? "checked" : "" ?>
                                                    name="gerichtheid3">
                                                <p>Homoseksueel</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="question">
                                    <p>- Ondervindt u problemen bij u zelf of bij anderen ten aanzien van uw seksuele
                                        gerichtheid?</p>
                                    <div class="checkboxes">
                                        <p>
                                            <input type="radio" value="1" name="seksuele_gerichtheid_problemen"
                                                <?= (isset($antwoorden['seksuele_gerichtheid_problemen']) && $antwoorden['seksuele_gerichtheid_problemen'] == '1') ? "checked" : "" ?>>
                                            <label>Ja</label>
                                        </p>
                                        <p>
                                            <input type="radio" value="0" name="seksuele_gerichtheid_problemen"
                                                <?= (!isset($antwoorden['seksuele_gerichtheid_problemen']) || $antwoorden['seksuele_gerichtheid_problemen'] == '0') ? "checked" : "" ?>>
                                            <label>Nee</label>
                                        </p>
                                    </div>
                                </div>
                                <div class="question">
                                    <p>Heeft u last (gehad) van seksueel overdraagbare aandoeningen (soa)?</p>
                                    <div class="checkboxes">
                                        <div class="question-answer">
                                            <input id="radio" type="radio" value="1" name="soa"
                                                <?= (isset($antwoorden['soa']) && $antwoorden['soa'] == '1') ? "checked" : "" ?>>
                                            <label>Ja</label>
                                            <textarea rows="1" cols="25" id="checkfield" type="text"
                                                placeholder="en wel?"
                                                name="soa_welke"> <?= isset($antwoorden['soa_welke']) ? $antwoorden['soa_welke'] : '' ?></textarea>
                                        </div>
                                        <p>
                                            <input type="radio" value="0" name="soa" <?= (!isset($antwoorden['soa']) || $antwoorden['soa'] == '0') ? "checked" : "" ?>>
                                            <label>Nee</label>
                                        </p>
                                    </div>
                                </div>

                                <div class="observation">
                                    <h2>Verpleegkundige observatie bij dit patroon</h2>
                                    <div class="question">
                                        <div class="observe"><input type="checkbox" <?= (isset($boolArrayObservatie[0]) && $boolArrayObservatie[0] == '1') ? "checked" : "" ?> name="observatie1">
                                            <p>Gewijzigde seksuele gewoonten</p>
                                        </div>
                                    </div>
                                    <div class="question">
                                        <div class="observe"><input type="checkbox" <?= (isset($boolArrayObservatie[1]) && $boolArrayObservatie[1] == '1') ? "checked" : "" ?> name="observatie2">
                                            <p>Seksueel disfunctioneren</p>
                                        </div>
                                    </div>
                                    <div class="question">
                                        <div class="observe"><input type="checkbox" <?= (isset($boolArrayObservatie[2]) && $boolArrayObservatie[2] == '1') ? "checked" : "" ?> name="observatie3">
                                            <p>Verkrachtingssyndroom gecompliceerde vorm</p>
                                        </div>
                                    </div>
                                    <div class="question">
                                        <div class="observe"><input type="checkbox" <?= (isset($boolArrayObservatie[3]) && $boolArrayObservatie[3] == '1') ? "checked" : "" ?> name="observatie4">
                                            <p>Verkrachtingssyndroom stille vorm</p>
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