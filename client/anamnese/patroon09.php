<?php
include_once '../../models/autoload.php';
Auth::requireLogin();

$Main = new Main();

$clientId = $_SESSION['clientId'];
$antwoorden = $Main->getAnswers($clientId, 9);

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['navbutton'])) {

    PatroonModel::saveAnswers(
        (int)$_SESSION['clientId'],
        (int)$_SESSION['loggedin_id'],
        9,
        $_POST
    );

    // Navigation
    switch ($_POST['navbutton']) {
        case 'next':
            header("Location: patroon10.php");
            exit;
        case 'prev':
            header("Location: patroon08.php");
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
    <link rel="stylesheet" href="../../assets/vendor/bootstrap/css/bootstrap.min.css">
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
                                            <input class="radio" type="radio" value="1"
                                                name="verandering_seksuele_beleving"
                                                <?= PatroonModel::isRadioChecked($antwoorden['verandering_seksuele_beleving'] ?? null, 1) ?>>
                                            <label>Ja</label>
                                            <textarea rows="1" cols="25" class="checkfield" type="text" placeholder="door?"
                                                name="verandering_seksuele_beleving_door"><?= isset($antwoorden['verandering_seksuele_beleving_door']) ? htmlspecialchars($antwoorden['verandering_seksuele_beleving_door']) : '' ?></textarea>
                                        </div>
                                        <p>
                                            <input type="radio" value="0" name="verandering_seksuele_beleving"
                                                <?= PatroonModel::isRadioChecked($antwoorden['verandering_seksuele_beleving'] ?? null, 0) ?>>
                                            <label>Nee</label>
                                        </p>
                                    </div>
                                </div>
                                <div class="question">
                                    <p>- Is er de laatste tijd verandering gekomen in uw seksuele gedrag?</p>
                                    <div class="checkboxes">
                                        <p>
                                            <input type="radio" value="1" name="verandering_seksueel_gedrag"
                                                <?= PatroonModel::isRadioChecked($antwoorden['verandering_seksueel_gedrag'] ?? null, 1) ?>>
                                            <label>Ja</label>
                                        </p>
                                        <p>
                                            <input type="radio" value="0" name="verandering_seksueel_gedrag"
                                                <?= PatroonModel::isRadioChecked($antwoorden['verandering_seksueel_gedrag'] ?? null, 0) ?>>
                                            <label>Nee</label>
                                        </p>
                                    </div>
                                </div>
                                <div class="question">
                                    <p>- Heeft u wisselende seksuele contacten?</p>
                                    <div class="checkboxes">
                                        <p>
                                            <input type="radio" value="1" name="wisselende_contacten"
                                                <?= PatroonModel::isRadioChecked($antwoorden['wisselende_contacten'] ?? null, 1) ?>>
                                            <label>Ja</label>
                                        </p>
                                        <p>
                                            <input type="radio" value="0" name="wisselende_contacten"
                                                <?= PatroonModel::isRadioChecked($antwoorden['wisselende_contacten'] ?? null, 0) ?>>
                                            <label>Nee</label>
                                        </p>
                                    </div>
                                </div>
                                <div class="question">
                                    <p>- Houdt u bij uw seksuele activiteiten rekening met veilig vrijen?</p>
                                    <div class="checkboxes">
                                        <p>
                                            <input type="radio" value="1" name="veilig_vrijen"
                                                <?= PatroonModel::isRadioChecked($antwoorden['veilig_vrijen'] ?? null, 1) ?>>
                                            <label>Ja</label>
                                        </p>
                                        <p>
                                            <input type="radio" value="0" name="veilig_vrijen"
                                                <?= PatroonModel::isRadioChecked($antwoorden['veilig_vrijen'] ?? null, 0) ?>>
                                            <label>Nee</label>
                                        </p>
                                    </div>
                                </div>
                                <div class="question">
                                    <p>Gebruikt u anticonceptiemiddelen?</p>
                                    <div class="checkboxes">
                                        <div class="question-answer">
                                            <input class="radio" type="radio" value="1" name="anticonceptiemiddel"
                                                <?= PatroonModel::isRadioChecked($antwoorden['anticonceptiemiddel'] ?? null, 1) ?>>
                                            <label>Ja</label>
                                            <textarea rows="1" cols="25" class="checkfield" type="text"
                                                placeholder="welke?"
                                                name="anticonceptiemiddel_welke"><?= isset($antwoorden['anticonceptiemiddel_welke']) ? htmlspecialchars($antwoorden['anticonceptiemiddel_welke']) : '' ?></textarea>
                                        </div>
                                        <p>
                                            <input type="radio" value="0" name="anticonceptiemiddel"
                                                <?= PatroonModel::isRadioChecked($antwoorden['anticonceptiemiddel'] ?? null, 0) ?>>
                                            <label>Nee</label>
                                        </p>
                                    </div>
                                </div>
                                <div class="question">
                                    <p>- Heeft u problemen bij het gebruik van anticonceptie-middelen?</p>
                                    <div class="checkboxes">
                                        <p>
                                            <input type="radio" value="1" name="anticonceptiemiddel_problemen"
                                                <?= PatroonModel::isRadioChecked($antwoorden['anticonceptiemiddel_problemen'] ?? null, 1) ?>>
                                            <label>Ja</label>
                                        </p>
                                        <p>
                                            <input type="radio" value="0" name="anticonceptiemiddel_problemen"
                                                <?= PatroonModel::isRadioChecked($antwoorden['anticonceptiemiddel_problemen'] ?? null, 0) ?>>
                                            <label>Nee</label>
                                        </p>
                                    </div>
                                </div>
                                <div class="question">
                                    <p>Wat is uw seksuele gerichtheid?</p>
                                    <div class="observation">
                                        <div class="question">
                                            <div class="observe"><input type="checkbox"
                                                    <?= PatroonModel::isChecked($antwoorden['seksuele_gerichtheid'], 0) ?>
                                                    name="gerichtheid1">
                                                <p>Heteroseksueel</p>
                                            </div>
                                        </div>
                                        <div class="question">
                                            <div class="observe"><input type="checkbox"
                                                    <?= PatroonModel::isChecked($antwoorden['seksuele_gerichtheid'], 1) ?>
                                                    name="gerichtheid2">
                                                <p>Biseksueel</p>
                                            </div>
                                        </div>
                                        <div class="question">
                                            <div class="observe"><input type="checkbox"
                                                    <?= PatroonModel::isChecked($antwoorden['seksuele_gerichtheid'], 2) ?>
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
                                                <?= PatroonModel::isRadioChecked($antwoorden['seksuele_gerichtheid_problemen'] ?? null, 1) ?>>
                                            <label>Ja</label>
                                        </p>
                                        <p>
                                            <input type="radio" value="0" name="seksuele_gerichtheid_problemen"
                                                <?= PatroonModel::isRadioChecked($antwoorden['seksuele_gerichtheid_problemen'] ?? null, 0) ?>>
                                            <label>Nee</label>
                                        </p>
                                    </div>
                                </div>
                                <div class="question">
                                    <p>Heeft u last (gehad) van seksueel overdraagbare aandoeningen (soa)?</p>
                                    <div class="checkboxes">
                                        <div class="question-answer">
                                            <input class="radio" type="radio" value="1" name="soa"
                                                <?= PatroonModel::isRadioChecked($antwoorden['soa'] ?? null, 1) ?>>
                                            <label>Ja</label>
                                            <textarea rows="1" cols="25" class="checkfield" type="text"
                                                placeholder="en wel?"
                                                name="soa_welke"><?= isset($antwoorden['soa_welke']) ? htmlspecialchars($antwoorden['soa_welke']) : '' ?></textarea>
                                        </div>
                                        <p>
                                            <input type="radio" value="0" name="soa" <?= PatroonModel::isRadioChecked($antwoorden['soa'] ?? null, 0) ?>>
                                            <label>Nee</label>
                                        </p>
                                    </div>
                                </div>

                                <div class="observation">
                                    <h2>Verpleegkundige observatie bij dit patroon</h2>
                                    <div class="question">
                                        <div class="observe"><input type="checkbox" <?= PatroonModel::isChecked($antwoorden['observatie'], 0) ?> name="observatie1">
                                            <p>Gewijzigde seksuele gewoonten</p>
                                        </div>
                                    </div>
                                    <div class="question">
                                        <div class="observe"><input type="checkbox" <?= PatroonModel::isChecked($antwoorden['observatie'], 1) ?> name="observatie2">
                                            <p>Seksueel disfunctioneren</p>
                                        </div>
                                    </div>
                                    <div class="question">
                                        <div class="observe"><input type="checkbox" <?= PatroonModel::isChecked($antwoorden['observatie'], 2) ?> name="observatie3">
                                            <p>Verkrachtingssyndroom gecompliceerde vorm</p>
                                        </div>
                                    </div>
                                    <div class="question">
                                        <div class="observe"><input type="checkbox" <?= PatroonModel::isChecked($antwoorden['observatie'], 3) ?> name="observatie4">
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
    <script src="../../assets/js/form-autosave.js"></script>
</body>

</html>
