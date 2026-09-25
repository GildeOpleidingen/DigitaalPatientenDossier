<?php
include_once '../../models/autoload.php';
Auth::requireLogin();

$Main = new Main();

$clientId = $_SESSION['clientId'];
$antwoorden = $Main->getAnswers($clientId, 11);

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['navbutton'])) {

    PatroonModel::saveAnswers(
        (int)$_SESSION['clientId'],
        (int)$_SESSION['loggedin_id'],
        11,
        $_POST
    );

    // Navigation
    switch ($_POST['navbutton']) {
        case 'next':
            header("Location: patroon01.php");
            exit;
        case 'prev':
            header("Location: patroon10.php");
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
                        <div class="h4 text-primary">11. Stressverwerkingspatroon (probleemhantering)</div>
                        <div class="form">
                            <div class="questionnaire">
                                <div class="question">
                                    <p>Bent u gelovig?</p>
                                    <div class="checkboxes">
                                        <div class="question-answer">
                                            <input class="radio" type="radio" value="1" name="gelovig" <?= (isset($antwoorden['gelovig']) && $antwoorden['gelovig'] == '1') ? "checked" : "" ?>>
                                            <label>Ja</label>
                                            <div class="checkfield">
                                                <div class="question">
                                                    <div class="observe"><input type="checkbox" <?= PatroonModel::isChecked($antwoorden['geloof_welk'], 0) ?> name="geloof1">
                                                        <p>R-K</p>
                                                    </div>
                                                </div>
                                                <div class="question">
                                                    <div class="observe"><input type="checkbox" <?= PatroonModel::isChecked($antwoorden['geloof_welk'], 1) ?> name="geloof2">
                                                        <p>Nederlands hervormd</p>
                                                    </div>
                                                </div>
                                                <div class="question">
                                                    <div class="observe"><input type="checkbox" <?= PatroonModel::isChecked($antwoorden['geloof_welk'], 2) ?> name="geloof3">
                                                        <p>Gereformeerd</p>
                                                    </div>
                                                </div>
                                                <div class="question">
                                                    <div class="observe"><input type="checkbox" <?= PatroonModel::isChecked($antwoorden['geloof_welk'], 3) ?> name="geloof4">
                                                        <p>Moslim</p>
                                                    </div>
                                                </div>
                                                <div class="question">
                                                    <div class="observe"><input type="checkbox" <?= PatroonModel::isChecked($antwoorden['geloof_welk'], 4) ?> name="geloof5">
                                                        <p>Joods</p>
                                                    </div>
                                                </div>
                                                <div class="question">
                                                    <div class="observe"><input type="checkbox" <?= PatroonModel::isChecked($antwoorden['geloof_welk'], 5) ?> name="geloof6">
                                                        <p>Anders, namelijk:</p>
                                                    </div><textarea rows="1" cols="25" type="text" name="geloof_anders"><?= isset($antwoorden['geloof_anders']) ? $antwoorden['geloof_anders'] : '' ?></textarea>
                                                </div>
                                            </div>
                                        </div>
                                        <p>
                                            <input type="radio" value="0" name="gelovig" <?= (!isset($antwoorden['gelovig']) || $antwoorden['gelovig'] == '0') ? "checked" : "" ?>>
                                            <label>Nee</label>
                                        </p>
                                    </div>
                                </div>
                                <div class="question">
                                    <p>- Heeft u behoefte aan religieuze activiteiten?</p>
                                    <div class="checkboxes">
                                        <p>
                                            <input type="radio" value="1" name="behoefte_religieuze_activiteit" <?= (isset($antwoorden['behoefte_religieuze_activiteit']) && $antwoorden['behoefte_religieuze_activiteit'] =='1') ? "checked" : "" ?>>
                                            <label>Ja</label>
                                        </p>
                                        <p>
                                            <input type="radio" value="0" name="behoefte_religieuze_activiteit" <?= (!isset($antwoorden['behoefte_religieuze_activiteit']) || $antwoorden['behoefte_religieuze_activiteit'] == '0') ? "checked" : "" ?>>
                                            <label>Nee</label>
                                        </p>
                                    </div>
                                </div>
                                <div class="question">
                                    <p>- Zijn er gebruiken ten aanzien van uw geloofsovertuiging waar rekening mee gehouden moet worden?</p>
                                    <div class="checkboxes">
                                        <div class="question-answer">
                                            <input class="radio" type="radio" value="1" name="gebruiken_tav_geloofsovertuiging" <?= (isset($antwoorden['gebruiken_tav_geloofsovertuiging']) && $antwoorden['gebruiken_tav_geloofsovertuiging'] == '1') ? "checked" : "" ?>>
                                            <label>Ja</label>
                                            <textarea rows="1" cols="25" class="checkfield" type="text" placeholder="welke?" name="gebruiken_tav_geloofsovertuiging_welke"><?= isset($antwoorden['gebruiken_tav_geloofsovertuiging_welke']) ? $antwoorden['gebruiken_tav_geloofsovertuiging_welke'] : '' ?></textarea>
                                        </div>
                                        <p>
                                            <input type="radio" value="0" name="gebruiken_tav_geloofsovertuiging" <?= (!isset($antwoorden['gebruiken_tav_geloofsovertuiging']) || $antwoorden['gebruiken_tav_geloofsovertuiging'] == '0') ? "checked" : "" ?>>
                                            <label>Nee</label>
                                        </p>
                                    </div>
                                </div>
                                <div class="question">
                                    <p>Ja, wanneer?</p><textarea rows="1" cols="25" type="text" name="gebruiken_tav_geloofsovertuiging_wanneer"><?= isset($antwoorden['gebruiken_tav_geloofsovertuiging_wanneer']) ? $antwoorden['gebruiken_tav_geloofsovertuiging_wanneer'] : '' ?></textarea>
                                </div>
                                <div class="question">
                                    <p>Komen uw waarden en normen overeen met maatschappelijke waarden en normen?</p>
                                    <div class="checkboxes">
                                        <p>
                                            <input type="radio" value="1" name="overeenkomst_waarden_normen" <?= (isset($antwoorden['overeenkomst_waarden_normen']) && $antwoorden['overeenkomst_waarden_normen'] == '1') ? "checked" : "" ?>>
                                            <label>Ja</label>
                                        </p>
                                        <p>
                                            <input type="radio" value="0" name="overeenkomst_waarden_normen" <?= (!isset($antwoorden['overeenkomst_waarden_normen']) || $antwoorden['overeenkomst_waarden_normen'] == '0') ? "checked" : "" ?>>
                                            <label>Nee</label>
                                        </p>
                                    </div>
                                </div>
                                <div class="question">
                                    <p>Wat is uw ethische achtergrond?</p><textarea rows="1" cols="25" type="text" name="etnische_achtergrond"><?= isset($antwoorden['etnische_achtergrond']) ? $antwoorden['etnische_achtergrond'] : '' ?></textarea>
                                </div>
                                <div class="question">
                                    <p>- Zijn er gebruiken met betrekking tot uw ethische achtergrond waar rekening mee gehouden moet worden?</p>
                                    <div class="checkboxes">
                                        <div class="question-answer">
                                            <input class="radio" type="radio" value="1" name="gebruiken_mbt_etnische_achtergrond" <?= (isset($antwoorden['gebruiken_mbt_etnische_achtergrond']) && $antwoorden['gebruiken_mbt_etnische_achtergrond'] =='1') ? "checked" : "" ?>>
                                            <label>Ja</label>
                                            <textarea rows="1" cols="25" class="checkfield" type="text" placeholder="welke?" name="gebruiken_mbt_etnische_achtergrond_welke"><?= isset($antwoorden['gebruiken_mbt_etnische_achtergrond_welke']) ? $antwoorden['gebruiken_mbt_etnische_achtergrond_welke'] : '' ?></textarea>
                                        </div>
                                        <p>
                                            <input type="radio" value="0" name="gebruiken_mbt_etnische_achtergrond" <?= (!isset($antwoorden['gebruiken_mbt_etnische_achtergrond']) || $antwoorden['gebruiken_mbt_etnische_achtergrond'] == '0') ? "checked" : "" ?>>
                                            <label>Nee</label>
                                        </p>
                                    </div>
                                </div>
                                <div class="question">
                                    <p>Ja, wanneer?</p><textarea rows="1" cols="25" type="text" name="gebruiken_mbt_etnische_achtergrond_wanneer"><?= isset($antwoorden['gebruiken_mbt_etnische_achtergrond']) ? $antwoorden['gebruiken_mbt_etnische_achtergrond_wanneer'] : '' ?></textarea>
                                </div>

                                <div class="observation">
                                    <h2>Verpleegkundige observatie bij dit patroon</h2>
                                    <div class="question">
                                        <div class="observe"><input type="checkbox" <?= PatroonModel::isChecked($antwoorden['observatie'], 0) ?> name="observatie1">
                                            <p>Geestelijke nood</p>
                                        </div>
                                    </div>
                                    <div class="question">
                                        <div class="observe"><input type="checkbox" <?= PatroonModel::isChecked($antwoorden['observatie'], 1) ?> name="observatie2">
                                            <p>Verandering in waarden en normen</p>
                                        </div>
                                    </div>
                                    <div class="question">
                                        <div class="observe"><input type="checkbox" <?= PatroonModel::isChecked($antwoorden['observatie'], 2) ?> name="observatie3">
                                            <p>Verandering in rolopvatting met betrekking tot ethische achtergrond</p>
                                        </div>
                                    </div>
                                    <div class="question">
                                        <div class="observe"><input type="checkbox" <?= PatroonModel::isChecked($antwoorden['observatie'], 3) ?> name="observatie4">
                                            <p>Verandering in rolinvulling met betrekking tot ethische achtergrond</p>
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
