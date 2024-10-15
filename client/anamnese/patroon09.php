<?php
session_start();
include '../../database/DatabaseConnection.php';
include_once '../../classes/autoload.php';
$Main = new Main();

$antwoorden = $Main->getPatternAnswers($_SESSION['clientId'], 9);

$boolArrayGerichtheid = str_split($antwoorden['seksuele_gerichtheid']);
$boolArrayObservatie = isset($antwoorden['observatie']) && $antwoorden['observatie'] !== null ? str_split($antwoorden['observatie']) : [];

if (isset($_REQUEST['navbutton'])) {
    //TODO: hier actie om data op te slaan in database.
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
    <link rel="stylesheet" href="../../assets/css/bootstrap.min.css">
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
                        <div class="h4 text-primary">9. Seksualiteits- en voorplantingspatroon</div>
                        <div class="form">
                            <div class="questionnaire">
                                <div class="question">
                                    <p>Is uw seksuele beleving veranderd?</p>
                                    <div class="checkboxes">
                                        <div class="question-answer">
                                            <input id="radio" type="radio" name="verandering_seksuele_beleving" <?= isset($antwoorden['verandering_seksuele_beleving']) ? "checked" : "" ?>>
                                            <label>Ja</label>
                                            <textarea rows="1" cols="25" id="checkfield" type="text" placeholder="door?" name="verandering_seksuele_beleving_door"><?= isset($antwoorden['verandering_seksuele_beleving_door']) ? $antwoorden['verandering_seksuele_beleving_door'] : '' ?></textarea>
                                        </div>
                                        <p>
                                            <input type="radio" name="verandering_seksuele_beleving" <?= !isset($antwoorden['verandering_seksuele_beleving']) ? "checked" : "" ?>>
                                            <label>Nee</label>
                                        </p>
                                    </div>
                                </div>
                                <div class="question">
                                    <p>- Is er de laatste tijd verandering gekomen in uw seksuele gedrag?</p>
                                    <div class="checkboxes">
                                        <p>
                                            <input type="radio" name="verandering_seksueel_gedrag" <?= isset($antwoorden['verandering_seksueel_gedrag']) ? "checked" : "" ?>>
                                            <label>Ja</label>
                                        </p>
                                        <p>
                                            <input type="radio" name="verandering_seksueel_gedrag" <?= !isset($antwoorden['verandering_seksueel_gedrag']) ? "checked" : "" ?>>
                                            <label>Nee</label>
                                        </p>
                                    </div>
                                </div>
                                <div class="question">
                                    <p>- Heeft u wisselende seksuele contacten?</p>
                                    <div class="checkboxes">
                                        <p>
                                            <input type="radio" name="wisselende_contacten" <?= isset($antwoorden['wisselende_contacten']) ? "checked" : "" ?>>
                                            <label>Ja</label>
                                        </p>
                                        <p>
                                            <input type="radio" name="wisselende_contacten" <?= !isset($antwoorden['wisselende_contacten']) ? "checked" : "" ?>>
                                            <label>Nee</label>
                                        </p>
                                    </div>
                                </div>
                                <div class="question">
                                    <p>- Houdt u bij uw seksuele activiteiten rekening met veilig vrijen?</p>
                                    <div class="checkboxes">
                                        <p>
                                            <input type="radio" name="veilig_vrijen" <?= isset($antwoorden['veilig_vrijen']) ? "checked" : "" ?>>
                                            <label>Ja</label>
                                        </p>
                                        <p>
                                            <input type="radio" name="veilig_vrijen" <?= !isset($antwoorden['veilig_vrijen']) ? "checked" : "" ?>>
                                            <label>Nee</label>
                                        </p>
                                    </div>
                                </div>
                                <div class="question">
                                    <p>Gebruikt u anticonceptiemiddelen?</p>
                                    <div class="checkboxes">
                                        <div class="question-answer">
                                            <input id="radio" type="radio" name="anticonceptiemiddel" <?= isset($antwoorden['anticonceptiemiddel']) ? "checked" : "" ?>>
                                            <label>Ja</label>
                                            <textarea rows="1" cols="25" id="checkfield" type="text" placeholder="welke?" name="anticonceptiemiddel_welke"> <?= isset($antwoorden['anticonceptiemiddel_welke']) ? $antwoorden['anticonceptiemiddel_welke'] : '' ?></textarea>
                                        </div>
                                        <p>
                                            <input type="radio" name="anticonceptiemiddel" <?= !isset($antwoorden['anticonceptiemiddel']) ? "checked" : "" ?>>
                                            <label>Nee</label>
                                        </p>
                                    </div>
                                </div>
                                <div class="question">
                                    <p>- Heeft u problemen bij het gebruik van anticonceptie-middelen?</p>
                                    <div class="checkboxes">
                                        <p>
                                            <input type="radio" name="anticonceptiemiddel_problemen" <?= isset($antwoorden['anticonceptiemiddel_problemen']) ? "checked" : "" ?>>
                                            <label>Ja</label>
                                        </p>
                                        <p>
                                            <input type="radio" name="anticonceptiemiddel_problemen" <?= !isset($antwoorden['anticonceptiemiddel_problemen']) ? "checked" : "" ?>>
                                            <label>Nee</label>
                                        </p>
                                    </div>
                                </div>
                                <div class="question">
                                    <p>Wat is uw seksuele gerichtheid?</p>
                                    <div class="observation">
                                        <div class="question">
                                            <div class="observe"><input type="checkbox" <?= isset($boolArrayGerichtheid[0]) ? "checked" : "" ?> name="gerichtheid1">
                                                <p>Heteroseksueel</p>
                                            </div>
                                        </div>
                                        <div class="question">
                                            <div class="observe"><input type="checkbox" <?= isset($boolArrayGerichtheid[1]) ? "checked" : "" ?> name="gerichtheid2">
                                                <p>Biseksueel</p>
                                            </div>
                                        </div>
                                        <div class="question">
                                            <div class="observe"><input type="checkbox" <?= isset($boolArrayGerichtheid[2]) ? "checked" : "" ?> name="gerichtheid3">
                                                <p>Homoseksueel</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="question">
                                    <p>- Ondervindt u problemen bij u zelf of bij anderen ten aanzien van uw seksuele gerichtheid?</p>
                                    <div class="checkboxes">
                                        <p>
                                            <input type="radio" name="seksuele_gerichtheid_problemen" <?= isset($antwoorden['seksuele_gerichtheid_problemen']) ? "checked" : "" ?>>
                                            <label>Ja</label>
                                        </p>
                                        <p>
                                            <input type="radio" name="seksuele_gerichtheid_problemen" <?= !isset($antwoorden['seksuele_gerichtheid_problemen']) ? "checked" : "" ?>>
                                            <label>Nee</label>
                                        </p>
                                    </div>
                                </div>
                                <div class="question">
                                    <p>Heeft u last (gehad) van seksueel overdraagbare aandoeningen (soa)?</p>
                                    <div class="checkboxes">
                                        <div class="question-answer">
                                            <input id="radio" type="radio" name="soa" <?= isset($antwoorden['soa']) ? "checked" : "" ?>>
                                            <label>Ja</label>
                                            <textarea rows="1" cols="25" id="checkfield" type="text" placeholder="en wel?" name="soa_welke"> <?= isset($antwoorden['soa_welke']) ? $antwoorden['soa_welke'] : '' ?></textarea>
                                        </div>
                                        <p>
                                            <input type="radio" name="soa" <?= !isset($antwoorden['soa']) ? "checked" : "" ?>>
                                            <label>Nee</label>
                                        </p>
                                    </div>
                                </div>

                                <div class="observation">
                                    <h2>Verpleegkundige observatie bij dit patroon</h2>
                                    <div class="question">
                                        <div class="observe"><input type="checkbox" <?= !isset($boolArrayObservatie[0]) ? "checked" : "" ?> name="observatie1">
                                            <p>Gewijzigde seksuele gewoonten</p>
                                        </div>
                                    </div>
                                    <div class="question">
                                        <div class="observe"><input type="checkbox" <?= !isset($boolArrayObservatie[1]) ? "checked" : "" ?> name="observatie2">
                                            <p>Seksueel disfunctioneren</p>
                                        </div>
                                    </div>
                                    <div class="question">
                                        <div class="observe"><input type="checkbox" <?= !isset($boolArrayObservatie[2]) ? "checked" : "" ?> name="observatie3">
                                            <p>Verkrachtingssyndroom gecompliceerde vorm</p>
                                        </div>
                                    </div>
                                    <div class="question">
                                        <div class="observe"><input type="checkbox" <?= !isset($boolArrayObservatie[3]) ? "checked" : "" ?> name="observatie4">
                                            <p>Verkrachtingssyndroom stille vorm</p>
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
</body>

</html>