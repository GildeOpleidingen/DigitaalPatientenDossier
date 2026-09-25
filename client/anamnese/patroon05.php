<?php
include_once '../../models/autoload.php';
Auth::requireLogin();
$Main = new Main();

$clientId = $_SESSION['clientId'];
$antwoorden = $Main->getAnswers($clientId, 5);

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['navbutton'])) {

    PatroonModel::saveAnswers(
        (int)$_SESSION['clientId'],
        (int)$_SESSION['loggedin_id'],
        5,
        $_POST
    );

    // Navigation
    switch ($_POST['navbutton']) {
        case 'next':
            header("Location: patroon06.php");
            exit;
        case 'prev':
            header("Location: patroon04.php");
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
                        <div class="h4 text-primary">5. Slaap- en rustpatroon</div>
                        <div class="form">
                            <div class="questionnaire">
                                <div class="question">
                                    <p>Is er in de afgelopen periode verandering in de de duur van uw slaap gekomen?</p>
                                    <div class="checkboxes">
                                        <div class="question-answer">
                                            <input class="radio" type="radio" value="1" name="verandering_inslaaptijd" <?= (isset($antwoorden['verandering_inslaaptijd']) && $antwoorden['verandering_inslaaptijd'] == '1') ? "checked" : "" ?>>
                                            <label>Ja</label>
                                            <textarea rows="1" cols="25" class="checkfield" type="text" placeholder="en wel?" name="verandering_inslaaptijd_blijktuit"><?= isset($antwoorden['verandering_inslaaptijd_blijktuit']) ? $antwoorden['verandering_inslaaptijd_blijktuit'] : '' ?></textarea>
                                        </div>
                                        <p>
                                            <input type="radio" value="0" name="verandering_inslaaptijd" <?= (!isset($antwoorden['verandering_inslaaptijd']) || $antwoorden['verandering_inslaaptijd'] == '0') ? "checked" : "" ?>>
                                            <label>Nee</label>
                                        </p>
                                    </div>
                                </div>
                                <div class="question">
                                    <p>- Is er verandering ontstaan in de kwaliteit van uw slaap (in- en/of doorslaapprobleem)?</p>
                                    <div class="checkboxes">
                                        <div class="question-answer">
                                            <input class="radio" type="radio" value="1" name="verandering_kwaliteit_slapen" <?= (isset($antwoorden['verandering_kwaliteit_slapen']) && $antwoorden['verandering_kwaliteit_slapen'] == '1') ? "checked" : "" ?>>
                                            <label>Ja</label>
                                            <textarea rows="1" cols="25" class="checkfield" type="text" placeholder="en wel?" name="verandering_kwaliteit_slapen_blijktuit"><?= isset($antwoorden['verandering_kwaliteit_slapen_blijktuit']) ? $antwoorden['verandering_kwaliteit_slapen_blijktuit'] : '' ?></textarea>
                                        </div>
                                        <p>
                                            <input type="radio" value="0" name="verandering_kwaliteit_slapen" <?= (!isset($antwoorden['verandering_kwaliteit_slapen']) || $antwoorden['verandering_kwaliteit_slapen'] == '0') ? "checked" : "" ?>>
                                            <label>Nee</label>
                                        </p>
                                    </div>
                                </div>
                                <div class="question">
                                    <p>- Doet u iets om (in) te kunnen slapen?</p>
                                    <div class="checkboxes">
                                        <div class="question-answer">
                                            <input class="radio" type="radio" value="1" name="gebruik_inslaapmiddel" <?= (isset($antwoorden['gebruik_inslaapmiddel']) && $antwoorden['gebruik_inslaapmiddel'] == '1') ? "checked" : "" ?>>
                                            <label>Ja</label>
                                            <div class="checkfield">
                                                <div class="question">
                                                    <div class="observe"><input type="checkbox" <?= PatroonModel::isChecked($antwoorden['gebruik_inslaapmiddel_welke'], 0) ?> name="inslaapmiddel1">
                                                        <p>Medicijngebruik</p>
                                                    </div>
                                                </div>
                                                <div class="question">
                                                    <div class="observe"><input type="checkbox" <?= PatroonModel::isChecked($antwoorden['gebruik_inslaapmiddel_welke'], 1) ?> name="inslaapmiddel2">
                                                        <p>Beweging</p>
                                                    </div>
                                                </div>
                                                <div class="question">
                                                    <div class="observe"><input type="checkbox" <?= PatroonModel::isChecked($antwoorden['gebruik_inslaapmiddel_welke'], 2) ?> name="inslaapmiddel3">
                                                        <p>Alcohol/drugs</p>
                                                    </div>
                                                </div>
                                                <div class="question">
                                                    <div class="observe"><input type="checkbox" <?= PatroonModel::isChecked($antwoorden['gebruik_inslaapmiddel_welke'], 3) ?> name="inslaapmiddel4">
                                                        <p>Eten/drinken</p>
                                                    </div>
                                                </div>
                                                <div class="question">
                                                    <div class="observe"><input type="checkbox" <?= PatroonModel::isChecked($antwoorden['gebruik_inslaapmiddel_welke'], 4) ?> name="inslaapmiddel5">
                                                        <p>Douche/bad</p>
                                                    </div>
                                                </div>

                                            </div>
                                        </div>
                                        <p>
                                            <input type="radio" value="0" name="gebruik_inslaapmiddel" <?= (!isset($antwoorden['gebruik_inslaapmiddel']) || $antwoorden['gebruik_inslaapmiddel'] == '0') ? "checked" : "" ?>>
                                            <label>Nee</label>
                                        </p>
                                    </div>
                                </div>
                                <div class="question">
                                    <p>- Hoe lang slaapt u nomaal?</p>
                                    <p><input type="number" step=0.5 min="0" max="24" value="<?= isset($antwoorden['slaapduur']) ? $antwoorden['slaapduur'] : '' ?>" name="slaapduur"> uur</p>
                                </div>
                                <div class="question">
                                    <p>- Voelt u zich uitgerust als u wakker wordt?</p>
                                    <div class="checkboxes">
                                        <p>
                                            <input type="radio" value="1" name="uitgerust_wakker" <?= (isset($antwoorden['uitgerust_wakker']) && $antwoorden['uitgerust_wakker'] == '1') ? "checked" : "" ?>>
                                            <label>Ja</label>
                                        </p>
                                        <p>
                                            <input type="radio" value="0" name="uitgerust_wakker" <?= (!isset($antwoorden['uitgerust_wakker']) || $antwoorden['uitgerust_wakker'] == '0') ? "checked" : "" ?>>
                                            <label>Nee</label>
                                        </p>
                                    </div>
                                </div>
                                <div class="question">
                                    <p>Heeft u last van dromen, nachtmerries?</p>
                                    <div class="checkboxes">
                                        <p>
                                            <input type="radio" value="1" name="dromen_nachtmerries" <?= (isset($antwoorden['dromen_nachtmerries']) && $antwoorden['dromen_nachtmerries'] == '1') ? "checked" : "" ?>>
                                            <label>Ja</label>
                                        </p>
                                        <p>
                                            <input type="radio" value="0" name="dromen_nachtmerries" <?= (!isset($antwoorden['dromen_nachtmerries']) || $antwoorden['dromen_nachtmerries'] == '0') ? "checked" : "" ?>>
                                            <label>Nee</label>
                                        </p>
                                    </div>
                                </div>
                                <div class="question">
                                    <p>- Neemt u rustperioden overdag?</p>
                                    <div class="checkboxes">
                                        <p>
                                            <input type="radio" value="1" name="rustperiodes_overdag" <?= (isset($antwoorden['rustperiodes_overdag']) && $antwoorden['rustperiodes_overdag'] == '1') ? "checked" : "" ?>>
                                            <label>Ja</label>
                                        </p>
                                        <p>
                                            <input type="radio" value="0" name="rustperiodes_overdag" <?= (!isset($antwoorden['rustperiodes_overdag']) || $antwoorden['rustperiodes_overdag'] == '0') ? "checked" : "" ?>>
                                            <label>Nee</label>
                                        </p>
                                    </div>
                                </div>
                                <div class="question">
                                    <p>- Kunt u zich gemakkelijk ontspannen?</p>
                                    <div class="checkboxes">
                                        <p>
                                            <input type="radio" value="1" name="gemakkelijk_ontspannen" <?= (isset($antwoorden['gemakkelijk_ontspannen']) && $antwoorden['gemakkelijk_ontspannen'] == '1') ? "checked" : "" ?>>
                                            <label>Ja</label>
                                        </p>
                                        <p>
                                            <input type="radio" value="0" name="gemakkelijk_ontspannen" <?= (!isset($antwoorden['gemakkelijk_ontspannen']) || $antwoorden['gemakkelijk_ontspannen'] == '0') ? "checked" : "" ?>>
                                            <label>Nee</label>
                                        </p>
                                    </div>
                                </div>

                                <div class="observation">
                                    <h2>Verpleegkundige observatie bij dit patroon</h2>
                                    <div class="question">
                                        <div class="observe"><input type="checkbox" <?= PatroonModel::isChecked($antwoorden['observatie'], 0) ?> name="observatie1">
                                            <p>Verstoord slaap- en rustpatroon</p>
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
