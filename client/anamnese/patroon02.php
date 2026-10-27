<?php
include_once '../../models/autoload.php';
Auth::requireLogin();

$Main = new Main();

$clientId = $_SESSION['clientId'];
$antwoorden = $Main->getAnswers($clientId, 2);

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['navbutton'])) {

    PatroonModel::saveAnswers(
        (int)$_SESSION['clientId'],
        (int)$_SESSION['loggedin_id'],
        2,
        $_POST
    );

    // Navigation
    switch ($_POST['navbutton']) {
        case 'next':
            header("Location: patroon03.php");
            exit;
        case 'prev':
            header("Location: patroon01.php");
            exit;
    }
}
?>
<!DOCTYPE html>
<html lang="nl">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Anamnese — Voedings- en Stofwisselingspatroon</title>

    <link rel="stylesheet" href="../../assets/css/client/patronen.css">
    <link rel="stylesheet" href="../../assets/vendor/bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet"
          href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css">
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
                        <div class="h4 text-primary">2. Voedings- en stofwisselingspatroon</div>
                        <div class="form">
                            <div class="questionnaire">
                                <div class="question">
                                    <p>Hoe is uw eetlust nu?</p>
                                    <div class="checkboxes">
                                        <p>
                                            <input type="radio" value="0" name="eetlust" <?= PatroonModel::isRadioChecked($antwoorden['eetlust'] ?? null, 0) ?>>
                                            <label>Normaal</label>
                                        </p>
                                        <p>
                                            <input type="radio" value="1" name="eetlust" <?= PatroonModel::isRadioChecked($antwoorden['eetlust'] ?? null, 1) ?>>
                                            <label>Slecht</label>
                                        </p>
                                        <p>
                                            <input type="radio" value="2" name="eetlust" <?= PatroonModel::isRadioChecked($antwoorden['eetlust'] ?? null, 2) ?>>
                                            <label>Overmatig</label>
                                        </p>
                                    </div>
                                </div>
                                <div class="question">
                                    <p>- Heeft u een dieet?</p>
                                    <div class="checkboxes">
                                        <div class="question-answer">
                                            <input class="radio" type="radio" value="1" name="dieet" <?= PatroonModel::isRadioChecked($antwoorden['dieet'] ?? null, 1) ?>>
                                            <label>Ja</label>
                                            <textarea rows="1" cols="25" class="checkfield" type="text" name="dieet_welk"
                                                placeholder="en wel?"><?= isset($antwoorden['dieet_welk']) ? htmlspecialchars($antwoorden['dieet_welk']) : '' ?></textarea>
                                        </div>
                                        <p>
                                            <input type="radio" value="0" name="dieet" <?= PatroonModel::isRadioChecked($antwoorden['dieet'] ?? null, 0) ?>>
                                            <label>Nee</label>
                                        </p>
                                    </div>
                                </div>
                                <div class="question">
                                    <p>- Is uw gewicht de laatste tijd veranderd?</p>
                                    <div class="checkboxes">
                                        <p>
                                            <input type="radio" value="1" name="gewicht_verandert" <?= PatroonModel::isRadioChecked($antwoorden['gewicht_verandert'] ?? null, 1) ?>>
                                            <label>Ja</label>
                                        </p>
                                        <p>
                                            <input type="radio" value="0" name="gewicht_verandert" <?= PatroonModel::isRadioChecked($antwoorden['gewicht_verandert'] ?? null, 0) ?>>
                                            <label>Nee</label>
                                        </p>
                                    </div>
                                </div>
                                <div class="question">
                                    <p>Heeft u moeite met slikken?</p>
                                    <div class="checkboxes">
                                        <p>
                                            <input type="radio" value="1" name="moeilijk_slikken" <?= PatroonModel::isRadioChecked($antwoorden['moeilijk_slikken'] ?? null, 1) ?>>
                                            <label>Ja</label>
                                        </p>
                                        <p>
                                            <input type="radio" value="0" name="moeilijk_slikken" <?= PatroonModel::isRadioChecked($antwoorden['moeilijk_slikken'] ?? null, 0) ?>>
                                            <label>Nee</label>
                                        </p>
                                    </div>
                                </div>
                                <div class="question">
                                    <p>Heeft u gebitsproblemen?</p>
                                    <div class="checkboxes">
                                        <p>
                                            <input type="radio" value="1" name="gebitsproblemen" <?= PatroonModel::isRadioChecked($antwoorden['gebitsproblemen'] ?? null, 1) ?>>
                                            <label>Ja</label>
                                        </p>
                                        <p>
                                            <input type="radio" value="0" name="gebitsproblemen" <?= PatroonModel::isRadioChecked($antwoorden['gebitsproblemen'] ?? null, 0) ?>>
                                            <label>Nee</label>
                                        </p>
                                    </div>
                                </div>
                                <div class="question">
                                    <p>- Heeft u een gebitsprothese?</p>
                                    <div class="checkboxes">
                                        <p>
                                            <input type="radio" value="1" name="gebitsprothese" <?= PatroonModel::isRadioChecked($antwoorden['gebitsprothese'] ?? null, 1) ?>>
                                            <label>Ja</label>
                                        </p>
                                        <p>
                                            <input type="radio" value="0" name="gebitsprothese" <?= PatroonModel::isRadioChecked($antwoorden['gebitsprothese'] ?? null, 0) ?>>
                                            <label>Nee</label>
                                        </p>
                                    </div>
                                </div>
                                <div class="question">
                                    <p>Heeft u huidproblemen?</p>
                                    <div class="checkboxes">
                                        <p>
                                            <input type="radio" value="1" name="huidproblemen" <?= PatroonModel::isRadioChecked($antwoorden['huidproblemen'] ?? null, 1) ?>>
                                            <label>Ja</label>
                                        </p>
                                        <p>
                                            <input type="radio" value="0" name="huidproblemen" <?= PatroonModel::isRadioChecked($antwoorden['huidproblemen'] ?? null, 0) ?>>
                                            <label>Nee</label>
                                        </p>
                                    </div>
                                </div>
                                <div class="question">
                                    <p>Heeft u het koud of warm?</p>
                                    <div class="checkboxes">
                                        <p>
                                            <input type="radio" value="0" name="gevoel" <?= PatroonModel::isRadioChecked($antwoorden['gevoel'] ?? null, 0) ?>>
                                            <label>Normaal</label>
                                        </p>
                                        <p>
                                            <input type="radio" value="1" name="gevoel" <?= PatroonModel::isRadioChecked($antwoorden['gevoel'] ?? null, 1) ?>>
                                            <label>Koud</label>
                                        </p>
                                        <p>
                                            <input type="radio" value="2" name="gevoel" <?= PatroonModel::isRadioChecked($antwoorden['gevoel'] ?? null, 2) ?>>
                                            <label>Warm</label>
                                        </p>
                                    </div>
                                </div>


                                <div class=" observation">
                                    <h2>Verpleegkundige observatie bij dit patroon</h2>
                                    <div class="question">
                                        <div class="observe"><input type="checkbox" value="1" name="observatie1" <?= PatroonModel::isChecked($antwoorden['observatie'], 0) ?>>
                                            <p>(Dreigend) voedingsteveel (zwaarlijvigheid)</p>
                                        </div>
                                    </div>
                                    <div class="question">
                                        <div class="observe"><input type="checkbox" value="1" name="observatie2" <?= PatroonModel::isChecked($antwoorden['observatie'], 1) ?>>
                                            <p>Voedingstekort</p>
                                        </div>
                                    </div>
                                    <div class="question">
                                        <div class="observe"><input type="checkbox" value="1" name="observatie3" <?= PatroonModel::isChecked($antwoorden['observatie'], 2) ?>>
                                            <p>(Dreigend) vochttekort</p>
                                        </div>
                                    </div>
                                    <div class="question">
                                        <div class="observe"><input type="checkbox" value="1" name="observatie4" <?= PatroonModel::isChecked($antwoorden['observatie'], 3) ?>>
                                            <p>Falende warmteregulatie</p>
                                        </div>
                                    </div>
                                    <div class="question">
                                        <div class="observe"><input type="checkbox" value="1" name="observatie5" <?= PatroonModel::isChecked($antwoorden['observatie'], 4) ?>>
                                            <p>Aspiratiegevaar</p>
                                        </div>
                                    </div>
                                    <div class="question">
                                        <div class="observe"><input type="checkbox" value="1" name="observatie6" <?= PatroonModel::isChecked($antwoorden['observatie'], 5) ?>>
                                            <p>(Dreigende) huiddefect</p>
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

<script src="../../assets/js/form-autosave.js"></script>

</body>
</html>
