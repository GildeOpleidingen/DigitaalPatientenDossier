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
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
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
                                                <?= (isset($antwoorden['verandering_seksuele_beleving']) && $antwoorden['verandering_seksuele_beleving'] == '1') ? "checked" : "" ?>>
                                            <label>Ja</label>
                                            <textarea rows="1" cols="25" class="checkfield" type="text" placeholder="door?"
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
                                            <input class="radio" type="radio" value="1" name="anticonceptiemiddel"
                                                <?= (isset($antwoorden['anticonceptiemiddel']) && $antwoorden['anticonceptiemiddel'] == '1') ? "checked" : "" ?>>
                                            <label>Ja</label>
                                            <textarea rows="1" cols="25" class="checkfield" type="text"
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
                                            <input class="radio" type="radio" value="1" name="soa"
                                                <?= (isset($antwoorden['soa']) && $antwoorden['soa'] == '1') ? "checked" : "" ?>>
                                            <label>Ja</label>
                                            <textarea rows="1" cols="25" class="checkfield" type="text"
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
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz"
        crossorigin="anonymous"></script>
    <script src="../../assets/js/form-autosave.js"></script>
</body>

</html>
