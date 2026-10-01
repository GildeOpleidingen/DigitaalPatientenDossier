<?php
include_once '../../models/autoload.php';
Auth::requireLogin();

$Main = new Main();

$clientId = $_SESSION['clientId'];
$antwoorden = $Main->getAnswers($clientId, 6);

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['navbutton'])) {

    PatroonModel::saveAnswers(
        (int)$_SESSION['clientId'],
        (int)$_SESSION['loggedin_id'],
        6,
        $_POST
    );

    // Navigation
    switch ($_POST['navbutton']) {
        case 'next':
            header("Location: patroon07.php");
            exit;
        case 'prev':
            header("Location: patroon05.php");
            exit;
    }
}

function e($v)
{
    return htmlspecialchars($v ?? "", ENT_QUOTES, "UTF-8");
}
?>
<!DOCTYPE html>
<html lang="nl">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Anamnese — Cognitie- en waarnemingspatroon</title>
    <link rel="stylesheet" href="../../assets/css/client/patronen.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css">
</head>

<body style="overflow: hidden;">
    <form method="POST" data-client-id="<?= htmlspecialchars((string)($_SESSION['clientId'] ?? '')) ?>">
        <div class="main">
            <?php
            include '../../includes/n-header.php';
            include '../../includes/n-sidebar.php';
            ?>
            <div class="mt-5 pt-5 content">
                <div class="mt-4 mb-3 bg-white p-3" style="height: 90%; overflow: auto;">
                    <div class="form-content">
                        <div class="h4 text-primary">6. Cognitie- en waarnemingspatroon</div>
                        <div class="form">
                            <div class="questionnaire">
                                <div class="question">
                                    <p>Heeft u moeite met horen?</p>
                                    <div class="checkboxes">
                                        <p>
                                            <input type="radio" value="1" name="moeilijk_horen"
                                                <?= isset($antwoorden['moeilijk_horen']) && $antwoorden['moeilijk_horen'] == 1 ? "checked" : "" ?>>
                                            <label>Ja</label>
                                        </p>
                                        <p>
                                            <input type="radio" value="0" name="moeilijk_horen"
                                                <?= isset($antwoorden['moeilijk_horen']) && $antwoorden['moeilijk_horen'] == 0 ? "checked" : "" ?>>
                                            <label>Nee</label>
                                        </p>
                                    </div>
                                </div>

                                <div class="question">
                                    <p>- Hoort u stemmen die op dat moment door personen in uw omgeving niet gehoord
                                        (kunnen) worden?</p>
                                    <div class="checkboxes">
                                        <div class="question-answer">
                                            <input class="radio" type="radio" value="1" name="hoort_stemmen"
                                                <?= isset($antwoorden['hoort_stemmen']) && $antwoorden['hoort_stemmen'] == 1 ? "checked" : "" ?>>
                                            <label>Ja</label>
                                            <textarea rows="1" cols="25" class="checkfield" name="hoort_stemmen_wat"
                                                placeholder="wat hoort u?"><?= e($antwoorden['hoort_stemmen_wat'] ?? "") ?></textarea>
                                        </div>
                                        <p>
                                            <input type="radio" value="0" name="hoort_stemmen"
                                                <?= isset($antwoorden['hoort_stemmen']) && $antwoorden['hoort_stemmen'] == 0 ? "checked" : "" ?>>
                                            <label>Nee</label>
                                        </p>
                                    </div>
                                </div>

                                <div class="question">
                                    <p>Heeft u moeite met zien?</p>
                                    <div class="checkboxes">
                                        <p>
                                            <input type="radio" value="1" name="moeite_met_zien"
                                                <?= isset($antwoorden['moeite_met_zien']) && $antwoorden['moeite_met_zien'] == 1 ? "checked" : "" ?>>
                                            <label>Ja</label>
                                        </p>
                                        <p>
                                            <input type="radio" value="0" name="moeite_met_zien"
                                                <?= isset($antwoorden['moeite_met_zien']) && $antwoorden['moeite_met_zien'] == 0 ? "checked" : "" ?>>
                                            <label>Nee</label>
                                        </p>
                                    </div>
                                </div>

                                <div class="question">
                                    <p>- Ziet u personen, dieren, objecten die op dat moment door personen in uw
                                        omgeving niet gezien (kunnen) worden?</p>
                                    <div class="checkboxes">
                                        <div class="question-answer">
                                            <input class="radio" type="radio" value="1" name="ziet_dingen"
                                                <?= isset($antwoorden['ziet_dingen']) && $antwoorden['ziet_dingen'] == 1 ? "checked" : "" ?>>
                                            <label>Ja</label>
                                            <textarea rows="1" cols="25" class="checkfield" name="ziet_dingen_wat"
                                                placeholder="wat ziet u?"><?= e($antwoorden['ziet_dingen_wat'] ?? "") ?></textarea>
                                        </div>
                                        <p>
                                            <input type="radio" value="0" name="ziet_dingen"
                                                <?= isset($antwoorden['ziet_dingen']) && $antwoorden['ziet_dingen'] == 0 ? "checked" : "" ?>>
                                            <label>Nee</label>
                                        </p>
                                    </div>
                                </div>

                                <div class="question">
                                    <p>Ruikt u iets dat op dat moment door personen in uw omgeving niet geroken (kan)
                                        worden?</p>
                                    <div class="checkboxes">
                                        <div class="question-answer">
                                            <input class="radio" type="radio" value="1" name="ruikt_iets_onverklaarbaar"
                                                <?= isset($antwoorden['ruikt_iets_onverklaarbaar']) && $antwoorden['ruikt_iets_onverklaarbaar'] == 1 ? "checked" : "" ?>>
                                            <label>Ja</label>
                                            <textarea rows="1" cols="25" class="checkfield"
                                                name="ruikt_iets_onverklaarbaar_wat"
                                                placeholder="wat ruikt u?"><?= e($antwoorden['ruikt_iets_onverklaarbaar_wat'] ?? "") ?></textarea>
                                        </div>
                                        <p>
                                            <input type="radio" value="0" name="ruikt_iets_onverklaarbaar"
                                                <?= isset($antwoorden['ruikt_iets_onverklaarbaar']) && $antwoorden['ruikt_iets_onverklaarbaar'] == 0 ? "checked" : "" ?>>
                                            <label>Nee</label>
                                        </p>
                                    </div>
                                </div>

                                <div class="question">
                                    <p>Zijn er de afgelopen tijd veranderingen opgetreden in uw denken?</p>
                                    <div class="checkboxes">
                                        <p>
                                            <input type="radio" value="1" name="verandering_denken"
                                                <?= isset($antwoorden['verandering_denken']) && $antwoorden['verandering_denken'] == 1 ? "checked" : "" ?>>
                                            <label>Ja</label>
                                        </p>
                                        <p>
                                            <input type="radio" value="0" name="verandering_denken"
                                                <?= isset($antwoorden['verandering_denken']) && $antwoorden['verandering_denken'] == 0 ? "checked" : "" ?>>
                                            <label>Nee</label>
                                        </p>
                                    </div>
                                </div>

                                <div class="question">
                                    <p>Heeft u moeite met spreken?</p>
                                    <div class="checkboxes">
                                        <p>
                                            <input type="radio" value="1" name="moeite_spreken"
                                                <?= isset($antwoorden['moeite_spreken']) && $antwoorden['moeite_spreken'] == 1 ? "checked" : "" ?>>
                                            <label>Ja</label>
                                        </p>
                                        <p>
                                            <input type="radio" value="0" name="moeite_spreken"
                                                <?= isset($antwoorden['moeite_spreken']) && $antwoorden['moeite_spreken'] == 0 ? "checked" : "" ?>>
                                            <label>Nee</label>
                                        </p>
                                    </div>
                                </div>

                                <div class="question">
                                    <p>- Welke taal spreekt u thuis?</p>
                                    <textarea rows="1" cols="25"
                                        name="taal_thuis"><?= e($antwoorden['taal_thuis'] ?? "") ?></textarea>
                                </div>

                                <div class="question">
                                    <p>Zijn er de afgelopen tijd veranderingen opgetreden in uw concentratievermogen?
                                    </p>
                                    <div class="checkboxes">
                                        <p>
                                            <input type="radio" value="1" name="verandering_concentratievermogen"
                                                <?= isset($antwoorden['verandering_concentratievermogen']) && $antwoorden['verandering_concentratievermogen'] == 1 ? "checked" : "" ?>>
                                            <label>Ja</label>
                                        </p>
                                        <p>
                                            <input type="radio" value="0" name="verandering_concentratievermogen"
                                                <?= isset($antwoorden['verandering_concentratievermogen']) && $antwoorden['verandering_concentratievermogen'] == 0 ? "checked" : "" ?>>
                                            <label>Nee</label>
                                        </p>
                                    </div>
                                </div>

                                <div class="question">
                                    <p>Kunt u moeilijker dagelijkse beslissingen nemen?</p>
                                    <div class="checkboxes">
                                        <p>
                                            <input type="radio" value="1" name="moeilijker_beslissen"
                                                <?= isset($antwoorden['moeilijker_beslissen']) && $antwoorden['moeilijker_beslissen'] == 1 ? "checked" : "" ?>>
                                            <label>Ja</label>
                                        </p>
                                        <p>
                                            <input type="radio" value="0" name="moeilijker_beslissen"
                                                <?= isset($antwoorden['moeilijker_beslissen']) && $antwoorden['moeilijker_beslissen'] == 0 ? "checked" : "" ?>>
                                            <label>Nee</label>
                                        </p>
                                    </div>
                                </div>

                                <div class="question">
                                    <p>Zijn er de afgelopen tijd veranderingen opgetreden in uw geheugen?</p>
                                    <div class="checkboxes">
                                        <p>
                                            <input type="radio" value="1" name="verandering_geheugen"
                                                <?= isset($antwoorden['verandering_geheugen']) && $antwoorden['verandering_geheugen'] == 1 ? "checked" : "" ?>>
                                            <label>Ja</label>
                                        </p>
                                        <p>
                                            <input type="radio" value="0" name="verandering_geheugen"
                                                <?= isset($antwoorden['verandering_geheugen']) && $antwoorden['verandering_geheugen'] == 0 ? "checked" : "" ?>>
                                            <label>Nee</label>
                                        </p>
                                    </div>
                                </div>

                                <div class="question">
                                    <p>Zijn er de afgelopen tijd veranderingen opgetreden in uw oriëntatie?</p>
                                    <div class="checkboxes">
                                        <p>
                                            <input type="radio" value="1" name="verandering_orientatie"
                                                <?= isset($antwoorden['verandering_orientatie']) && $antwoorden['verandering_orientatie'] == 1 ? "checked" : "" ?>>
                                            <label>Ja</label>
                                        </p>
                                        <p>
                                            <input type="radio" value="0" name="verandering_orientatie"
                                                <?= isset($antwoorden['verandering_orientatie']) && $antwoorden['verandering_orientatie'] == 0 ? "checked" : "" ?>>
                                            <label>Nee</label>
                                        </p>
                                    </div>
                                </div>

                                <div class="question">
                                    <p>Gebruikt u medicatie die uw oriëntatie, reactievermogen of denken beïnvloeden?
                                    </p>
                                    <div class="checkboxes">
                                        <div class="question-answer">
                                            <input class="radio" type="radio" value="1" name="invloed_medicatie"
                                                <?= isset($antwoorden['invloed_medicatie']) && $antwoorden['invloed_medicatie'] == 1 ? "checked" : "" ?>>
                                            <label>Ja</label>
                                            <textarea rows="1" cols="25" class="checkfield" name="invloed_medicatie_welke"
                                                placeholder="welke?"><?= e($antwoorden['invloed_medicatie_welke'] ?? "") ?></textarea>
                                        </div>
                                        <p>
                                            <input type="radio" value="0" name="invloed_medicatie"
                                                <?= isset($antwoorden['invloed_medicatie']) && $antwoorden['invloed_medicatie'] == 0 ? "checked" : "" ?>>
                                            <label>Nee</label>
                                        </p>
                                    </div>
                                </div>

                                <div class="question">
                                    <p>Gebruikt u verdovende/stimulerende middelen?</p>
                                    <div class="checkboxes">
                                        <div class="question-answer">
                                            <input class="radio" type="radio" value="1" name="gebruikt_middelen"
                                                <?= isset($antwoorden['gebruikt_middelen']) && $antwoorden['gebruikt_middelen'] == 1 ? "checked" : "" ?>>
                                            <label>Ja</label>
                                            <div class="checkfield">
                                                <div class="question">
                                                    <div class="observe">
                                                        <input type="checkbox" value="1"
                                                            name="gebruikt_middelen_softdrugs"
                                                            <?= isset($antwoorden['gebruikt_middelen_softdrugs']) && $antwoorden['gebruikt_middelen_softdrugs'] == 1 ? "checked" : "" ?>>
                                                        <p>Softdrugs</p>
                                                    </div>
                                                    <textarea rows="1" cols="25"
                                                        name="gebruikt_middelen_softdrugs_welke"><?= e($antwoorden['gebruikt_middelen_softdrugs_welke'] ?? "") ?></textarea>
                                                </div>
                                                <div class="question">
                                                    <div class="observe">
                                                        <input type="checkbox" value="1"
                                                            name="gebruikt_middelen_harddrugs"
                                                            <?= isset($antwoorden['gebruikt_middelen_harddrugs']) && $antwoorden['gebruikt_middelen_harddrugs'] == 1 ? "checked" : "" ?>>
                                                        <p>Harddrugs</p>
                                                    </div>
                                                    <textarea rows="1" cols="25"
                                                        name="gebruikt_middelen_harddrugs_welke"><?= e($antwoorden['gebruikt_middelen_harddrugs_welke'] ?? "") ?></textarea>
                                                </div>
                                                <div class="question">
                                                    <div class="observe">
                                                        <input type="checkbox" value="1"
                                                            name="gebruikt_middelen_alcohol"
                                                            <?= isset($antwoorden['gebruikt_middelen_alcohol']) && $antwoorden['gebruikt_middelen_alcohol'] == 1 ? "checked" : "" ?>>
                                                        <p>Alcohol</p>
                                                    </div>
                                                    <textarea rows="1" cols="25"
                                                        name="gebruikt_middelen_alcohol_welke"><?= e($antwoorden['gebruikt_middelen_alcohol_welke'] ?? "") ?></textarea>
                                                </div>
                                                <div class="question">
                                                    <div class="observe">
                                                        <input type="checkbox" value="1" name="gebruikt_middelen_anders"
                                                            <?= isset($antwoorden['gebruikt_middelen_anders']) && $antwoorden['gebruikt_middelen_anders'] == 1 ? "checked" : "" ?>>
                                                        <p>Anders, namelijk:</p>
                                                    </div>
                                                    <textarea rows="1" cols="25"
                                                        name="gebruikt_middelen_anders_welke"><?= e($antwoorden['gebruikt_middelen_anders_welke'] ?? "") ?></textarea>
                                                </div>
                                            </div>
                                        </div>
                                        <p>
                                            <input type="radio" value="0" name="gebruikt_middelen"
                                                <?= isset($antwoorden['gebruikt_middelen']) && $antwoorden['gebruikt_middelen'] == 0 ? "checked" : "" ?>>
                                            <label>Nee</label>
                                        </p>
                                    </div>
                                </div>

                                <div class="question">
                                    <p>Heeft u pijnklachten?</p>
                                    <div class="checkboxes">
                                        <p>
                                            <input type="radio" value="1" name="pijnklachten"
                                                <?= isset($antwoorden['pijnklachten']) && $antwoorden['pijnklachten'] == 1 ? "checked" : "" ?>>
                                            <label>Ja</label>
                                        </p>
                                        <p>
                                            <input type="radio" value="0" name="pijnklachten"
                                                <?= isset($antwoorden['pijnklachten']) && $antwoorden['pijnklachten'] == 0 ? "checked" : "" ?>>
                                            <label>Nee</label>
                                        </p>
                                    </div>
                                </div>

                                <div class="question">
                                    <p>- Waar, wanneer, soort pijn?</p>
                                    <textarea rows="1" cols="25"
                                        name="pijnklachten_waar_wanneer_soort"><?= e($antwoorden['pijnklachten_waar_wanneer_soort'] ?? "") ?></textarea>
                                </div>

                                <div class="question">
                                    <p>- Wat doet u doorgaans tegen de pijn?</p>
                                    <textarea rows="1" cols="25"
                                        name="pijnklachten_tegengaan_pijn"><?= e($antwoorden['pijnklachten_tegengaan_pijn'] ?? "") ?></textarea>
                                </div>

                                <div class="question">
                                    <p>- Wat doet u om pijn/ongemak zoveel mogelijk te voorkomen?</p>
                                    <textarea rows="1" cols="25"
                                        name="pijnklachten_preventie"><?= e($antwoorden['pijnklachten_preventie'] ?? "") ?></textarea>
                                </div>

                                <div class="observation">
                                    <h2>Verpleegkundige observatie bij dit patroon</h2>
                                    <div class="question">
                                        <div class="observe"><input type="checkbox" value="1" name="observatie1"
                                                <?= PatroonModel::isChecked($antwoorden['observatie'], 0) ?>>
                                            <p>Wijziging in de waarneming</p>
                                        </div>
                                    </div>
                                    <div class="question">
                                        <div class="observe"><input type="checkbox" value="1" name="observatie2"
                                                <?= PatroonModel::isChecked($antwoorden['observatie'], 1) ?>>
                                            <p>Verstoord denken</p>
                                        </div>
                                    </div>
                                    <div class="question">
                                        <div class="observe"><input type="checkbox" value="1" name="observatie3"
                                                <?= PatroonModel::isChecked($antwoorden['observatie'], 2) ?>>
                                            <p>Kennistekort</p>
                                        </div>
                                    </div>
                                    <div class="question">
                                        <div class="observe"><input type="checkbox" value="1" name="observatie4"
                                                <?= PatroonModel::isChecked($antwoorden['observatie'], 3) ?>>
                                            <p>Dreigend cognitietekort</p>
                                        </div>
                                    </div>
                                    <div class="question">
                                        <div class="observe"><input type="checkbox" value="1" name="observatie5"
                                                <?= PatroonModel::isChecked($antwoorden['observatie'], 4) ?>>
                                            <p>Beslisconflict</p>
                                        </div>
                                    </div>
                                    <div class="question">
                                        <div class="observe"><input type="checkbox" value="1" name="observatie6"
                                                <?= PatroonModel::isChecked($antwoorden['observatie'], 5) ?>>
                                            <p>Achterdocht</p>
                                        </div>
                                    </div>
                                    <div class="question">
                                        <div class="observe"><input type="checkbox" value="1" name="observatie7"
                                                <?= PatroonModel::isChecked($antwoorden['observatie'], 6) ?>>
                                            <p>Acute verwardheid</p>
                                        </div>
                                    </div>
                                    <div class="question">
                                        <div class="observe"><input type="checkbox" value="1" name="observatie8"
                                                <?= PatroonModel::isChecked($antwoorden['observatie'], 7) ?>>
                                            <p>Pijn (specificeer type en locatie)</p>
                                        </div>
                                    </div>
                                    <div class="question">
                                        <div class="observe"><input type="checkbox" value="1" name="observatie9"
                                                <?= PatroonModel::isChecked($antwoorden['observatie'], 8) ?>>
                                            <p>Chronische pijn (specificeer type en locatie)</p>
                                        </div>
                                    </div>
                                    <div class="question">
                                        <div class="observe"><input type="checkbox" value="1" name="observatie10"
                                                <?= PatroonModel::isChecked($antwoorden['observatie'], 9) ?>>
                                            <p>Middelenmisbruik:</p>
                                        </div>
                                    </div>
                                    <div class="question">
                                        <div class="observe"><input type="checkbox" value="1" name="observatie11"
                                                <?= PatroonModel::isChecked($antwoorden['observatie'], 10) ?>>
                                            <p>Alcohol</p>
                                        </div>
                                    </div>
                                    <div class="question">
                                        <div class="observe"><input type="checkbox" value="1" name="observatie12"
                                                <?= PatroonModel::isChecked($antwoorden['observatie'], 11) ?>>
                                            <p>Drugs</p>
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
        </div>
    </form>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="../../assets/js/form-autosave.js"></script>
</body>

</html>
