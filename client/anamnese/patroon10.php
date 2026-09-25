<?php
include_once '../../models/autoload.php';
Auth::requireLogin();

$Main = new Main();

$clientId = $_SESSION['clientId'];
$antwoorden = $Main->getAnswers($clientId, 10);

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['navbutton'])) {

    PatroonModel::saveAnswers(
        (int)$_SESSION['clientId'],
        (int)$_SESSION['loggedin_id'],
        10,
        $_POST
    );

    // Navigation
    switch ($_POST['navbutton']) {
        case 'next':
            header("Location: patroon11.php");
            exit;
        case 'prev':
            header("Location: patroon09.php");
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
    <form action="" method="post" data-client-id="<?= htmlspecialchars((string)($_SESSION['clientId'] ?? '')) ?>">
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
                                            <div class="observe"><input type="checkbox" <?= PatroonModel::isChecked($antwoorden['reactie_spanningen'], 0) ?> name="reactie1">
                                                <p>Zoveel mogelijk vermijden</p>
                                            </div>
                                        </div>
                                        <div class="question">
                                            <div class="observe"><input type="checkbox" <?= PatroonModel::isChecked($antwoorden['reactie_spanningen'], 1) ?> name="reactie2">
                                                <p>Drugs gebruiken</p>
                                            </div>
                                        </div>
                                        <div class="question">
                                            <div class="observe"><input type="checkbox" <?= PatroonModel::isChecked($antwoorden['reactie_spanningen'], 2) ?> name="reactie3">
                                                <p>Ontwikkeling van lichamelijke symptomen</p>
                                            </div>
                                        </div>
                                        <div class="question">
                                            <div class="observe"><input type="checkbox" <?= PatroonModel::isChecked($antwoorden['reactie_spanningen'], 3) ?> name="reactie4">
                                                <p>Medicatie</p>
                                            </div>
                                        </div>
                                        <div class="question">
                                            <div class="observe"><input type="checkbox" <?= PatroonModel::isChecked($antwoorden['reactie_spanningen'], 4) ?> name="reactie5">
                                                <p>Meer/minder eten</p>
                                            </div>
                                        </div>
                                        <div class="question">
                                            <div class="observe"><input type="checkbox" <?= PatroonModel::isChecked($antwoorden['reactie_spanningen'], 5) ?> name="reactie6">
                                                <p>Agressie</p>
                                            </div>
                                        </div>
                                        <div class="question">
                                            <div class="observe"><input type="checkbox" <?= PatroonModel::isChecked($antwoorden['reactie_spanningen'], 6) ?> name="reactie7">
                                                <p>Praten met anderen</p>
                                            </div>
                                        </div>
                                        <div class="question">
                                            <div class="observe"><input type="checkbox" <?= PatroonModel::isChecked($antwoorden['reactie_spanningen'], 7) ?> name="reactie8">
                                                <p>Alcohol drinken</p>
                                            </div>
                                        </div>
                                        <div class="question">
                                            <div class="observe"><input type="checkbox" <?= PatroonModel::isChecked($antwoorden['reactie_spanningen'], 8) ?> name="reactie9">
                                                <p>Houd mijn gevoelens voor me</p>
                                            </div>
                                        </div>
                                        <div class="question">
                                            <div class="observe"><input type="checkbox" <?= PatroonModel::isChecked($antwoorden['reactie_spanningen'], 9) ?> name="reactie10">
                                                <p>Slapen/terugtrekken</p>
                                            </div>
                                        </div>
                                        <div class="question">
                                            <div class="observe"><input type="checkbox" <?= PatroonModel::isChecked($antwoorden['reactie_spanningen'], 10) ?> name="reactie11">
                                                <p>Vertrouwen op religie</p>
                                            </div>
                                        </div>
                                        <div class="question">
                                            <div class="observe"><input type="checkbox" <?= PatroonModel::isChecked($antwoorden['reactie_spanningen'], 11) ?> name="reactie12">
                                                <p>Zo goed mogelijk zelf oplossen</p>
                                            </div>
                                        </div>
                                        <div class="question">
                                            <div class="observe"><input type="checkbox" <?= PatroonModel::isChecked($antwoorden['reactie_spanningen'], 12) ?> name="reactie13">
                                                <p>Anders, namelijk:</p>
                                            </div><textarea rows="1" cols="25" type="text" name="reactie_anders"><?= isset($antwoorden['reactie_anders']) ? $antwoorden['reactie_anders'] : '' ?></textarea>
                                        </div>
                                    </div>
                                </div>
                                <div class="question">
                                    <p>Probeert u spanningsvolle situaties zo goed mogelijk te voorkomen?</p>
                                    <div class="checkboxes">
                                        <div class="question-answer">
                                            <input class="radio" type="radio" value="1" name="spanningsvolle_situaties_voorkomen" <?= (isset($antwoorden['spanningsvolle_situaties_voorkomen']) && $antwoorden['spanningsvolle_situaties_voorkomen'] == '1') ? "checked" : "" ?>>
                                            <label>Ja</label>
                                            <textarea rows="1" cols="25" class="checkfield" type="text" placeholder="en wel?" name="spanningsvolle_situaties_voorkomen_hoe"><?= isset($antwoorden['spanningsvolle_situaties_voorkomen_hoe']) ? $antwoorden['spanningsvolle_situaties_voorkomen_hoe'] : '' ?></textarea>
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
                                            <input class="radio" type="radio" value="1" name="spanningsvolle_situaties_oplossen" <?= (isset($antwoorden['spanningsvolle_situaties_oplossen']) && $antwoorden['spanningsvolle_situaties_oplossen'] == '1') ? "checked" : "" ?>>
                                            <label>Ja</label>
                                            <textarea rows="1" cols="25" class="checkfield" type="text" placeholder="en wel?" name="spanningsvolle_situaties_oplossen_hoe"><?= isset($antwoorden['spanningsvolle_situaties_oplossen_hoe']) ? $antwoorden['spanningsvolle_situaties_oplossen_hoe'] : '' ?></textarea>
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
                                            <input class="radio" type="radio" value="1" name="omstandigheden_in_war_raken" <?= (isset($antwoorden['omstandigheden_in_war_raken']) && $antwoorden['omstandigheden_in_war_raken'] == '1') ? "checked" : "" ?>>
                                            <label>Ja</label>
                                            <textarea rows="1" cols="25" class="checkfield" type="text" placeholder="welke?" name="omstandigheden_in_war_raken_welke"><?= isset($antwoorden['omstandigheden_in_war_raken_welke']) ? $antwoorden['omstandigheden_in_war_raken_welke'] : '' ?></textarea>
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
                                            <input class="radio" type="radio" value="1" name="angstig_paniek" <?= (isset($antwoorden['angstig_paniek']) && $antwoorden['angstig_paniek'] == '1') ? "checked" : "" ?>>
                                            <label>Ja</label>
                                            <textarea rows="1" cols="25" class="checkfield" type="text" placeholder="wat doet u dan?" name="angstig_paniek_actie"><?= isset($antwoorden['angstig_paniek_actie']) ? $antwoorden['angstig_paniek_actie'] : '' ?></textarea>
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
                                            <input class="radio" type="radio" value="1" name="maatregelen_veiligheid" <?= (isset($antwoorden['maatregelen_veiligheid']) && $antwoorden['maatregelen_veiligheid'] == '1') ? "checked" : "" ?>>
                                            <label>Ja</label>
                                            <textarea rows="1" cols="25" class="checkfield" type="text" placeholder="door?" name="maatregelen_veiligheid_door"><?= isset($antwoorden['maatregelen_veiligheid_door']) ? $antwoorden['maatregelen_veiligheid_door'] : '' ?></textarea>
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
                                        <div class="observe"><input type="checkbox" <?= PatroonModel::isChecked($antwoorden['observatie'], 0) ?> name="observatie1">
                                            <p>Defensieve coping</p>
                                        </div>
                                    </div>
                                    <div class="question">
                                        <div class="observe"><input type="checkbox" <?= PatroonModel::isChecked($antwoorden['observatie'], 1) ?> name="observatie2">
                                            <p>Probleemvermijding</p>
                                        </div>
                                    </div>
                                    <div class="question">
                                        <div class="observe"><input type="checkbox" <?= PatroonModel::isChecked($antwoorden['observatie'], 2) ?> name="observatie3">
                                            <p>Ineffectieve coping</p>
                                        </div>
                                    </div>
                                    <div class="question">
                                        <div class="observe"><input type="checkbox" <?= PatroonModel::isChecked($antwoorden['observatie'], 3) ?> name="observatie4">
                                            <p>Ineffectieve ontkenning</p>
                                        </div>
                                    </div>
                                    <div class="question">
                                        <div class="observe"><input type="checkbox" <?= PatroonModel::isChecked($antwoorden['observatie'], 4) ?> name="observatie5">
                                            <p>Posttraumatische reactie</p>
                                        </div>
                                    </div>
                                    <div class="question">
                                        <div class="observe"><input type="checkbox" <?= PatroonModel::isChecked($antwoorden['observatie'], 5) ?> name="observatie6">
                                            <p>Verminderd aanpassingsvermogen</p>
                                        </div>
                                    </div>
                                    <div class="question">
                                        <div class="observe"><input type="checkbox" <?= PatroonModel::isChecked($antwoorden['observatie'], 6) ?> name="observatie7">
                                            <p>Gezinscoping: ontplooiingsmogelijkheden</p>
                                        </div>
                                    </div>
                                    <div class="question">
                                        <div class="observe"><input type="checkbox" <?= PatroonModel::isChecked($antwoorden['observatie'], 7) ?> name="observatie8">
                                            <p>Bedreigde gezinscoping</p>
                                        </div>
                                    </div>
                                    <div class="question">
                                        <div class="observe"><input type="checkbox" <?= PatroonModel::isChecked($antwoorden['observatie'], 8) ?> name="observatie9">
                                            <p>Gebrekkige gezinscoping</p>
                                        </div>
                                    </div>
                                    <div class="question">
                                        <div class="observe"><input type="checkbox" <?= PatroonModel::isChecked($antwoorden['observatie'], 9) ?> name="observatie10">
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
    <script src="../../assets/js/form-autosave.js"></script> 
</body>

</html>
