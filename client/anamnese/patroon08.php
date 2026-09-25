<?php
include_once '../../models/autoload.php';
Auth::requireLogin();

$Main = new Main();

$clientId = $_SESSION['clientId'];
$antwoorden = $Main->getAnswers($clientId, 8);

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['navbutton'])) {

    PatroonModel::saveAnswers(
        (int)$_SESSION['clientId'],
        (int)$_SESSION['loggedin_id'],
        8,
        $_POST
    );

    // Navigation
    switch ($_POST['navbutton']) {
        case 'next':
            header("Location: patroon09.php");
            exit;
        case 'prev':
            header("Location: patroon07.php");
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

<body>
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
                        <div class="h4 text-primary">8. Rollen- en relatiepatroon</div>
                        <div class="form">
                            <div class="questionnaire">
                                <div class="question">
                                    <p>Bent u getrouwd/samenwonend?</p>
                                    <div class="checkboxes">
                                        <p>
                                            <input type="radio" name="getrouwd_samenwonend" value="1" <?= (isset($antwoorden['getrouwd_samenwonend']) && $antwoorden['getrouwd_samenwonend'] == '1') ? "checked" : "" ?>>
                                            <label>Ja</label>
                                        </p>
                                        <p>
                                            <input type="radio" name="getrouwd_samenwonend" value="0" <?= (!isset($antwoorden['getrouwd_samenwonend']) || $antwoorden['getrouwd_samenwonend'] == '0') ? "checked" : "" ?>>
                                            <label>Nee</label>
                                        </p>
                                    </div>
                                </div>
                                <div class="question">
                                    <p>- Heeft u kinderen?</p>
                                    <div class="checkboxes">
                                        <p>
                                            <input type="radio" name="kinderen" value="1" <?= (isset($antwoorden['kinderen']) && $antwoorden['kinderen'] == '1') ? "checked" : "" ?>>
                                            <label>Ja</label>
                                        </p>
                                        <p>
                                            <input type="radio" name="kinderen" value="0" <?= (!isset($antwoorden['kinderen']) || $antwoorden['kinderen'] == '0') ? "checked" : "" ?>>
                                            <label>Nee</label>
                                        </p>
                                    </div>
                                </div>
                                <div class="question">
                                    <p>- Bent u tevreden over uw thuissituatie?</p>
                                    <div class="checkboxes">
                                        <p>
                                            <input type="radio" name="tevreden_thuissituatie" value="1" <?= (isset($antwoorden['tevreden_thuissituatie']) && $antwoorden['tevreden_thuissituatie'] == '1') ? "checked" : "" ?>>
                                            <label>Ja</label>
                                        </p>
                                        <p>
                                            <input type="radio" name="tevreden_thuissituatie" value="0" <?= (!isset($antwoorden['tevreden_thuissituatie']) || $antwoorden['tevreden_thuissituatie'] == '0') ? "checked" : "" ?>>
                                            <label>Nee</label>
                                        </p>
                                    </div>
                                </div>
                                <div class="question">
                                    <p>Heeft u een vrienden-/familiekring waar u steun aan heeft?</p>
                                    <div class="checkboxes">
                                        <p>
                                            <input type="radio" name="steun_vrienden_familie" value="1" <?= (isset($antwoorden['steun_vrienden_familie']) && $antwoorden['steun_vrienden_familie'] == '1') ? "checked" : "" ?>>
                                            <label>Ja</label>
                                        </p>
                                        <p>
                                            <input type="radio" name="steun_vrienden_familie" value="0" <?= (!isset($antwoorden['steun_vrienden_familie']) || $antwoorden['steun_vrienden_familie'] == '0') ? "checked" : "" ?>>
                                            <label>Nee</label>
                                        </p>
                                    </div>
                                </div>
                                <div class="question">
                                    <p>Wat is uw beroep of huidige bron van inkomsten?</p><textarea rows="1" cols="25" type="text" name="inkomstenbron"><?= isset($antwoorden['inkomstenbron']) ? $antwoorden['inkomstenbron'] : '' ?></textarea>
                                </div>
                                <div class="question">
                                    <p>- Is er de afgelopen tijd een verandering geweest in uw financiële situatie?</p>
                                    <div class="checkboxes">
                                        <div class="question-answer">
                                            <input class="radio" type="radio" name="verandering_fin_sit_vroeger" value="1" <?= (isset($antwoorden['verandering_fin_sit_vroeger']) && $antwoorden['verandering_fin_sit_vroeger'] == '1') ? "checked" : "" ?>>
                                            <label>Ja</label>
                                            <textarea rows="1" cols="25" class="checkfield" type="text" placeholder="welke?" name="verandering_fin_sit_vroeger_welke"><?= isset($antwoorden['verandering_fin_sit_vroeger_welke']) ? $antwoorden['verandering_fin_sit_vroeger_welke'] : '' ?></textarea>
                                        </div>
                                        <p>
                                            <input type="radio" name="verandering_fin_sit_vroeger" value="0" <?= (!isset($antwoorden['verandering_fin_sit_vroeger']) || $antwoorden['verandering_fin_sit_vroeger'] == '0') ? "checked" : "" ?>>
                                            <label>Nee</label>
                                        </p>
                                    </div>
                                </div>
                                <div class="question">
                                    <p>- Verwacht u in de nabije toekomst een verandering in uw financiële situatie?</p>
                                    <div class="checkboxes">
                                        <div class="question-answer">
                                            <input class="radio" type="radio" name="verandering_fin_sit_toekomst" value="1" <?= (isset($antwoorden['verandering_fin_sit_toekomst']) && $antwoorden['verandering_fin_sit_toekomst'] == '1') ? "checked" : "" ?>>
                                            <label>Ja</label>
                                            <textarea rows="1" cols="25" class="checkfield" type="text" placeholder="welke?" name="verandering_fin_sit_toekomst_welke"><?= isset($antwoorden['verandering_fin_sit_toekomst_welke']) ? $antwoorden['verandering_fin_sit_toekomst_welke'] : '' ?></textarea>
                                        </div>
                                        <p>
                                            <input type="radio" name="verandering_fin_sit_toekomst" value="0" <?= (!isset($antwoorden['verandering_fin_sit_toekomst']) || $antwoorden['verandering_fin_sit_toekomst'] == '0') ? "checked" : "" ?>>
                                            <label>Nee</label>
                                        </p>
                                    </div>
                                </div>
                                <div class="question">
                                    <p>Wat is uw opleiding?</p><textarea rows="1" cols="25" type="text" name="opleiding"><?= isset($antwoorden['opleiding']) ? $antwoorden['opleiding'] : '' ?></textarea>
                                </div>
                                <div class="question">
                                    <p>Is er de laatste tijd verandering gekomen in uw sociale contacten?</p>
                                    <div class="checkboxes">
                                        <div class="question-answer">
                                            <input class="radio" type="radio" name="verandering_sociale_contacten" value="1" <?= (isset($antwoorden['verandering_sociale_contacten']) && $antwoorden['verandering_sociale_contacten'] == '1') ? "checked" : "" ?>>
                                            <label>Ja</label>
                                            <textarea rows="1" cols="25" class="checkfield" type="text" placeholder="welke?" name="verandering_sociale_contacten_welke"><?= isset($antwoorden['verandering_sociale_contacten_welke']) ? $antwoorden['verandering_sociale_contacten_welke'] : '' ?></textarea>
                                        </div>
                                        <p>
                                            <input type="radio" name="verandering_sociale_contacten" value="0" <?= (!isset($antwoorden['verandering_sociale_contacten']) || $antwoorden['verandering_sociale_contacten'] == '0') ? "checked" : "" ?> >
                                            <label>Nee</label>
                                        </p>
                                    </div>
                                </div>
                                <div class="question">
                                    <p>Komt u uit een groot gezin?</p>
                                    <div class="checkboxes">
                                        <p>
                                            <input type="radio" name="groot_gezin" value="1" <?= (isset($antwoorden['groot_gezin']) && $antwoorden['groot_gezin'] == '1') ? "checked" : "" ?>>
                                            <label>Ja</label>
                                        </p>
                                        <p>
                                            <input type="radio" name="groot_gezin" value="0" <?= (!isset($antwoorden['groot_gezin']) || $antwoorden['groot_gezin'] == '0') ? "checked" : "" ?>>
                                            <label>Nee</label>
                                        </p>
                                    </div>
                                </div>
                                <div class="question">
                                    <p>- Wat was u plaats in dat gezin?</p><textarea rows="1" cols="25" type="text" name="plaats_in_gezin"><?= isset($antwoorden['plaats_in_gezin']) ? $antwoorden['plaats_in_gezin'] : '' ?></textarea>
                                </div>
                                <div class="question">
                                    <p>- Hoe verliepen de onderlinge contacten?</p><textarea rows="1" cols="25" type="text" name="onderlinge_contacten_gezin"><?= isset($antwoorden['onderlinge_contacten_gezin']) ? $antwoorden['onderlinge_contacten_gezin'] : '' ?></textarea>
                                </div>
                                <div class="question">
                                    <p>- Was er sprake van agressie in dat gezin?</p>
                                    <div class="checkboxes">
                                        <p>
                                            <input type="radio" name="agressie_gezin" value="1" <?= (isset($antwoorden['agressie_gezin']) && $antwoorden['agressie_gezin'] == '1') ? "checked" : "" ?> >
                                            <label>Ja</label>
                                        </p>
                                        <p>
                                            <input type="radio" name="agressie_gezin" value="0" <?= (!isset($antwoorden['agressie_gezin']) || $antwoorden['agressie_gezin'] == '0') ? "checked" : "" ?> >
                                            <label>Nee</label>
                                        </p>
                                    </div>
                                </div>
                                <div class="question">
                                    <p>Bent u lid van verenigingen?</p>
                                    <div class="checkboxes">
                                        <div class="question-answer">
                                            <input class="radio" type="radio" name="verenigingslid" value="1" <?= (isset($antwoorden['verenigingslid']) && $antwoorden['verenigingslid'] == '1') ? "checked" : "" ?>>
                                            <label>Ja</label>
                                            <textarea rows="1" cols="25" class="checkfield" type="text" placeholder="welke?" name="vereniging_welke"><?= isset($antwoorden['vereniging_welke']) ? $antwoorden['vereniging_welke'] : '' ?></textarea>
                                        </div>
                                        <p>
                                            <input type="radio" name="verenigingslid" value="0" <?= (!isset($antwoorden['verenigingslid']) || $antwoorden['verenigingslid'] == '0') ? "checked" : "" ?>>
                                            <label>Nee</label>
                                        </p>
                                    </div>
                                </div>
                                <div class="question">
                                    <p>- Hoe verliepen de contacten met derden (collega's, kennissen, verenigingsgenoten)?</p><textarea rows="1" cols="25" type="text" name="contact_met_derden"><?= isset($antwoorden['contact_met_derden']) ? $antwoorden['contact_met_derden'] : '' ?></textarea>
                                </div>
                                <div class="question">
                                    <p>Heeft u de laatst tijd een verlies geleden (werk, personen, enzovoort)?</p>
                                    <div class="checkboxes">
                                        <div class="question-answer">
                                            <input class="radio" type="radio" name="verlies_geleden" value="1" <?= (isset($antwoorden['verlies_geleden']) && $antwoorden['verlies_geleden'] == '1') ? "checked" : "" ?>>
                                            <label>Ja</label>
                                            <textarea rows="1" cols="25" class="checkfield" type="text" placeholder="en wel?" name="verlies_geleden_welke"><?= isset($antwoorden['verlies_geleden_welke']) ? $antwoorden['verlies_geleden_welke'] : '' ?></textarea>
                                        </div>
                                        <p>
                                            <input type="radio" name="verlies_geleden" value="0" <?= (!isset($antwoorden['verlies_geleden']) || $antwoorden['verlies_geleden'] == '0') ? "checked" : "" ?>>
                                            <label>Nee</label>
                                        </p>
                                    </div>
                                </div>

                                <div class="observation">
                                    <h2>Verpleegkundige observatie bij dit patroon</h2>
                                    <div class="question">
                                        <div class="observe"><input type="checkbox" <?= PatroonModel::isChecked($antwoorden['observatie'], 0) ?> name="observatie1">
                                            <p>Verstoorde verbale communicatie (afwijkende groei en ontwikkeling in communicatieve vaardigheden)</p>
                                        </div>
                                    </div>
                                    <div class="question">
                                        <div class="observe"><input type="checkbox" <?= PatroonModel::isChecked($antwoorden['observatie'], 1) ?> name="observatie2">
                                            <p>Anticiperende rouw</p>
                                        </div>
                                    </div>
                                    <div class="question">
                                        <div class="observe"><input type="checkbox" <?= PatroonModel::isChecked($antwoorden['observatie'], 2) ?> name="observatie3">
                                            <p>Disfunctionele rouw</p>
                                        </div>
                                    </div>
                                    <div class="question">
                                        <div class="observe"><input type="checkbox" <?= PatroonModel::isChecked($antwoorden['observatie'], 3) ?> name="observatie4">
                                            <p>Gewijzigde gezinsprocessen</p>
                                        </div>
                                    </div>
                                    <div class="question">
                                        <div class="observe"><input type="checkbox" <?= PatroonModel::isChecked($antwoorden['observatie'], 4) ?> name="observatie5">
                                            <p>(Dreigend) ouderschapstekort</p>
                                        </div>
                                    </div>
                                    <div class="question">
                                        <div class="observe"><input type="checkbox" <?= PatroonModel::isChecked($antwoorden['observatie'], 5) ?> name="observatie6">
                                            <p>Ouderrolconflict</p>
                                        </div>
                                    </div>
                                    <div class="question">
                                        <div class="observe"><input type="checkbox" <?= PatroonModel::isChecked($antwoorden['observatie'], 6) ?> name="observatie7">
                                            <p>Inadequate sociale interacties</p>
                                        </div>
                                    </div>
                                    <div class="question">
                                        <div class="observe"><input type="checkbox" <?= PatroonModel::isChecked($antwoorden['observatie'], 7) ?> name="observatie8">
                                            <p>Afwijkende groei en ontikkeling in sociale vaardigheden</p>
                                        </div>
                                    </div>
                                    <div class="question">
                                        <div class="observe"><input type="checkbox" <?= PatroonModel::isChecked($antwoorden['observatie'], 8) ?> name="observatie9">
                                            <p>Sociaal isolement</p>
                                        </div>
                                    </div>
                                    <div class="question">
                                        <div class="observe"><input type="checkbox" <?= PatroonModel::isChecked($antwoorden['observatie'], 9) ?> name="observatie10">
                                            <p>Verstoorde rolvervulling</p>
                                        </div>
                                    </div>
                                    <div class="question">
                                        <div class="observe"><input type="checkbox" <?= PatroonModel::isChecked($antwoorden['observatie'], 10) ?> name="observatie11">
                                            <p>Onopgelost onafhankelijkheids-/afhankelijkheidsconflict</p>
                                        </div>
                                    </div>
                                    <div class="question">
                                        <div class="observe"><input type="checkbox" <?= PatroonModel::isChecked($antwoorden['observatie'], 11) ?> name="observatie12">
                                            <p>Sociale afwijzing</p>
                                        </div>
                                    </div>
                                    <div class="question">
                                        <div class="observe"><input type="checkbox" <?= PatroonModel::isChecked($antwoorden['observatie'], 12) ?> name="observatie13">
                                            <p>(Dreigende) overbelasting van de mantelzorg)</p>
                                        </div>
                                    </div>
                                    <div class="question">
                                        <div class="observe"><input type="checkbox" <?= PatroonModel::isChecked($antwoorden['observatie'], 13) ?> name="observatie14">
                                            <p>Mantelzorgtekort</p>
                                        </div>
                                    </div>
                                    <div class="question">
                                        <div class="observe"><input type="checkbox" <?= PatroonModel::isChecked($antwoorden['observatie'], 14) ?> name="observatie15">
                                            <p>Dreigend geweld:</p>
                                        </div>
                                    </div>
                                    <div class="question">
                                        <div class="observe"><input type="checkbox" <?= PatroonModel::isChecked($antwoorden['observatie'], 15) ?> name="observatie16">
                                            <p>gericht op andere</p>
                                        </div>
                                    </div>
                                    <div class="question">
                                        <div class="observe"><input type="checkbox" <?= PatroonModel::isChecked($antwoorden['observatie'], 16) ?> name="observatie17">
                                            <p>gericht op voorwerpen (meubilair, enzovoort)</p>
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
