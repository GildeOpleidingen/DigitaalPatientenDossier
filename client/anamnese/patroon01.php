<?php
include_once '../../models/autoload.php';
Auth::requireLogin();

$Main = new Main();

$clientId = $_SESSION['clientId'];
$antwoorden = $Main->getAnswers($clientId, 1);
$answers = $antwoorden;

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['navbutton'])) {

    PatroonModel::saveAnswers(
        (int)$_SESSION['clientId'],
        (int)$_SESSION['loggedin_id'],
        1,
        $_POST
    );

    // Navigation
    switch ($_POST['navbutton']) {
        case 'next':
            header("Location: patroon02.php");
            exit;
        case 'prev':
            header("Location: patroon11.php");
            exit;
    }
}
?>


<!DOCTYPE html>
<html lang="nl">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Anamnese – Patroon 1</title>

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
                    <p class="card-text">
                    <div class="form-content">
                        <div class="h4 text-primary">1. Patroon van gezondheidsbeleving en -instandhouding</div>
                        <div class="form">
                            <div class="questionnaire">
                                <div class="question">
                                    <p>Hoe is uw gezondheid in het algemeen?</p><textarea rows="1" cols="25" type="text" name="algemene_gezondheid"><?= isset($answers['algemene_gezondheid']) ? $answers['algemene_gezondheid'] : '' ?></textarea>
                                </div>
                                <div class="question">
                                    <p>Wat doet u om gezond te blijven?</p><textarea rows="1" cols="25" type="text" name="gezondheids_bezigheid"><?= isset($answers['gezondheids_bezigheid']) ? $answers['gezondheids_bezigheid'] : '' ?></textarea>
                                </div>
                                <div class="question">
                                    <p>- Rookt u?</p>
                                    <div class="checkboxes">
                                        <div class="question-answer">
                                            <input class="radio" type="radio" name="rookt" <?= isset($answers['rookt']) && $answers['rookt'] == 1 ? "checked" : "" ?> value="1">
                                            <label>Ja</label>
                                            <textarea rows="1" cols="25" class="checkfield" type="text" placeholder="hoeveel?" name="rookt_hoeveelheid"><?= isset($answers['rookt_hoeveelheid']) ? $answers['rookt_hoeveelheid'] : '' ?></textarea>
                                        </div>
                                        <p>
                                            <input type="radio" name="rookt" <?= isset($answers['rookt']) && $answers['rookt'] == 0 ? "checked" : "" ?> value="0">
                                            <label>Nee</label>
                                        </p>
                                    </div>
                                </div>
                                <div class="question">
                                    <p>- Drinkt u?</p>
                                    <div class="checkboxes">
                                        <div class="question-answer">
                                            <input class="radio" type="radio" name="drinkt" <?= isset($answers['drinkt']) && $answers['drinkt'] == 1 ? "checked" : "" ?> value="1">
                                            <label>Ja</label>
                                            <textarea rows="1" cols="25" class="checkfield" type="text" placeholder="hoeveel?" name="drinkt_hoeveelheid"><?= isset($answers['drinkt_hoeveelheid']) ? $answers['drinkt_hoeveelheid'] : '' ?></textarea>
                                        </div>
                                        <p>
                                            <input type="radio" name="drinkt" <?= isset($answers['drinkt']) && $answers['drinkt'] == 0 ? "checked" : "" ?> value="0">
                                            <label>Nee</label>
                                        </p>
                                    </div>
                                </div>
                                <div class="question">
                                    <p>Heeft u momenteel een infectie of overdraagbare besmettelijke aandoening?</p>
                                    <div class="checkboxes">
                                        <div class="question-answer">
                                            <input class="radio" type="radio" name="besmettelijke_aandoening" <?= isset($answers['besmettelijke_aandoening']) && $answers['besmettelijke_aandoening'] == 1 ? "checked" : "" ?> value="1">
                                            <label>Ja</label>
                                            <textarea rows="1" cols="25" class="checkfield" type="text" placeholder="en wel?" name="besmettelijke_aandoening_welke"><?= isset($answers['besmettelijke_aandoening_welke']) ? $answers['besmettelijke_aandoening_welke'] : '' ?></textarea>
                                        </div>
                                        <p>
                                            <input type="radio" name="besmettelijke_aandoening" <?= isset($answers['besmettelijke_aandoening']) && $answers['besmettelijke_aandoening'] == 0 ? "checked" : "" ?>  value="0">
                                            <label>Nee</label>
                                        </p>
                                    </div>
                                </div>
                                <div class="question">
                                    <p>Bent u ergens allergisch voor?</p>
                                    <div class="checkboxes">
                                        <div class="question-answer">
                                            <input class="radio" type="radio" name="alergieen" <?= isset($answers['alergieen']) && $answers['alergieen'] == 1 ? "checked" : "" ?> value="1">
                                            <label>Ja</label>
                                            <textarea rows="1" cols="25" class="checkfield" type="text" placeholder="en wel?" name="alergieen_welke"><?= isset($answers['alergieen_welke']) ? $answers['alergieen_welke'] : '' ?></textarea>
                                        </div>
                                        <p>
                                            <input type="radio" name="alergieen" <?= isset($answers['alergieen']) && $answers['alergieen'] == 0 ? "checked" : "" ?> value="0">
                                            <label>Nee</label>
                                        </p>
                                    </div>
                                </div>
                                <div class="question">
                                    <p>Wat denkt u dat de oorzaak is van uw huidige situatie/toestand?</p><textarea rows="1" cols="25" type="text" name="oorzaak_huidige_toestand"><?= isset($answers['oorzaak_huidige_toestand']) ? $answers['oorzaak_huidige_toestand'] : '' ?></textarea>
                                </div>
                                <div class="question">
                                    <p>- Wat heeft u eraan gedaan?</p><textarea rows="1" cols="25" type="text" name="oht_actie"><?= isset($answers['oht_actie']) ? $answers['oht_actie'] : '' ?></textarea>
                                </div>
                                <div class="question">
                                    <p>- Hoe effectief was dat?</p><textarea rows="1" cols="25" type="text" name="oht_hoe_effectief"><?= isset($answers['oht_hoe_effectief']) ? $answers['oht_hoe_effectief'] : '' ?></textarea>
                                </div>
                                <div class="question">
                                    <p>- Hoe kunnen wij u helpen?</p><textarea rows="1" cols="25" type="text" name="oht_wat_nodig"><?= isset($answers['oht_wat_nodig']) ? $answers['oht_wat_nodig'] : '' ?></textarea>
                                </div>
                                <div class="question">
                                    <p>- Wat is voor u belangrijk tijdens het verblijf op deze afdeling?</p><textarea rows="1" cols="25" type="text" name="oht_wat_belangrijk"><?= isset($answers['oht_wat_belangrijk']) ? $answers['oht_wat_belangrijk'] : '' ?></textarea>
                                </div>
                                <div class="question">
                                    <p>- Vind u het gemakkelijk om dingen te doen of te laten op advies van de arts of verpleegkundige?</p><textarea rows="1" cols="25" type="text" name="oht_reactie_op_advies"><?= isset($answers['oht_reactie_op_advies']) ? $answers['oht_reactie_op_advies'] : '' ?></textarea>
                                </div>
                                <div class="question">
                                    <p>Wat moet u in de toekomst doen ter voorkoming van het weer ziek worden?</p><textarea rows="1" cols="25" type="text" name="preventie"><?= isset($answers['preventie']) ? $answers['preventie'] : '' ?></textarea>
                                </div>


                                <div class="observation">
                                    <h2>Verpleegkundige observatie bij dit patroon</h2>
                                    <div class="question">
                                        <div class="observe"><input type="checkbox" <?= PatroonModel::isChecked($antwoorden['observatie'], 0) ?> value="1" name="observatie1">
                                            <p>Gezondheidszoekend gedrag</p>
                                        </div>
                                    </div>
                                    <div class="question">
                                        <div class="observe"><input type="checkbox" <?= PatroonModel::isChecked($antwoorden['observatie'], 1) ?> value="1" name="observatie2">
                                            <p>Tekort in gezondheidsonderhoud</p>
                                        </div>
                                    </div>
                                    <div class="question">
                                        <div class="observe"><input type="checkbox" <?= PatroonModel::isChecked($antwoorden['observatie'], 2) ?> value="1" name="observatie3">
                                            <p>(Dreigende) inadequate opvolging van de behandeling</p>
                                        </div>
                                    </div>
                                    <div class="question">
                                        <div class="observe"><input type="checkbox" <?= PatroonModel::isChecked($antwoorden['observatie'], 3) ?> value="1" name="observatie4">
                                            <p>(Dreigend) tekort in gezondheidsinstandhouding</p>
                                        </div>
                                    </div>
                                    <div class="question">
                                        <div class="observe"><input type="checkbox" <?= PatroonModel::isChecked($antwoorden['observatie'], 4) ?> value="1" name="observatie5">
                                            <p>(Dreigende) therapieontrouw</p>
                                        </div>
                                    </div>
                                    <div class="question">
                                        <div class="observe"><input type="checkbox" <?= PatroonModel::isChecked($antwoorden['observatie'], 5) ?> value="1" name="observatie6">
                                            <p>Vergiftigingsgevaar</p>
                                        </div>
                                    </div>
                                    <div class="question">
                                        <div class="observe"><input type="checkbox" <?= PatroonModel::isChecked($antwoorden['observatie'], 6) ?> value="1" name="observatie7">
                                            <p>Infectiegevaar</p>
                                        </div>
                                    </div>
                                    <div class="question">
                                        <div class="observe"><input type="checkbox" <?= PatroonModel::isChecked($antwoorden['observatie'], 7) ?> value="1" name="observatie8">
                                            <p>Gevaar voor letsel (trauma)</p>
                                        </div>
                                    </div>
                                    <div class="question">
                                        <div class="observe"><input type="checkbox" <?= PatroonModel::isChecked($antwoorden['observatie'], 8) ?> value="1" name="observatie9">
                                            <p>Verstikkingsgevaar</p>
                                        </div>
                                    </div>
                                    <div class="question">
                                        <div class="observe"><input type="checkbox" <?= PatroonModel::isChecked($antwoorden['observatie'], 9) ?> value="1" name="observatie10">
                                            <p>Beschermingstekort</p>
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

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="../../assets/js/form-autosave.js"></script>

</body>
</html>
