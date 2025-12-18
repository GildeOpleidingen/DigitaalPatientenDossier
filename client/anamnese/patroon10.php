<?php
session_start();
include '../../includes/auth.php';
include '../../database/DatabaseConnection.php';
include_once '../../models/autoload.php';
$Main = new Main();

$antwoorden = $Main->getPatternAnswers($_SESSION['clientId'], 10);

$boolArrayReacties = isset($antwoorden['reactie_spanningen']) && $antwoorden['reactie_spanningen'] !== null ? str_split($antwoorden['reactie_spanningen']) : [];
$boolArrayObservatie = isset($antwoorden['observatie']) && $antwoorden['observatie'] !== null ? str_split($antwoorden['observatie']) : [];

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_REQUEST['navbutton'])) {
    //Lees ingevulde gegevens.
    $reactie_anders = $_POST['reactie_anders'];
    $spanningsvolle_situaties_voorkomen = $_POST['spanningsvolle_situaties_voorkomen'];
    $spanningsvolle_situaties_voorkomen_hoe = strval($_POST['spanningsvolle_situaties_voorkomen_hoe']);
    $spanningsvolle_situaties_oplossen = $_POST['spanningsvolle_situaties_oplossen'];
    $spanningsvolle_situaties_oplossen_hoe = strval($_POST['spanningsvolle_situaties_oplossen_hoe']);
    $omstandigheden_in_war_raken = $_POST['omstandigheden_in_war_raken'];
    $omstandigheden_in_war_raken_welke = strval($_POST['omstandigheden_in_war_raken_welke']);
    $angstig_paniek = $_POST['angstig_paniek'];
    $angstig_paniek_actie = strval($_POST['angstig_paniek_actie']);
    $angstig_paniek_lukt_voorkomen = $_POST['angstig_paniek_lukt_voorkomen'];
    $suicidaal = $_POST['suicidaal'];
    $suicidaal_momenteel = $_POST['suicidaal_momenteel'];
    $agressief = $_POST['agressief'];
    $anderen_iets_aan_willen_doen = $_POST['anderen_iets_aan_willen_doen'];
    $maatregelen_veiligheid = $_POST['maatregelen_veiligheid'];
    $maatregelen_veiligheid_door = strval($_POST['maatregelen_veiligheid_door']);
    $moeite_uiten_gevoelens = $_POST['moeite_uiten_gevoelens'];
    $bespreken_gevoelens_met = strval($_POST['bespreken_gevoelens_met']);

    // array van checkboxes van reacties tab
    $arr = array(!empty($_POST['reactie1']), !empty($_POST['reactie2']), !empty($_POST['reactie3']), !empty($_POST['reactie4']), !empty($_POST['reactie5']), !empty($_POST['reactie6']), !empty($_POST['reactie7']), !empty($_POST['reactie8']), !empty($_POST['reactie9']), !empty($_POST['reactie10']), !empty($_POST['reactie11']), !empty($_POST['reactie12']), !empty($_POST['reactie13']));
    $reactie_spanningen = $Main->convertBoolArrayToString($arr);

    // array van checkboxes van observatie tab
    $arr = array(!empty($_POST['observatie1']), !empty($_POST['observatie2']), !empty($_POST['observatie3']), !empty($_POST['observatie4']), !empty($_POST['observatie5']), !empty($_POST['observatie6']), !empty($_POST['observatie7']), !empty($_POST['observatie8']), !empty($_POST['observatie9']), !empty($_POST['observatie10']));
    $observatie = $Main->convertBoolArrayToString($arr);

    //Haal vragenlijst ID op.
    $vragenlijstId = $Main->getVragenlijstId($_SESSION['clientId'], $_SESSION['loggedin_id']);
    // kijken of patroon10 bestaat door te kijken naar vragenlijst id
    $result = DatabaseConnection::getConn()->prepare("
                    SELECT p.id
                    FROM patroon10stressverwerking p
                    WHERE p.vragenlijstid =  ?");
    $result->bind_param("i", $vragenlijstId);
    $result->execute();
    $result = $result->get_result()->fetch_assoc();

    unset($_SESSION['patroonerror']);

    if ($result != null) {
        //update
        $result1 = DatabaseConnection::getConn()->prepare("UPDATE `patroon10stressverwerking`
            SET
            `reactie_spanningen`= ?,
            `reactie_anders`= ?,
            `spanningsvolle_situaties_voorkomen`= ?,
            `spanningsvolle_situaties_voorkomen_hoe`= ?,
            `spanningsvolle_situaties_oplossen`= ?,
            `spanningsvolle_situaties_oplossen_hoe`= ?,
            `omstandigheden_in_war_raken`= ?,
            `omstandigheden_in_war_raken_welke`= ?,
            `angstig_paniek`= ?,
            `angstig_paniek_actie`= ?,
            `angstig_paniek_lukt_voorkomen`= ?,
            `suicidaal`= ?,
            `suicidaal_momenteel`= ?,
            `agressief`= ?,
            `anderen_iets_aan_willen_doen`= ?,
            `maatregelen_veiligheid`= ?,
            `maatregelen_veiligheid_door`= ?,
            `moeite_uiten_gevoelens`= ?,
            `bespreken_gevoelens_met`= ?,
            `observatie`= ?
            WHERE `vragenlijstid`=?");
        if ($result1) {
            $result1->bind_param("ssisisisisiiiiiisissi", 
                $reactie_spanningen, 
                $reactie_anders,
                $spanningsvolle_situaties_voorkomen,
                $spanningsvolle_situaties_voorkomen_hoe,
                $spanningsvolle_situaties_oplossen,
                $spanningsvolle_situaties_oplossen_hoe, 
                $omstandigheden_in_war_raken,
                $omstandigheden_in_war_raken_welke,
                $angstig_paniek,
                $angstig_paniek_actie,
                $angstig_paniek_lukt_voorkomen,
                $suicidaal,
                $suicidaal_momenteel,
                $agressief,
                $anderen_iets_aan_willen_doen,
                $maatregelen_veiligheid,
                $maatregelen_veiligheid_door,
                $moeite_uiten_gevoelens,
                $bespreken_gevoelens_met, 
                $observatie, 
                $vragenlijstId);
            $result1->execute();
        } else {
            // Handle error
            echo "Error preparing statement: " . DatabaseConnection::getConn()->error;
        }
    } else {
        //hier insert je alle data in patroon02
        try{
            $result2 = DatabaseConnection::getConn()->prepare("INSERT INTO `patroon10stressverwerking`(
                    `vragenlijstid`,
                    `reactie_spanningen`,
                    `reactie_anders`,
                    `spanningsvolle_situaties_voorkomen`,
                    `spanningsvolle_situaties_voorkomen_hoe`,
                    `spanningsvolle_situaties_oplossen`,
                    `spanningsvolle_situaties_oplossen_hoe`,
                    `omstandigheden_in_war_raken`,
                    `omstandigheden_in_war_raken_welke`,
                    `angstig_paniek`,
                    `angstig_paniek_actie`,
                    `angstig_paniek_lukt_voorkomen`,
                    `suicidaal`,
                    `suicidaal_momenteel`,
                    `agressief`,
                    `anderen_iets_aan_willen_doen`,
                    `maatregelen_veiligheid`,
                    `maatregelen_veiligheid_door`,
                    `moeite_uiten_gevoelens`,
                    `bespreken_gevoelens_met`,
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
                        ?,
                        ?,
                        ?,
                        ?,
                        ?,
                        ?,
                        ?,
                        ?)");
            $result2->bind_param("issisisisisiiiiiisiss", 
                $vragenlijstId, 
                $reactie_spanningen, 
                $reactie_anders,
                $spanningsvolle_situaties_voorkomen,
                $spanningsvolle_situaties_voorkomen_hoe,
                $spanningsvolle_situaties_oplossen,
                $spanningsvolle_situaties_oplossen_hoe, 
                $omstandigheden_in_war_raken,
                $omstandigheden_in_war_raken_welke,
                $angstig_paniek,
                $angstig_paniek_actie,
                $angstig_paniek_lukt_voorkomen,
                $suicidaal,
                $suicidaal_momenteel,
                $agressief,
                $anderen_iets_aan_willen_doen,
                $maatregelen_veiligheid,
                $maatregelen_veiligheid_door,
                $moeite_uiten_gevoelens,
                $bespreken_gevoelens_met, 
                $observatie);
            $result2->execute();
            $result2 = $result2->get_result();
        } catch (Exception $e) {
            // Display the alert box on next of previous page
            $_SESSION['patroonerror'] = 'Er ging iets fout, wijzigingen zijn NIET opgeslagen.';
            $_SESSION['patroonnr'] = '10. Stressverwerkingspatroon (probleemhantering)';
        }
    }

    switch ($_REQUEST['navbutton']) {
        case 'next': //action for next here
            header('Location: patroon11.php');
            break;

        case 'prev': //action for previous here
            header('Location: patroon09.php');
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
            ?>
            <?php
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
                        <div class="h4 text-primary">10. Stressverwerkingspatroon (probleemhantering)</div>
                        <div class="form">
                            <div class="questionnaire">
                                <div class="question">
                                    <p>Hoe reageert u gewoonlijk op situaties die spanningen oproepen?</p>
                                    <div class="observation">
                                        <div class="question">
                                            <div class="observe"><input type="checkbox" <?= (isset($boolArrayReacties[0]) && $boolArrayReacties[0] == '1') ? "checked" : "" ?> name="reactie1">
                                                <p>Zoveel mogelijk vermijden</p>
                                            </div>
                                        </div>
                                        <div class="question">
                                            <div class="observe"><input type="checkbox" <?= (isset($boolArrayReacties[1]) && $boolArrayReacties[1] == '1') ? "checked" : "" ?> name="reactie2">
                                                <p>Drugs gebruiken</p>
                                            </div>
                                        </div>
                                        <div class="question">
                                            <div class="observe"><input type="checkbox" <?= (isset($boolArrayReacties[2]) && $boolArrayReacties[2] == '1') ? "checked" : "" ?> name="reactie3">
                                                <p>Ontwikkeling van lichamelijke symptomen</p>
                                            </div>
                                        </div>
                                        <div class="question">
                                            <div class="observe"><input type="checkbox" <?= (isset($boolArrayReacties[3]) && $boolArrayReacties[3] == '1') ? "checked" : "" ?> name="reactie4">
                                                <p>Medicatie</p>
                                            </div>
                                        </div>
                                        <div class="question">
                                            <div class="observe"><input type="checkbox" <?= (isset($boolArrayReacties[4]) && $boolArrayReacties[4] == '1') ? "checked" : "" ?> name="reactie5">
                                                <p>Meer/minder eten</p>
                                            </div>
                                        </div>
                                        <div class="question">
                                            <div class="observe"><input type="checkbox" <?= (isset($boolArrayReacties[5]) && $boolArrayReacties[5] == '1') ? "checked" : "" ?> name="reactie6">
                                                <p>Agressie</p>
                                            </div>
                                        </div>
                                        <div class="question">
                                            <div class="observe"><input type="checkbox" <?= (isset($boolArrayReacties[6]) && $boolArrayReacties[6] == '1') ? "checked" : "" ?> name="reactie7">
                                                <p>Praten met anderen</p>
                                            </div>
                                        </div>
                                        <div class="question">
                                            <div class="observe"><input type="checkbox" <?= (isset($boolArrayReacties[7]) && $boolArrayReacties[7] == '1') ? "checked" : "" ?> name="reactie8">
                                                <p>Alcohol drinken</p>
                                            </div>
                                        </div>
                                        <div class="question">
                                            <div class="observe"><input type="checkbox" <?= (isset($boolArrayReacties[8]) && $boolArrayReacties[8] == '1') ? "checked" : "" ?> name="reactie9">
                                                <p>Houd mijn gevoelens voor me</p>
                                            </div>
                                        </div>
                                        <div class="question">
                                            <div class="observe"><input type="checkbox" <?= (isset($boolArrayReacties[9]) && $boolArrayReacties[9] == '1') ? "checked" : "" ?> name="reactie10">
                                                <p>Slapen/terugtrekken</p>
                                            </div>
                                        </div>
                                        <div class="question">
                                            <div class="observe"><input type="checkbox" <?= (isset($boolArrayReacties[10]) && $boolArrayReacties[10] == '1') ? "checked" : "" ?> name="reactie11">
                                                <p>Vertrouwen op religie</p>
                                            </div>
                                        </div>
                                        <div class="question">
                                            <div class="observe"><input type="checkbox" <?= (isset($boolArrayReacties[11]) && $boolArrayReacties[11] == '1') ? "checked" : "" ?> name="reactie12">
                                                <p>Zo goed mogelijk zelf oplossen</p>
                                            </div>
                                        </div>
                                        <div class="question">
                                            <div class="observe"><input type="checkbox" <?= (isset($boolArrayReacties[12]) && $boolArrayReacties[12] == '1') ? "checked" : "" ?> name="reactie13">
                                                <p>Anders, namelijk:</p>
                                            </div><textarea rows="1" cols="25" type="text" name="reactie_anders"><?= isset($antwoorden['reactie_anders']) ? $antwoorden['reactie_anders'] : '' ?></textarea>
                                        </div>
                                    </div>
                                </div>
                                <div class="question">
                                    <p>Probeert u spanningsvolle situaties zo goed mogelijk te voorkomen?</p>
                                    <div class="checkboxes">
                                        <div class="question-answer">
                                            <input id="radio" type="radio" value="1" name="spanningsvolle_situaties_voorkomen" <?= (isset($antwoorden['spanningsvolle_situaties_voorkomen']) && $antwoorden['spanningsvolle_situaties_voorkomen'] == '1') ? "checked" : "" ?>>
                                            <label>Ja</label>
                                            <textarea rows="1" cols="25" id="checkfield" type="text" placeholder="en wel?" name="spanningsvolle_situaties_voorkomen_hoe"><?= isset($antwoorden['spanningsvolle_situaties_voorkomen_hoe']) ? $antwoorden['spanningsvolle_situaties_voorkomen_hoe'] : '' ?></textarea>
                                        </div>
                                        <p>
                                            <input type="radio" value="0" name="spanningsvolle_situaties_voorkomen" <?= (!isset($antwoorden['spanningsvolle_situaties_voorkomen']) || $antwoorden['spanningsvolle_situaties_voorkomen'] == '0') ? "checked" : "" ?>>
                                            <label>Nee</label>
                                        </p>
                                    </div>
                                </div>
                                <div class="question">
                                    <p>- Probeert u spanningsvolle situaties zo goed mogelijk op te lossen?</p>
                                    <div class="checkboxes">
                                        <div class="question-answer">
                                            <input id="radio" type="radio" value="1" name="spanningsvolle_situaties_oplossen" <?= (isset($antwoorden['spanningsvolle_situaties_oplossen']) && $antwoorden['spanningsvolle_situaties_oplossen'] == '1') ? "checked" : "" ?>>
                                            <label>Ja</label>
                                            <textarea rows="1" cols="25" id="checkfield" type="text" placeholder="en wel?" name="spanningsvolle_situaties_oplossen_hoe"><?= isset($antwoorden['spanningsvolle_situaties_oplossen_hoe']) ? $antwoorden['spanningsvolle_situaties_oplossen_hoe'] : '' ?></textarea>
                                        </div>
                                        <p>
                                            <input type="radio" value="0" name="spanningsvolle_situaties_oplossen" <?= (!isset($antwoorden['spanningsvolle_situaties_oplossen']) || $antwoorden['spanningsvolle_situaties_oplossen'] == '0') ? "checked" : "" ?>>                                            <label>Nee</label>
                                        </p>
                                    </div>
                                </div>
                                <div class="question">
                                    <p>Zijn er omstandigheden waarbij u in de war raakt?</p>
                                    <div class="checkboxes">
                                        <div class="question-answer">
                                            <input id="radio" type="radio" value="1" name="omstandigheden_in_war_raken" <?= (isset($antwoorden['omstandigheden_in_war_raken']) && $antwoorden['omstandigheden_in_war_raken'] == '1') ? "checked" : "" ?>>
                                            <label>Ja</label>
                                            <textarea rows="1" cols="25" id="checkfield" type="text" placeholder="welke?" name="omstandigheden_in_war_raken_welke"><?= isset($antwoorden['omstandigheden_in_war_raken_welke']) ? $antwoorden['omstandigheden_in_war_raken_welke'] : '' ?></textarea>
                                        </div>
                                        <p>
                                            <input type="radio" value="0" name="omstandigheden_in_war_raken" <?= (!isset($antwoorden['omstandigheden_in_war_raken']) || $antwoorden['omstandigheden_in_war_raken'] == '0') ? "checked" : "" ?>>
                                            <label>Nee</label>
                                        </p>
                                    </div>
                                </div>
                                <div class="question">
                                    <p>- Bent u wel eens angstig of in paniek?</p>
                                    <div class="checkboxes">
                                        <div class="question-answer">
                                            <input id="radio" type="radio" value="1" name="angstig_paniek" <?= (isset($antwoorden['angstig_paniek']) && $antwoorden['angstig_paniek'] == '1') ? "checked" : "" ?>>
                                            <label>Ja</label>
                                            <textarea rows="1" cols="25" id="checkfield" type="text" placeholder="wat doet u dan?" name="angstig_paniek_actie"><?= isset($antwoorden['angstig_paniek_actie']) ? $antwoorden['angstig_paniek_actie'] : '' ?></textarea>
                                        </div>
                                        <p>
                                            <input type="radio" value="0" name="angstig_paniek" <?= (!isset($antwoorden['angstig_paniek']) || $antwoorden['angstig_paniek'] == '0') ? "checked" : "" ?>>
                                            <label>Nee</label>
                                        </p>
                                    </div>
                                </div>
                                <div class="question">
                                    <p>- Weet u een dergelijke situatie te vookomen?</p>
                                    <div class="checkboxes">
                                        <p>
                                            <input type="radio" value="1" name="angstig_paniek_lukt_voorkomen" <?= (isset($antwoorden['angstig_paniek_lukt_voorkomen']) && $antwoorden['angstig_paniek_lukt_voorkomen'] == '1') ? "checked" : "" ?>>
                                            <label>Ja</label>
                                        </p>
                                        <p>
                                            <input type="radio" value="0" name="angstig_paniek_lukt_voorkomen" <?= (!isset($antwoorden['angstig_paniek_lukt_voorkomen']) || $antwoorden['angstig_paniek_lukt_voorkomen'] == '0') ? "checked" : "" ?>>
                                            <label>Nee</label>
                                        </p>
                                    </div>
                                </div>
                                <div class="question">
                                    <p>Zijn er wel eens momenten dat u niet verder wilt leven?</p>
                                    <div class="checkboxes">
                                        <p>
                                            <input type="radio" value="1" name="suicidaal" <?= (isset($antwoorden['suicidaal']) && $antwoorden['suicidaal'] == '1') ? "checked" : "" ?>>
                                            <label>Ja</label>
                                        </p>
                                        <p>
                                            <input type="radio" value="0" name="suicidaal" <?= (!isset($antwoorden['suicidaal']) || $antwoorden['suicidaal'] == '0') ? "checked" : "" ?>>
                                            <label>Nee</label>
                                        </p>
                                    </div>
                                </div>
                                <div class="question">
                                    <p>- Zo ja, ook op dit moment?</p>
                                    <div class="checkboxes">
                                        <p>
                                            <input type="radio" value="1" name="suicidaal_momenteel" <?= (isset($antwoorden['suicidaal_momenteel']) && $antwoorden['suicidaal_momenteel'] == '1') ? "checked" : "" ?>>
                                            <label>Ja</label>
                                        </p>
                                        <p>
                                            <input type="radio" value="0" name="suicidaal_momenteel" <?= (!isset($antwoorden['suicidaal_momenteel']) || $antwoorden['suicidaal_momenteel'] == '0') ? "checked" : "" ?>>
                                            <label>Nee</label>
                                        </p>
                                    </div>
                                </div>
                                <div class="question">
                                    <p>Bent u wel eens agressief?</p>
                                    <div class="checkboxes">
                                        <p>
                                            <input type="radio" value="1" name="agressief" <?= (isset($antwoorden['agressief']) && $antwoorden['agressief'] == '1') ? "checked" : "" ?>>
                                            <label>Ja</label>
                                        </p>
                                        <p>
                                            <input type="radio" value="0" name="agressief" <?= (!isset($antwoorden['agressief']) || $antwoorden['agressief'] == '0') ? "checked" : "" ?>>
                                            <label>Nee</label>
                                        </p>
                                    </div>
                                </div>
                                <div class="question">
                                    <p>- Voelt u een dreiging om u zelf of anderen iets aan te doen?</p>
                                    <div class="checkboxes">
                                        <p>
                                            <input type="radio" value="1" name="anderen_iets_aan_willen_doen" <?= (isset($antwoorden['anderen_iets_aan_willen_doen']) && $antwoorden['anderen_iets_aan_willen_doen'] == '1') ? "checked" : "" ?>>
                                            <label>Ja</label>
                                        </p>
                                        <p>
                                            <input type="radio" value="0" name="anderen_iets_aan_willen_doen" <?= (!isset($antwoorden['anderen_iets_aan_willen_doen']) || $antwoorden['anderen_iets_aan_willen_doen'] == '0') ? "checked" : "" ?>>
                                            <label>Nee</label>
                                        </p>
                                    </div>
                                </div>
                                <div class="question">
                                    <p>- Neemt u maatregelen om de veiligheid van u zelf en anderen te waarborgen?</p>
                                    <div class="checkboxes">
                                        <div class="question-answer">
                                            <input id="radio" type="radio" value="1" name="maatregelen_veiligheid" <?= (isset($antwoorden['maatregelen_veiligheid']) && $antwoorden['maatregelen_veiligheid'] == '1') ? "checked" : "" ?>>
                                            <label>Ja</label>
                                            <textarea rows="1" cols="25" id="checkfield" type="text" placeholder="door?" name="maatregelen_veiligheid_door"><?= isset($antwoorden['maatregelen_veiligheid_door']) ? $antwoorden['maatregelen_veiligheid_door'] : '' ?></textarea>
                                        </div>
                                        <p>
                                            <input type="radio" value="0" name="maatregelen_veiligheid" <?= (!isset($antwoorden['maatregelen_veiligheid']) || $antwoorden['maatregelen_veiligheid'] == '0') ? "checked" : "" ?>>
                                            <label>Nee</label>
                                        </p>
                                    </div>
                                </div>
                                <div class="question">
                                    <p>Heeft u moeite met het uiten van gevoelens c.q. problemen?</p>
                                    <div class="checkboxes">
                                        <p>
                                            <input type="radio" value="1" name="moeite_uiten_gevoelens" <?= (isset($antwoorden['moeite_uiten_gevoelens']) && $antwoorden['moeite_uiten_gevoelens'] == '1') ? "checked" : "" ?>>
                                            <label>Ja</label>
                                        </p>
                                        <p>
                                            <input type="radio" value="0" name="moeite_uiten_gevoelens" <?= (!isset($antwoorden['moeite_uiten_gevoelens']) || $antwoorden['moeite_uiten_gevoelens'] == '0') ? "checked" : "" ?>>
                                            <label>Nee</label>
                                        </p>
                                    </div>
                                </div>
                                <div class="question">
                                    <p>- Met wie bespreekt u uw gevoelens c.q. problemen?</p><textarea rows="1" cols="25" type="text" name="bespreken_gevoelens_met"><?= isset($antwoorden['bespreken_gevoelens_met']) ? $antwoorden['bespreken_gevoelens_met'] : '' ?></textarea>
                                </div>

                                <div class="observation">
                                    <h2>Verpleegkundige observatie bij dit patroon</h2>
                                    <div class="question">
                                        <div class="observe"><input type="checkbox" <?= (isset($boolArrayObservatie[0]) && $boolArrayObservatie[0] == '1') ? "checked" : "" ?> name="observatie1">
                                            <p>Defensieve coping</p>
                                        </div>
                                    </div>
                                    <div class="question">
                                        <div class="observe"><input type="checkbox" <?= (isset($boolArrayObservatie[1]) && $boolArrayObservatie[1] == '1') ? "checked" : "" ?> name="observatie2">
                                            <p>Probleemvermijding</p>
                                        </div>
                                    </div>
                                    <div class="question">
                                        <div class="observe"><input type="checkbox" <?= (isset($boolArrayObservatie[2]) && $boolArrayObservatie[2] == '1') ? "checked" : "" ?> name="observatie3">
                                            <p>Ineffectieve coping</p>
                                        </div>
                                    </div>
                                    <div class="question">
                                        <div class="observe"><input type="checkbox" <?= (isset($boolArrayObservatie[3]) && $boolArrayObservatie[3] == '1') ? "checked" : "" ?> name="observatie4">
                                            <p>Ineffectieve ontkenning</p>
                                        </div>
                                    </div>
                                    <div class="question">
                                        <div class="observe"><input type="checkbox" <?= (isset($boolArrayObservatie[4]) && $boolArrayObservatie[4] == '1') ? "checked" : "" ?> name="observatie5">
                                            <p>Posttraumatische reactie</p>
                                        </div>
                                    </div>
                                    <div class="question">
                                        <div class="observe"><input type="checkbox" <?= (isset($boolArrayObservatie[5]) && $boolArrayObservatie[5] == '1') ? "checked" : "" ?> name="observatie6">
                                            <p>Verminderd aanpassingsvermogen</p>
                                        </div>
                                    </div>
                                    <div class="question">
                                        <div class="observe"><input type="checkbox" <?= (isset($boolArrayObservatie[6]) && $boolArrayObservatie[6] == '1') ? "checked" : "" ?> name="observatie7">
                                            <p>Gezinscoping: ontplooiingsmogelijkheden</p>
                                        </div>
                                    </div>
                                    <div class="question">
                                        <div class="observe"><input type="checkbox" <?= (isset($boolArrayObservatie[7]) && $boolArrayObservatie[7] == '1') ? "checked" : "" ?> name="observatie8">
                                            <p>Bedreigde gezinscoping</p>
                                        </div>
                                    </div>
                                    <div class="question">
                                        <div class="observe"><input type="checkbox" <?= (isset($boolArrayObservatie[8]) && $boolArrayObservatie[8] == '1') ? "checked" : "" ?> name="observatie9">
                                            <p>Gebrekkige gezinscoping</p>
                                        </div>
                                    </div>
                                    <div class="question">
                                        <div class="observe"><input type="checkbox" <?= (isset($boolArrayObservatie[9]) && $boolArrayObservatie[9] == '1') ? "checked" : "" ?> name="observatie10">
                                            <p>Dreiging van suïcidaliteit</p>
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