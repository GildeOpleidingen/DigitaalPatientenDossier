<?php
include_once '../../models/autoload.php';
Auth::requireLogin();
$Main = new Main();

$clientId = $_SESSION['clientId'];
$antwoorden = $Main->getAnswers($clientId, 3);

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['navbutton'])) {

    PatroonModel::saveAnswers(
        (int)$_SESSION['clientId'],
        (int)$_SESSION['loggedin_id'],
        3,
        $_POST
    );

    // Navigation
    switch ($_POST['navbutton']) {
        case 'next':
            header("Location: patroon04.php");
            exit;
        case 'prev':
            header("Location: patroon02.php");
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
            include '../../includes/n-sidebar.php';
            ?>
            <div class="mt-5 pt-5 content">
                <div class="mt-4 mb-3 bg-white p-3" style="height: 90%; overflow: auto;">
                    <p class="card-text">
                    <div class="form-content">
                        <div class="h4 text-primary">3. Uitscheidingspatroon</div>
                        <div class="form">
                            <div class="questionnaire">
                                <div class="question">
                                    <p>Heeft u problemen met ontlasting?</p>
                                    <div class="checkboxes">
                                        <div class="question-answer">
                                            <input class="radio" type="radio" value="1" name="ontlasting_probleem" <?= PatroonModel::isRadioChecked($antwoorden['ontlasting_probleem'] ?? null, 1) ?>>
                                            <label>Ja</label>
                                            <textarea rows="1" cols="25" class="checkfield" type="text" placeholder="en wel?" name="op_welke"><?= isset($antwoorden['op_welke']) ? htmlspecialchars($antwoorden['op_welke']) : '' ?></textarea>
                                        </div>
                                        <p>
                                            <input type="radio" value="0" name="ontlasting_probleem" <?= PatroonModel::isRadioChecked($antwoorden['ontlasting_probleem'] ?? null, 0) ?>>
                                            <label>Nee</label>
                                        </p>
                                    </div>
                                </div>
                                <div class="question">
                                    <p>- Wat doet u om deze problemen te bestrijden?</p><textarea rows="1" cols="25" type="text" name="ontlasting_probleem_oplossing"><?= isset($antwoorden['op_preventie']) ? htmlspecialchars($antwoorden['op_preventie']) : '' ?></textarea>
                                </div>
                                <div class="question">
                                    <p>- Gebruikt u iets om uw stoelgang te reguleren?</p>
                                    <div class="checkboxes">
                                        <div class="question-answer">
                                            <input class="radio" type="radio" value="1" name="op_medicijnen" <?= PatroonModel::isRadioChecked($antwoorden['op_medicijnen'] ?? null, 1) ?>>
                                            <label>Ja</label>
                                            <textarea rows="1" cols="25" class="checkfield" type="text" placeholder="en wel?" name="op_medicijnen_welke"><?= isset($antwoorden['op_medicijnen_welke']) ? htmlspecialchars($antwoorden['op_medicijnen_welke']) : '' ?></textarea>
                                        </div>
                                        <p>
                                            <input type="radio" value="0" name="op_medicijnen" <?= PatroonModel::isRadioChecked($antwoorden['op_medicijnen'] ?? null, 0) ?>>
                                            <label>Nee</label>
                                        </p>
                                    </div>
                                </div>
                                <div class="question">
                                    <p>Heeft u problemen ten aanzien van het urineren?</p>
                                    <div class="checkboxes">
                                        <p>
                                            <input type="radio" value="1" name="urineer_probleem" <?= PatroonModel::isRadioChecked($antwoorden['urineer_probleem'] ?? null, 1) ?>>
                                            <label>Ja</label>
                                        </p>
                                        <p>
                                            <input type="radio" value="0" name="urineer_probleem" <?= PatroonModel::isRadioChecked($antwoorden['urineer_probleem'] ?? null, 0) ?>>
                                            <label>Nee</label>
                                        </p>
                                    </div>
                                </div>
                                <div class="question">
                                    <p>- Heeft u last van incontinentie?</p>
                                    <div class="checkboxes">
                                        <p>
                                            <input type="radio" value="1" name="up_incontinentie" <?= PatroonModel::isRadioChecked($antwoorden['up_incontinentie'] ?? null, 1) ?>>
                                            <label>Ja</label>
                                        </p>
                                        <p>
                                            <input type="radio" value="0" name="up_incontinentie" <?= PatroonModel::isRadioChecked($antwoorden['up_incontinentie'] ?? null, 0) ?>>
                                            <label>Nee</label>
                                        </p>
                                    </div>
                                </div>
                                <div class="question">
                                    <p>- Bent u hiervoor in behandeling?</p>
                                    <div class="checkboxes">
                                        <div class="question-answer">
                                            <input class="radio" type="radio" value="1" name="up_incontinentie_behandeling" <?= PatroonModel::isRadioChecked($antwoorden['up_incontinentie_behandeling'] ?? null, 1) ?>>
                                            <label>Ja</label>
                                            <textarea rows="1" cols="25" class="checkfield" type="text" placeholder="en wel?" name="up_incontinentie_behandeling_welke"><?= isset($antwoorden['up_incontinentie_behandeling_welke']) ? htmlspecialchars($antwoorden['up_incontinentie_behandeling_welke']) : '' ?></textarea>
                                        </div>
                                        <p>
                                            <input type="radio" value="0" name="up_incontinentie_behandeling" <?= PatroonModel::isRadioChecked($antwoorden['up_incontinentie_behandeling'] ?? null, 0) ?>>
                                            <label>Nee</label>
                                        </p>
                                    </div>
                                </div>
                                <div class="question">
                                    <p>Hebt u last van transpiratie?</p>
                                    <div class="checkboxes">
                                        <div class="question-answer">
                                            <input class="radio" type="radio" value="1" name="transpiratie" <?= PatroonModel::isRadioChecked($antwoorden['transpiratie'] ?? null, 1) ?>>
                                            <label>Ja</label>
                                            <textarea rows="1" cols="25" class="checkfield" type="text" placeholder="en wel?" name="transpiratie_welke"><?= isset($antwoorden['transpiratie_welke']) ? htmlspecialchars($antwoorden['transpiratie_welke']) : '' ?></textarea>
                                        </div>
                                        <p>
                                            <input type="radio" value="0" name="transpiratie" <?= PatroonModel::isRadioChecked($antwoorden['transpiratie'] ?? null, 0) ?>>
                                            <label>Nee</label>
                                        </p>
                                    </div>
                                </div>

                                <div class="observation">
                                    <h2>Verpleegkundige observatie bij dit patroon</h2>
                                    <div class="question">
                                        <div class="observe"><input type="checkbox" <?= PatroonModel::isChecked($antwoorden['observatie'], 0) ?> name="observatie1">
                                            <p>Colon-obstipatie</p>
                                        </div>
                                    </div>
                                    <div class="question">
                                        <div class="observe"><input type="checkbox" <?= PatroonModel::isChecked($antwoorden['observatie'], 1) ?> name="observatie2">
                                            <p>Subjectief ervaren obstipatie</p>
                                        </div>
                                    </div>
                                    <div class="question">
                                        <div class="observe"><input type="checkbox" <?= PatroonModel::isChecked($antwoorden['observatie'], 2) ?> name="observatie3">
                                            <p>Diarree</p>
                                        </div>
                                    </div>
                                    <div class="question">
                                        <div class="observe"><input type="checkbox" <?= PatroonModel::isChecked($antwoorden['observatie'], 3) ?> name="observatie4">
                                            <p>Incontinentie van feces</p>
                                        </div>
                                    </div>
                                    <div class="question">
                                        <div class="observe"><input type="checkbox" <?= PatroonModel::isChecked($antwoorden['observatie'], 4) ?> name="observatie5">
                                            <p>Verstoorde urine-uitscheiding</p>
                                        </div>
                                    </div>
                                    <div class="question">
                                        <div class="observe"><input type="checkbox" <?= PatroonModel::isChecked($antwoorden['observatie'], 5) ?> name="observatie6">
                                            <p>Functionele urine-incontinentie</p>
                                        </div>
                                    </div>
                                    <div class="question">
                                        <div class="observe"><input type="checkbox" <?= PatroonModel::isChecked($antwoorden['observatie'], 6) ?> name="observatie7">
                                            <p>Reflex-urine-incontinentie</p>
                                        </div>
                                    </div>
                                    <div class="question">
                                        <div class="observe"><input type="checkbox" <?= PatroonModel::isChecked($antwoorden['observatie'], 7) ?> name="observatie8">
                                            <p>Stress-urine-incontinentie</p>
                                        </div>
                                    </div>
                                    <div class="question">
                                        <div class="observe"><input type="checkbox" <?= PatroonModel::isChecked($antwoorden['observatie'], 8) ?> name="observatie9">
                                            <p>Volledige urine-incontinentie</p>
                                        </div>
                                    </div>
                                    <div class="question">
                                        <div class="observe"><input type="checkbox" <?= PatroonModel::isChecked($antwoorden['observatie'], 9) ?> name="observatie10">
                                            <p>Urineretentie</p>
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
