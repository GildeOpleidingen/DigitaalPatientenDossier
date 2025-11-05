<?php
session_start();
include '../../database/DatabaseConnection.php';
include_once '../../models/autoload.php';
$Main = new Main();

$antwoorden = $Main->getPatternAnswers($_SESSION['clientId'], 6);
$boolArrayObservatie = isset($antwoorden['observatie']) && $antwoorden['observatie'] !== null ? str_split($antwoorden['observatie']) : [];

if (isset($_REQUEST['navbutton'])) {
    //TODO: hier actie om data op te slaan in database.
    switch ($_REQUEST['navbutton']) {
        case 'next': //action for next here
            header('Location: patroon07.php');
            break;

        case 'prev': //action for previous here
            header('Location: patroon05.php');
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
            include '../../includes/n-sidebar.php';
            ?>
            <div class="mt-5 pt-5 content">
                <div class="mt-4 mb-3 bg-white p-3" style="height: 90%; overflow: auto;">
                    <p class="card-text">
                    <div class="form-content">
                        <div class="h4 text-primary">6. Cognitie- en waarnemingspatroon</div>
                        <div class="form">
                            <div class="questionnaire">
                                <div class="question">
                                    <p>Heeft u moeite met horen?</p>
                                    <div class="checkboxes">
                                        <p>
                                            <input type="radio" name="moeilijk_horen"
                                                <?= isset($antwoorden['moeilijk_horen']) ? "checked" : "" ?>>
                                            <label>Ja</label>
                                        </p>
                                        <p>
                                            <input type="radio" name="moeilijk_horen"
                                                <?= !isset($antwoorden['moeilijk_horen']) ? "checked" : "" ?>>
                                            <label>Nee</label>
                                        </p>
                                    </div>
                                </div>
                                <div class="question">
                                    <p>- Hoort u stemmen die op dat moment door personen in uw omgeving niet gehoord
                                        (kunnen) worden?</p>
                                    <div class="checkboxes">
                                        <div class="question-answer">
                                            <input id="radio" type="radio" name="hoort_stemmen"
                                                <?= isset($antwoorden['hoort_stemmen']) ? "checked" : "" ?>>
                                            <label>Ja</label>
                                            <textarea rows="1" cols="25" id="checkfield" type="text"
                                                placeholder="wat hoort u?"
                                                name="hoort_stemmen_wat"> <?= isset($antwoorden['hoort_stemmen_wat']) ? $antwoorden['hoort_stemmen_wat'] : '' ?> </textarea>
                                        </div>
                                        <p>
                                            <input type="radio" name="hoort_stemmen"
                                                <?= !isset($antwoorden['hoort_stemmen']) ? "checked" : "" ?>>
                                            <label>Nee</label>
                                        </p>
                                    </div>
                                </div>
                                <div class="question">
                                    <p>Heeft u moeite met zien?</p>
                                    <div class="checkboxes">
                                        <p>
                                            <input type="radio" name="moeite_met_zien"
                                                <?= isset($antwoorden['moeite_met_zien']) ? "checked" : "" ?>>
                                            <label>Ja</label>
                                        </p>
                                        <p>
                                            <input type="radio" name="moeite_met_zien"
                                                <?= !isset($antwoorden['moeite_met_zien']) ? "checked" : "" ?>>
                                            <label>Nee</label>
                                        </p>
                                    </div>
                                </div>
                                <div class="question">
                                    <p>- Ziet u personen, dieren, objecten die op dat moment door personen in uw
                                        omgeving niet gezien (kunnen) worden?</p>
                                    <div class="checkboxes">
                                        <div class="question-answer">
                                            <input id="radio" type="radio" name="ziet_dingen"
                                                <?= isset($antwoorden['ziet_dingen']) ? "checked" : "" ?>>
                                            <label>Ja</label>
                                            <textarea rows="1" cols="25" id="checkfield" type="text"
                                                placeholder="wat ziet u?"
                                                name="ziet_dingen_wat"><?= isset($antwoorden['ziet_dingen_wat']) ? $antwoorden['ziet_dingen_wat'] : '' ?></textarea>
                                        </div>
                                        <p>
                                            <input type="radio" name="ziet_dingen" <?= !isset($antwoorden['ziet_dingen']) ? "checked" : "" ?>>
                                            <label>Nee</label>
                                        </p>
                                    </div>
                                </div>
                                <div class="question">
                                    <p>Ruikt u iets dat op dat moment door personen in uw omgeving niet geroken (kan)
                                        worden?</p>
                                    <div class="checkboxes">
                                        <div class="question-answer">
                                            <input id="radio" type="radio" name="ruikt_iets_onverklaarbaar"
                                                <?= isset($antwoorden['ruikt_iets_onverklaarbaar']) ? "checked" : "" ?>>
                                            <label>Ja</label>
                                            <textarea rows="1" cols="25" id="checkfield" type="text"
                                                placeholder="wat ruikt u?"
                                                name="ruikt_iets_onverklaarbaar_wat"><?= isset($antwoorden['ruikt_iets_onverklaarbaar_wat']) ? $antwoorden['ruikt_iets_onverklaarbaar_wat'] : '' ?></textarea>
                                        </div>
                                        <p>
                                            <input type="radio" name="ruikt_iets_onverklaarbaar"
                                                <?= !isset($antwoorden['ruikt_iets_onverklaarbaar']) ? "checked" : "" ?>>
                                            <label>Nee</label>
                                        </p>
                                    </div>
                                </div>
                                <div class="question">
                                    <p>Zijn er de afgelopen tijd veranderingen opgetreden in uw denken?</p>
                                    <div class="checkboxes">
                                        <p>
                                            <input type="radio" name="verandering_denken"
                                                <?= isset($antwoorden['verandering_denken']) ? "checked" : "" ?>>
                                            <label>Ja</label>
                                        </p>
                                        <p>
                                            <input type="radio" name="verandering_denken"
                                                <?= !isset($antwoorden['verandering_denken']) ? "checked" : "" ?>>
                                            <label>Nee</label>
                                        </p>
                                    </div>
                                </div>
                                <div class="question">
                                    <p>Heeft u moeite met spreken?</p>
                                    <div class="checkboxes">
                                        <p>
                                            <input type="radio" name="moeite_spreken"
                                                <?= isset($antwoorden['moeite_spreken']) ? "checked" : "" ?>>
                                            <label>Ja</label>
                                        </p>
                                        <p>
                                            <input type="radio" name="moeite_spreken"
                                                <?= !isset($antwoorden['moeite_spreken']) ? "checked" : "" ?>>
                                            <label>Nee</label>
                                        </p>
                                    </div>
                                </div>
                                <div class="question">
                                    <p>- Welke taal spreekt u thuis?</p><textarea rows="1" cols="25"
                                        type="text"><?= isset($antwoorden['taal_thuis']) ? $antwoorden['taal_thuis'] : '' ?></textarea>
                                </div>
                                <div class="question">
                                    <p>Zijn er de afgelopen tijd veranderingen opgetreden in uw concentratievermogen?
                                    </p>
                                    <div class="checkboxes">
                                        <p>
                                            <input type="radio" name="verandering_concentratievermogen"
                                                <?= isset($antwoorden['verandering_concentratievermogen']) ? "checked" : "" ?>>
                                            <label>Ja</label>
                                        </p>
                                        <p>
                                            <input type="radio" name="verandering_concentratievermogen"
                                                <?= !isset($antwoorden['verandering_concentratievermogen']) ? "checked" : "" ?>>
                                            <label>Nee</label>
                                        </p>
                                    </div>
                                </div>
                                <div class="question">
                                    <p>Kunt u moeilijker dagelijkse beslissingen nemen?</p>
                                    <div class="checkboxes">
                                        <p>
                                            <input type="radio" name="moeilijker_beslissen"
                                                <?= isset($antwoorden['moeilijker_beslissen']) ? "checked" : "" ?>>
                                            <label>Ja</label>
                                        </p>
                                        <p>
                                            <input type="radio" name="moeilijker_beslissen"
                                                <?= !isset($antwoorden['moeilijker_beslissen']) ? "checked" : "" ?>>
                                            <label>Nee</label>
                                        </p>
                                    </div>
                                </div>
                                <div class="question">
                                    <p>Zijn er de afgelopen tijd veranderingen opgetreden in uw geheugen?</p>
                                    <div class="checkboxes">
                                        <p>
                                            <input type="radio" name="verandering_geheugen"
                                                <?= isset($antwoorden['verandering_geheugen']) ? "checked" : "" ?>>
                                            <label>Ja</label>
                                        </p>
                                        <p>
                                            <input type="radio" name="verandering_geheugen"
                                                <?= !isset($antwoorden['verandering_geheugen']) ? "checked" : "" ?>>
                                            <label>Nee</label>
                                        </p>
                                    </div>
                                </div>
                                <div class="question">
                                    <p>Zijn er de afgelopen tijd veranderingen opgetreden in uw oriëntatie?</p>
                                    <div class="checkboxes">
                                        <p>
                                            <input type="radio" name="verandering_orientatie"
                                                <?= isset($antwoorden['verandering_orientatie']) ? "checked" : "" ?>>
                                            <label>Ja</label>
                                        </p>
                                        <p>
                                            <input type="radio" name="verandering_orientatie"
                                                <?= !isset($antwoorden['verandering_orientatie']) ? "checked" : "" ?>>
                                            <label>Nee</label>
                                        </p>
                                    </div>
                                </div>
                                <div class="question">
                                    <p>Gebruikt u medicatie die uw oriëntatie, reactievermogen of denken beïnvloeden?
                                    </p>
                                    <div class="checkboxes">
                                        <div class="question-answer">
                                            <input id="radio" type="radio" name="invloed_medicatie"
                                                <?= isset($antwoorden['invloed_medicatie']) ? "checked" : "" ?>>
                                            <label>Ja</label>
                                            <textarea rows="1" cols="25" id="checkfield" type="text"
                                                placeholder="welke?"
                                                name="invloed_medicatie_welke"><?= isset($antwoorden['invloed_medicatie_welke']) ? $antwoorden['invloed_medicatie_welke'] : '' ?></textarea>
                                        </div>
                                        <p>
                                            <input type="radio" name="invloed_medicatie"
                                                <?= !isset($antwoorden['invloed_medicatie']) ? "checked" : "" ?>>
                                            <label>Nee</label>
                                        </p>
                                    </div>
                                </div>
                                <div class="question">
                                    <p>Gebruikt u verdovende/stimulerende middelen?</p>
                                    <div class="checkboxes">
                                        <div class="question-answer">
                                            <input id="radio" type="radio" name="gebruikt_middelen"
                                                <?= isset($antwoorden['gebruikt_middelen']) ? "checked" : "" ?>>
                                            <label>Ja</label>
                                            <div id="checkfield">
                                                <div class="question">
                                                    <div class="observe"><input type="checkbox"
                                                            <?= isset($antwoorden['gebruikt_middelen_softdrugs']) ? "checked" : "" ?> name="gebruikt_middelen_softdrugs">
                                                        <p>Softdrugs</p>
                                                    </div><textarea rows="1" cols="25"
                                                        type="text"><?= isset($antwoorden['gebruikt_middelen_softdrugs_welke']) ? $antwoorden['gebruikt_middelen_softdrugs_welke'] : '' ?></textarea>
                                                </div>
                                                <div class="question">
                                                    <div class="observe"><input type="checkbox"
                                                            <?= isset($antwoorden['gebruikt_middelen_harddrugs']) ? "checked" : "" ?> name="gebruikt_middelen_harddrugs">
                                                        <p>Harddrugs</p>
                                                    </div><textarea rows="1" cols="25"
                                                        type="text"><?= isset($antwoorden['gebruikt_middelen_harddrugs_welke']) ? $antwoorden['gebruikt_middelen_harddrugs_welke'] : '' ?></textarea>
                                                </div>
                                                <div class="question">
                                                    <div class="observe"><input type="checkbox"
                                                            <?= isset($antwoorden['gebruikt_middelen_alcohol']) ? "checked" : "" ?> name="gebruikt_middelen_alcohol">
                                                        <p>Alcohol</p>
                                                    </div><textarea rows="1" cols="25"
                                                        type="text"><?= isset($antwoorden['gebruikt_middelen_alcohol_welke']) ? $antwoorden['gebruikt_middelen_alcohol_welke'] : '' ?></textarea>
                                                </div>
                                                <div class="question">
                                                    <div class="observe"><input type="checkbox"
                                                            <?= isset($antwoorden['gebruikt_middelen_anders']) ? "checked" : "" ?> name="gebruikt_middelen_anders">
                                                        <p>Anders, namelijk:</p>
                                                    </div><textarea rows="1" cols="25"
                                                        type="text"><?= isset($antwoorden['gebruikt_middelen_anders_welke']) ? $antwoorden['gebruikt_middelen_anders_welke'] : '' ?></textarea>
                                                </div>
                                            </div>
                                        </div>
                                        <p>
                                            <input type="radio" name="gebruikt_middelen"
                                                <?= !isset($antwoorden['gebruikt_middelen']) ? "checked" : "" ?>>
                                            <label>Nee</label>
                                        </p>
                                    </div>
                                </div>
                                <div class="question">
                                    <p>Heeft u pijnklachten?</p>
                                    <div class="checkboxes">
                                        <p>
                                            <input type="radio" name="pijnklachten"
                                                <?= isset($antwoorden['pijnklachten']) ? "checked" : "" ?>>
                                            <label>Ja</label>
                                        </p>
                                        <p>
                                            <input type="radio" name="pijnklachten"
                                                <?= !isset($antwoorden['pijnklachten']) ? "checked" : "" ?>>
                                            <label>Nee</label>
                                        </p>
                                    </div>
                                </div>
                                <div class="question">
                                    <p>- Waar, wanneer, soort pijn?</p><textarea rows="1" cols="25" type="text"
                                        name="pijnklachten_waar_wanneer_soort"><?= isset($antwoorden['pijnklachten_waar_wanneer_soort']) ? $antwoorden['pijnklachten_waar_wanneer_soort'] : '' ?></textarea>
                                </div>
                                <div class="question">
                                    <p>- Wat doet u doorgaans tegen de pijn?</p><textarea rows="1" cols="25" type="text"
                                        name="pijnklachten_tegengaan_pijn"><?= isset($antwoorden['pijnklachten_tegengaan_pijn']) ? $antwoorden['pijnklachten_tegengaan_pijn'] : '' ?></textarea>
                                </div>
                                <div class="question">
                                    <p>- Wat doet u om pijn/ongemak zoveel mogelijk te voorkomen?</p><textarea rows="1"
                                        cols="25" type="text"
                                        name="pijnklachten_preventie"><?= isset($antwoorden['pijnklachten_preventie']) ? $antwoorden['pijnklachten_preventie'] : '' ?></textarea>
                                </div>

                                <div class="observation">
                                    <h2>Verpleegkundige observatie bij dit patroon</h2>
                                    <div class="question">
                                        <div class="observe"><input type="checkbox" <?= isset($boolArrayObservatie[0]) ? "checked" : "" ?>>
                                            <p>Wijziging in de waarneming</p>
                                        </div>
                                    </div>
                                    <div class="question">
                                        <div class="observe"><input type="checkbox" <?= isset($boolArrayObservatie[1]) ? "checked" : "" ?>>
                                            <p>Verstoord denken</p>
                                        </div>
                                    </div>
                                    <div class="question">
                                        <div class="observe"><input type="checkbox" <?= isset($boolArrayObservatie[2]) ? "checked" : "" ?>>
                                            <p>Kennistekort</p>
                                        </div>
                                    </div>
                                    <div class="question">
                                        <div class="observe"><input type="checkbox" <?= isset($boolArrayObservatie[3]) ? "checked" : "" ?>>
                                            <p>Dreigend cognitietekort</p>
                                        </div>
                                    </div>
                                    <div class="question">
                                        <div class="observe"><input type="checkbox" <?= isset($boolArrayObservatie[4]) ? "checked" : "" ?>>
                                            <p>Beslisconflict</p>
                                        </div>
                                    </div>
                                    <div class="question">
                                        <div class="observe"><input type="checkbox" <?= isset($boolArrayObservatie[5]) ? "checked" : "" ?>>
                                            <p>Achterdocht</p>
                                        </div>
                                    </div>
                                    <div class="question">
                                        <div class="observe"><input type="checkbox" <?= isset($boolArrayObservatie[6]) ? "checked" : "" ?>>
                                            <p>Acute verwardheid</p>
                                        </div>
                                    </div>
                                    <div class="question">
                                        <div class="observe"><input type="checkbox" <?= isset($boolArrayObservatie[7]) ? "checked" : "" ?>>
                                            <p>Pijn (specificeer type en locatie)</p>
                                        </div>
                                    </div>
                                    <div class="question">
                                        <div class="observe"><input type="checkbox" <?= isset($boolArrayObservatie[8]) ? "checked" : "" ?>>
                                            <p>Chronische pijn (specificeer type en locatie)</p>
                                        </div>
                                    </div>
                                    <div class="question">
                                        <div class="observe"><input type="checkbox" <?= isset($boolArrayObservatie[9]) ? "checked" : "" ?>>
                                            <p>Middelenmisbruik:</p>
                                        </div>
                                    </div>
                                    <div class="question">
                                        <div class="observe"><input type="checkbox" <?= isset($boolArrayObservatie[10]) ? "checked" : "" ?>>
                                            <p>Alcohol</p>
                                        </div>
                                    </div>
                                    <div class="question">
                                        <div class="observe"><input type="checkbox" <?= isset($boolArrayObservatie[11]) ? "checked" : "" ?>>
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
    </form>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz"
        crossorigin="anonymous"></script>
</body>

</html>