<?php
include_once '../../models/autoload.php';
Auth::requireLogin();

$Main = new Main();

$clientId = $_SESSION['clientId'];
$antwoorden = $Main->getAnswers($clientId, 7);

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['navbutton'])) {

    PatroonModel::saveAnswers(
        (int)$_SESSION['clientId'],
        (int)$_SESSION['loggedin_id'],
        7,
        $_POST
    );

    // Navigation
    switch ($_POST['navbutton']) {
        case 'next':
            header("Location: patroon08.php");
            exit;
        case 'prev':
            header("Location: patroon06.php");
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
                        <div class="h4 text-primary">7. Zelfbelevingspatroon</div>
                        <div class="form">
                            <div class="questionnaire">
                                <div class="question">
                                    <p>Kunt u uzelf, in het kort, beschrijven?</p><textarea rows="1" cols="25" type="text" name="zelfbeschrijving"><?= isset($antwoorden['zelfbeschrijving']) ? $antwoorden['zelfbeschrijving'] : '' ?></textarea>
                                </div>
                                <div class="question">
                                    <p>Kunt u voor uzelf opkomen?</p>
                                    <div class="checkboxes">
                                        <p>
                                            <input type="radio" value="1" name="opkomen_voor_uzelf" <?= (isset($antwoorden['opkomen_voor_uzelf']) && $antwoorden['opkomen_voor_uzelf'] == '1') ? "checked" : "" ?>>
                                            <label>Ja</label>
                                        </p>
                                        <p>
                                            <input type="radio" value="0" name="opkomen_voor_uzelf" <?= (!isset($antwoorden['opkomen_voor_uzelf']) || $antwoorden['opkomen_voor_uzelf'] == '0') ? "checked" : "" ?>>
                                            <label>Nee</label>
                                        </p>
                                    </div>
                                </div>
                                <div class="question">
                                    <p>Waar blijkt dat uit?</p><textarea rows="1" cols="25" type="text" name="wel_niet_opkomen_blijktuit"><?= isset($antwoorden['wel_niet_opkomen_blijktuit']) ? $antwoorden['wel_niet_opkomen_blijktuit'] : '' ?></textarea>
                                </div>
                                <div class="question">
                                    <p>Is uw stemming de laatste tijd veranderd?</p>
                                    <div class="checkboxes">
                                        <div class="question-answer">
                                            <input class="radio" type="radio" value="1" name="verandering_stemming" <?= (isset($antwoorden['verandering_stemming']) && $antwoorden['verandering_stemming'] == '1') ? "checked" : "" ?>>
                                            <label>Ja</label>
                                            <textarea rows="1" cols="25" class="checkfield" type="text" placeholder="en wel?" name="verandering_stemming_welke"> <?= isset($antwoorden['verandering_stemming_welke']) ? $antwoorden['verandering_stemming_welke'] : '' ?> </textarea>
                                        </div>
                                        <p>
                                            <input type="radio" value="0" name="verandering_stemming" <?= (!isset($antwoorden['verandering_stemming']) || $antwoorden['verandering_stemming'] == '0') ? "checked" : "" ?>>
                                            <label>Nee</label>
                                        </p>
                                    </div>
                                </div>

                                <div class="question">
                                    <p>- Hoe voelt u zich op dit moment?</p> 
                                    <div class="observation">
                                        <div class="question"><div class="observe"><input type="checkbox" <?= PatroonModel::isChecked($antwoorden['gevoel_op_dit_moment'], 0) ?> name="gevoel1"><p>Neerslachtig</p></div></div>
                                        <div class="question"><div class="observe"><input type="checkbox" <?= PatroonModel::isChecked($antwoorden['gevoel_op_dit_moment'], 1) ?> name="gevoel2"><p>Wanhopig</p></div></div>
                                        <div class="question"><div class="observe"><input type="checkbox" <?= PatroonModel::isChecked($antwoorden['gevoel_op_dit_moment'], 2) ?> name="gevoel3"><p>Machteloos</p></div></div>
                                        <div class="question"><div class="observe"><input type="checkbox" <?= PatroonModel::isChecked($antwoorden['gevoel_op_dit_moment'], 3) ?> name="gevoel4"><p>Opgewekt</p></div></div>
                                        <div class="question"><div class="observe"><input type="checkbox" <?= PatroonModel::isChecked($antwoorden['gevoel_op_dit_moment'], 4) ?> name="gevoel5"><p>Somber</p></div></div>
                                        <div class="question"><div class="observe"><input type="checkbox" <?= PatroonModel::isChecked($antwoorden['gevoel_op_dit_moment'], 5) ?> name="gevoel6"><p>Eufoor</p></div></div>
                                        <div class="question"><div class="observe"><input type="checkbox" <?= PatroonModel::isChecked($antwoorden['gevoel_op_dit_moment'], 6) ?> name="gevoel7"><p>Labiel</p></div></div>
                                        <div class="question"><div class="observe"><input type="checkbox" <?= PatroonModel::isChecked($antwoorden['gevoel_op_dit_moment'], 7) ?> name="gevoel8"><p>Gespannen</p></div></div>
                                        <div class="question"><div class="observe"><input type="checkbox" <?= PatroonModel::isChecked($antwoorden['gevoel_op_dit_moment'], 8) ?> name="gevoel9"><p>Verdrietig</p></div></div>
                                        <div class="question"><div class="observe"><input type="checkbox" <?= PatroonModel::isChecked($antwoorden['gevoel_op_dit_moment'], 9) ?> name="gevoel10"><p>Anders, namelijk:</p></div><textarea  rows="1" cols="25" type="text" name="gevoel_op_dit_moment_anders"><?= $antwoorden['gevoel_op_dit_moment_anders'] ?></textarea></div>
                                    </div>
                                </div>

                                <div class="question">
                                    <p>- Is er de afgelopen tijd iets veranderd in uw concentratievermogen ten gevolgen van u stemming?</p>
                                    <div class="checkboxes">
                                        <p>
                                            <input type="radio" value="1" name="verandering_concentratie" <?= (isset($antwoorden['verandering_concentratie']) && $antwoorden['verandering_concentratie'] == '1') ? "checked" : "" ?>>
                                            <label>Ja</label>
                                        </p>
                                        <p>
                                            <input type="radio" value="0" name="verandering_concentratie" <?= (!isset($antwoorden['verandering_concentratie']) || $antwoorden['verandering_concentratie'] == '0') ? "checked" : "" ?>>
                                            <label>Nee</label>
                                        </p>
                                    </div>
                                </div>
                                <div class="question">
                                    <p>- Is er de afgelopen tijd iets veranderd in uw denkpatroon ten gevolgen van u stemming?</p>
                                    <div class="checkboxes">
                                        <p>
                                            <input type="radio" value="1" name="verandering_denkpatroon" <?= (isset($antwoorden['verandering_denkpatroon']) && $antwoorden['verandering_denkpatroon'] == '1') ? "checked" : "" ?>>
                                            <label>Ja</label>
                                        </p>
                                        <p>
                                            <input type="radio" value="0" name="verandering_denkpatroon" <?= (!isset($antwoorden['verandering_denkpatroon']) || $antwoorden['verandering_denkpatroon'] == '0') ? "checked" : "" ?>>
                                            <label>Nee</label>
                                        </p>
                                    </div>
                                </div>
                                <div class="question">
                                    <p>Ervaart u uzelf nu anders dan voorheen?</p>
                                    <div class="checkboxes">
                                        <p>
                                            <input type="radio" value="1" name="ervaring_voorheen" <?= (isset($antwoorden['ervaring_voorheen']) && $antwoorden['ervaring_voorheen'] =='1') ? "checked" : "" ?>>
                                            <label>Ja</label>
                                        </p>
                                        <p>
                                            <input type="radio" value="0" name="ervaring_voorheen" <?= (!isset($antwoorden['ervaring_voorheen']) || $antwoorden['ervaring_voorheen'] == '0') ? "checked" : "" ?>>
                                            <label>Nee</label>
                                        </p>
                                    </div>
                                </div>
                                <div class="question">
                                    <p>- Zijn er veranderingen in uw uiterlijk en/of mogelijkheden waardoor u zich anders voelt?</p>
                                    <div class="checkboxes">
                                        <p>
                                            <input type="radio" value="1" name="verandering_uiterlijk" <?= (isset($antwoorden['verandering_uiterlijk']) && $antwoorden['verandering_uiterlijk'] == '1') ? "checked" : "" ?>>
                                            <label>Ja</label>
                                        </p>
                                        <p>
                                            <input type="radio" value="0" name="verandering_uiterlijk" <?= (!isset($antwoorden['verandering_uiterlijk']) | $antwoorden['verandering_uiterlijk'] == '0') ? "checked" : "" ?>>
                                            <label>Nee</label>
                                        </p>
                                    </div>
                                </div>
                                <div class="question">
                                    <p>- Voelt u (lichamelijke) sensaties?</p>
                                    <div class="checkboxes">
                                        <div class="question-answer">
                                            <input class="radio" type="radio" value="1" name="sensaties" <?= (isset($antwoorden['sensaties']) && $antwoorden['sensaties'] == '1') ? "checked" : "" ?>>
                                            <label>Ja</label>
                                            <textarea rows="1" cols="25" class="checkfield" type="text" placeholder="wat voelt u?" name="sensaties_welk_gevoel"><?= isset($antwoorden['sensaties_welk_gevoel']) ? $antwoorden['sensaties_welk_gevoel'] : '' ?></textarea>
                                        </div>
                                        <p>
                                            <input type="radio" value="0" name="sensaties" <?= (!isset($antwoorden['sensaties']) | $antwoorden['sensaties'] == '0') ? "checked" : "" ?>>
                                            <label>Nee</label>
                                        </p>
                                    </div>
                                </div>
                                <div class="question">
                                    <p>- Hoe voelt u zich momenteel?</p>
                                    <div class="observation">
                                        <div class="question">
                                            <div class="observe"><input type="checkbox" <?= PatroonModel::isChecked($antwoorden['gevoel_momenteel'], 0) ?> name="gevoelMomenteel1">
                                                <p>Sterk</p>
                                            </div>
                                        </div>
                                        <div class="question">
                                            <div class="observe"><input type="checkbox" <?= PatroonModel::isChecked($antwoorden['gevoel_momenteel'], 1) ?> name="gevoelMomenteel2">
                                                <p>Zwak</p>
                                            </div>
                                        </div>
                                        <div class="question">
                                            <div class="observe"><input type="checkbox" <?= PatroonModel::isChecked($antwoorden['gevoel_momenteel'], 2) ?> name="gevoelMomenteel3">
                                                <p>Krachteloos</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="question">
                                    <p>- Hoe staat het met uw lichamelijke energie?</p>
                                    <div class="observation">
                                        <div class="question">
                                            <div class="observe"><input type="checkbox" <?= PatroonModel::isChecked($antwoorden['lichamelijke_energie'], 0) ?> name="lichamelijkeEnergie1">
                                                <p>Genoeg</p>
                                            </div>
                                        </div>
                                        <div class="question">
                                            <div class="observe"><input type="checkbox" <?= PatroonModel::isChecked($antwoorden['lichamelijke_energie'], 1) ?> name="lichamelijkeEnergie2">
                                                <p>Te veel</p>
                                            </div>
                                        </div>
                                        <div class="question">
                                            <div class="observe"><input type="checkbox" <?= PatroonModel::isChecked($antwoorden['lichamelijke_energie'], 2) ?> name="lichamelijkeEnergie3">
                                                <p>Te weinig</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="question">
                                    <p>Zorgt u goed voor u zelf, of vindt u dat het beter kan?</p><textarea rows="1" cols="25" type="text" name="zelfverzorging"><?= isset($antwoorden['zelfverzorging']) ? $antwoorden['zelfverzorging'] : '' ?></textarea>
                                </div>

                                <div class="observation">
                                    <h2>Verpleegkundige observatie bij dit patroon</h2>
                                    <div class="question">
                                        <div class="observe"><input type="checkbox" <?= PatroonModel::isChecked($antwoorden['observatie'], 0) ?> name="observatie1">
                                            <p>Lichte angst</p>
                                        </div>
                                    </div>
                                    <div class="question">
                                        <div class="observe"><input type="checkbox" <?= PatroonModel::isChecked($antwoorden['observatie'], 1) ?> name="observatie2">
                                            <p>Matige angst</p>
                                        </div>
                                    </div>
                                    <div class="question">
                                        <div class="observe"><input type="checkbox" <?= PatroonModel::isChecked($antwoorden['observatie'], 2) ?> name="observatie3">
                                            <p>Hevige (paniek) angst</p>
                                        </div>
                                    </div>
                                    <div class="question">
                                        <div class="observe"><input type="checkbox" <?= PatroonModel::isChecked($antwoorden['observatie'], 3) ?> name="observatie4">
                                            <p>Lichte anticiperende angst</p>
                                        </div>
                                    </div>
                                    <div class="question">
                                        <div class="observe"><input type="checkbox" <?= PatroonModel::isChecked($antwoorden['observatie'], 4) ?> name="observatie5">
                                            <p>Matige anticiperende angst</p>
                                        </div>
                                    </div>
                                    <div class="question">
                                        <div class="observe"><input type="checkbox" <?= PatroonModel::isChecked($antwoorden['observatie'], 5) ?> name="observatie6">
                                            <p>Hevige anticiperende angst</p>
                                        </div>
                                    </div>
                                    <div class="question">
                                        <div class="observe"><input type="checkbox" <?= PatroonModel::isChecked($antwoorden['observatie'], 6) ?> name="observatie7">
                                            <p>Vrees</p>
                                        </div>
                                    </div>
                                    <div class="question">
                                        <div class="observe"><input type="checkbox" <?= PatroonModel::isChecked($antwoorden['observatie'], 7) ?> name="observatie8">
                                            <p>Reactieve depressie</p>
                                        </div>
                                    </div>
                                    <div class="question">
                                        <div class="observe"><input type="checkbox" <?= PatroonModel::isChecked($antwoorden['observatie'], 8) ?> name="observatie9">
                                            <p>Moedeloosheid</p>
                                        </div>
                                    </div>
                                    <div class="question">
                                        <div class="observe"><input type="checkbox" <?= PatroonModel::isChecked($antwoorden['observatie'], 9) ?> name="observatie10">
                                            <p>Identiteitsstoornis</p>
                                        </div>
                                    </div>
                                    <div class="question">
                                        <div class="observe"><input type="checkbox" <?= PatroonModel::isChecked($antwoorden['observatie'], 10) ?> name="observatie11">
                                            <p>Lichte machteloosheid</p>
                                        </div>
                                    </div>
                                    <div class="question">
                                        <div class="observe"><input type="checkbox" <?= PatroonModel::isChecked($antwoorden['observatie'], 11) ?> name="observatie12">
                                            <p>Matige machteloosheid</p>
                                        </div>
                                    </div>
                                    <div class="question">
                                        <div class="observe"><input type="checkbox" <?= PatroonModel::isChecked($antwoorden['observatie'], 12) ?> name="observatie13">
                                            <p>Ernstige machteloosheid</p>
                                        </div>
                                    </div>
                                    <div class="question">
                                        <div class="observe"><input type="checkbox" <?= PatroonModel::isChecked($antwoorden['observatie'], 13) ?> name="observatie14">
                                            <p>Geringe zelfachting</p>
                                        </div>
                                    </div>
                                    <div class="question">
                                        <div class="observe"><input type="checkbox" <?= PatroonModel::isChecked($antwoorden['observatie'], 14) ?> name="observatie15">
                                            <p>Chronisch geringe zelfachting</p>
                                        </div>
                                    </div>
                                    <div class="question">
                                        <div class="observe"><input type="checkbox" <?= PatroonModel::isChecked($antwoorden['observatie'], 15) ?> name="observatie16">
                                            <p>Reactief geringe zelfachting</p>
                                        </div>
                                    </div>
                                    <div class="question">
                                        <div class="observe"><input type="checkbox" <?= PatroonModel::isChecked($antwoorden['observatie'], 16) ?> name="observatie17">
                                            <p>Verstoord lichaamsbeeld</p>
                                        </div>
                                    </div>
                                    <div class="question">
                                        <div class="observe"><input type="checkbox" <?= PatroonModel::isChecked($antwoorden['observatie'], 17) ?> name="observatie18">
                                            <p>Hopeloosheid</p>
                                        </div>
                                    </div>
                                    <div class="question">
                                        <div class="observe"><input type="checkbox" <?= PatroonModel::isChecked($antwoorden['observatie'], 18) ?> name="observatie19">
                                            <p>Dreigende zelfverminking (automutilatie)</p>
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
