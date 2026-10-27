<?php
include_once '../../models/autoload.php';
Auth::requireLogin();

$Main = new Main();

$clientId = $_SESSION['clientId'];
$antwoorden = $Main->getAnswers($clientId, 4);

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['navbutton'])) {

    PatroonModel::saveAnswers(
        (int)$_SESSION['clientId'],
        (int)$_SESSION['loggedin_id'],
        4,
        $_POST
    );

    // Navigation
    switch ($_POST['navbutton']) {
        case 'next':
            header("Location: patroon05.php");
            exit;
        case 'prev':
            header("Location: patroon03.php");
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
                        <div class="h4 text-primary">4. Activiteitenpatroon</div>
                        <div class="form">
                            <div class="questionnaire">
                                <!-- Zorg ervoor dat dit 0-4 is en als het niet is ingevuld null is en niet 0 -->
                                <div class="question">
                                    <p>In hoeverre bent u in staat de volgende activiteiten te doen?</p>
                                </div>
                                <div class="question">
                                    <p><i>0 - volledige zelfzorg<br>1 - gebruik van hulpmiddelen of plan<br>2 - vereist
                                            assistentie/supervisie van anderen<br>3 - Vereidst gebruik van hulpmiddelen
                                            of plan/methoden en assistentie van anderen<br>4 - is afhankelijk en/of
                                            participeert niet)</i></p>
                                </div>
                                <div class="question">
                                    <div class="observe"><input type="checkbox" name="voedingCheckbox" <?= (isset($antwoorden['voeding']) && $antwoorden['voeding'] !== '' && $antwoorden['voeding'] !== null) ? "checked" : "" ?>>
                                        <p>Voeding</p>
                                    </div><input type="number" min="0" max="4"
                                        value="<?= isset($antwoorden['voeding']) ? htmlspecialchars((string)$antwoorden['voeding']) : '' ?>"
                                        name="voeding">
                                </div>
                                <div class="question">
                                    <div class="observe"><input type="checkbox" name="aankledenCheckbox" <?= (isset($antwoorden['aankleden']) && $antwoorden['aankleden'] !== '' && $antwoorden['aankleden'] !== null) ? "checked" : "" ?>>
                                        <p>Aankleden</p>
                                    </div><input type="number" min="0" max="4"
                                        value="<?= isset($antwoorden['aankleden']) ? htmlspecialchars((string)$antwoorden['aankleden']) : '' ?>"
                                        name="aankleden">
                                </div>
                                <div class="question">
                                    <div class="observe"><input type="checkbox" name="alg_mobiliteitCheckbox" <?= (isset($antwoorden['alg_mobiliteit']) && $antwoorden['alg_mobiliteit'] !== '' && $antwoorden['alg_mobiliteit'] !== null) ? "checked" : "" ?>>
                                        <p>Algemene mobiliteit</p>
                                    </div><input type="number" min="0" max="4"
                                        value="<?= isset($antwoorden['alg_mobiliteit']) ? htmlspecialchars((string)$antwoorden['alg_mobiliteit']) : '' ?>"
                                        name="alg_mobiliteit">
                                </div>
                                <div class="question">
                                    <div class="observe"><input type="checkbox" name="kokenCheckbox" <?= (isset($antwoorden['koken']) && $antwoorden['koken'] !== '' && $antwoorden['koken'] !== null) ? "checked" : "" ?>>
                                        <p>Koken</p>
                                    </div><input type="number" min="0" max="4"
                                        value="<?= isset($antwoorden['koken']) ? htmlspecialchars((string)$antwoorden['koken']) : '' ?>"
                                        name="koken">
                                </div>
                                <div class="question">
                                    <div class="observe"><input type="checkbox" name="huishoudenCheckbox" <?= (isset($antwoorden['huishouden']) && $antwoorden['huishouden'] !== '' && $antwoorden['huishouden'] !== null) ? "checked" : "" ?>>
                                        <p>Huishouden</p>
                                    </div><input type="number" min="0" max="4"
                                        value="<?= isset($antwoorden['huishouden']) ? htmlspecialchars((string)$antwoorden['huishouden']) : '' ?>"
                                        name="huishouden">
                                </div>
                                <div class="question">
                                    <div class="observe"><input type="checkbox" name="financienCheckbox" <?= (isset($antwoorden['financien']) && $antwoorden['financien'] !== '' && $antwoorden['financien'] !== null) ? "checked" : "" ?>>
                                        <p>Financien</p>
                                    </div><input type="number" min="0" max="4"
                                        value="<?= isset($antwoorden['financien']) ? htmlspecialchars((string)$antwoorden['financien']) : '' ?>"
                                        name="financien">
                                </div>
                                <div class="question">
                                    <div class="observe"><input type="checkbox" name="verzorgingCheckbox" <?= (isset($antwoorden['verzorging']) && $antwoorden['verzorging'] !== '' && $antwoorden['verzorging'] !== null) ? "checked" : "" ?>>
                                        <p>Verzorging</p>
                                    </div><input type="number" min="0" max="4"
                                        value="<?= isset($antwoorden['verzorging']) ? htmlspecialchars((string)$antwoorden['verzorging']) : '' ?>"
                                        name="verzorging">
                                </div>
                                <div class="question">
                                    <div class="observe"><input type="checkbox" name="badenCheckbox" <?= (isset($antwoorden['baden']) && $antwoorden['baden'] !== '' && $antwoorden['baden'] !== null) ? "checked" : "" ?>>
                                        <p>Baden</p>
                                    </div><input type="number" min="0" max="4"
                                        value="<?= isset($antwoorden['baden']) ? htmlspecialchars((string)$antwoorden['baden']) : '' ?>"
                                        name="baden">
                                </div>
                                <div class="question">
                                    <div class="observe"><input type="checkbox" name="toiletgangCheckbox" <?= (isset($antwoorden['toiletgang']) && $antwoorden['toiletgang'] !== '' && $antwoorden['toiletgang'] !== null) ? "checked" : "" ?>>
                                        <p>Toiletgang</p>
                                    </div><input type="number" min="0" max="4"
                                        value="<?= isset($antwoorden['toiletgang']) ? htmlspecialchars((string)$antwoorden['toiletgang']) : '' ?>"
                                        name="toiletgang">
                                </div>
                                <div class="question">
                                    <div class="observe"><input type="checkbox" name="uit_bed_komenCheckbox" <?= (isset($antwoorden['uit_bed_komen']) && $antwoorden['uit_bed_komen'] !== '' && $antwoorden['uit_bed_komen'] !== null) ? "checked" : "" ?>>
                                        <p>Uit bed komen</p>
                                    </div><input type="number" min="0" max="4"
                                        value="<?= isset($antwoorden['uit_bed_komen']) ? htmlspecialchars((string)$antwoorden['uit_bed_komen']) : '' ?>"
                                        name="uit_bed_komen">
                                </div>
                                <div class="question">
                                    <div class="observe"><input type="checkbox" name="winkelenCheckbox" <?= (isset($antwoorden['winkelen']) && $antwoorden['winkelen'] !== '' && $antwoorden['winkelen'] !== null) ? "checked" : "" ?>>
                                        <p>Winkelen</p>
                                    </div><input type="number" min="0" max="4"
                                        value="<?= isset($antwoorden['winkelen']) ? htmlspecialchars((string)$antwoorden['winkelen']) : '' ?>"
                                        name="winkelen">
                                </div>
                                <div class="question">
                                    <p>Neemt u meer tijd voor uzelf wanneer u dat nodig heeft?</p>
                                    <div class="checkboxes">
                                        <div class="question-answer">
                                            <input class="radio" type="radio" value="1" name="tijd_voor_uzelf_nodig"
                                                <?= PatroonModel::isRadioChecked($antwoorden['tijd_voor_uzelf_nodig'] ?? null, 1) ?>>
                                            <label>Ja</label>
                                            <textarea rows="1" cols="25" class="checkfield" type="text"
                                                placeholder="blijkt uit?"
                                                name="tijd_voor_uzelf_nodig_blijktuit"><?= isset($antwoorden['tijd_voor_uzelf_nodig_blijktuit']) ? htmlspecialchars($antwoorden['tijd_voor_uzelf_nodig_blijktuit']) : '' ?></textarea>
                                        </div>
                                        <p>
                                            <input type="radio" value="0" name="tijd_voor_uzelf_nodig"
                                                <?= PatroonModel::isRadioChecked($antwoorden['tijd_voor_uzelf_nodig'] ?? null, 0) ?>>
                                            <label>Nee</label>
                                        </p>
                                    </div>
                                </div>
                                <div class="question">
                                    <p>- Wat zijn uw belangrijkste dagelijkse activiteiten?</p><textarea rows="1"
                                        cols="25" type="text"
                                        name="dagelijkse_activiteiten"><?= isset($antwoorden['dagelijkse_activiteiten']) ? htmlspecialchars($antwoorden['dagelijkse_activiteiten']) : '' ?></textarea>
                                </div>
                                <div class="question">
                                    <p>- Heeft u dagelijkse gewoonten?</p>
                                    <div class="checkboxes">
                                        <div class="question-answer">
                                            <input class="radio" type="radio" value="1" name="dagelijkse_gewoontes"
                                                <?= PatroonModel::isRadioChecked($antwoorden['dagelijkse_gewoontes'] ?? null, 1) ?>>
                                            <label>Ja</label>
                                            <textarea rows="1" cols="25" class="checkfield" type="text"
                                                placeholder="welke?"
                                                name="dagelijkse_gewoontes_welke"><?= isset($antwoorden['dagelijkse_gewoontes_welke']) ? htmlspecialchars($antwoorden['dagelijkse_gewoontes_welke']) : '' ?></textarea>
                                        </div>
                                        <p>
                                            <input type="radio" value="0" name="dagelijkse_gewoontes"
                                                <?= PatroonModel::isRadioChecked($antwoorden['dagelijkse_gewoontes'] ?? null, 0) ?>>
                                            <label>Nee</label>
                                        </p>
                                    </div>
                                </div>
                                <div class="question">
                                    <p>Zijn er lichamelijke beperkingen waardoor u in uw activiteiten wordt belemmerd?
                                    </p>
                                    <div class="checkboxes">
                                        <div class="question-answer">
                                            <input class="radio" type="radio" value="1" name="lichamelijke_beperking"
                                                <?= PatroonModel::isRadioChecked($antwoorden['lichamelijke_beperking'] ?? null, 1) ?>>
                                            <label>Ja</label>
                                            <textarea rows="1" cols="25" class="checkfield" type="text"
                                                placeholder="welke?"
                                                name="lichamelijke_beperking_welke"><?= isset($antwoorden['lichamelijke_beperking_welke']) ? htmlspecialchars($antwoorden['lichamelijke_beperking_welke']) : '' ?></textarea>
                                        </div>
                                        <p>
                                            <input type="radio" value="0" name="lichamelijke_beperking"
                                                <?= PatroonModel::isRadioChecked($antwoorden['lichamelijke_beperking'] ?? null, 0) ?>>
                                            <label>Nee</label>
                                        </p>
                                    </div>
                                </div>
                                <div class="question">
                                    <p>Heeft u vermoeidheidsklachten?</p>
                                    <div class="checkboxes">
                                        <p>
                                            <input type="radio" value="1" name="vermoeidheids_klachten"
                                                <?= PatroonModel::isRadioChecked($antwoorden['vermoeidheids_klachten'] ?? null, 1) ?>>
                                            <label>Ja</label>
                                        </p>
                                        <p>
                                            <input type="radio" value="0" name="vermoeidheids_klachten"
                                                <?= PatroonModel::isRadioChecked($antwoorden['vermoeidheids_klachten'] ?? null, 0) ?>>
                                            <label>Nee</label>
                                        </p>
                                    </div>
                                </div>
                                <div class="question">
                                    <p>- Bent u de afgelopen tijd passiever geworden?</p>
                                    <div class="checkboxes">
                                        <div class="question-answer">
                                            <input class="radio" type="radio" value="1" name="passiever"
                                                <?= PatroonModel::isRadioChecked($antwoorden['passiever'] ?? null, 1) ?>>
                                            <label>Ja</label>
                                            <textarea rows="1" cols="25" class="checkfield" type="text"
                                                placeholder="blijkt uit?"
                                                name="passiever_blijktuit"><?= isset($antwoorden['passiever_blijktuit']) ? htmlspecialchars($antwoorden['passiever_blijktuit']) : '' ?></textarea>
                                        </div>
                                        <p>
                                            <input type="radio" value="0" name="passiever" <?= PatroonModel::isRadioChecked($antwoorden['passiever'] ?? null, 0) ?>>
                                            <label>Nee</label>
                                        </p>
                                    </div>
                                </div>
                                <div class="question">
                                    <p>- Heeft u problemen met het starten van de dag?</p>
                                    <div class="checkboxes">
                                        <div class="question-answer">
                                            <input class="radio" type="radio" value="1" name="problemen_starten_dag"
                                                <?= PatroonModel::isRadioChecked($antwoorden['problemen_starten_dag'] ?? null, 1) ?>>
                                            <label>Ja</label>
                                            <textarea rows="1" cols="25" class="checkfield" type="text"
                                                placeholder="blijkt uit?"
                                                name="problemen_starten_dag_blijktuit"><?= isset($antwoorden['problemen_starten_dag_blijktuit']) ? htmlspecialchars($antwoorden['problemen_starten_dag_blijktuit']) : '' ?></textarea>
                                        </div>
                                        <p>
                                            <input type="radio" value="0" name="problemen_starten_dag"
                                                <?= PatroonModel::isRadioChecked($antwoorden['problemen_starten_dag'] ?? null, 0) ?>>
                                            <label>Nee</label>
                                        </p>
                                    </div>
                                </div>
                                <div class="question">
                                    <p>Heeft u hobby's, doet u aan sport?</p>
                                    <div class="checkboxes">
                                        <p>
                                            <input type="radio" value="1" name="hobbys" <?= PatroonModel::isRadioChecked($antwoorden['hobbys'] ?? null, 1) ?>>
                                            <label>Ja</label>
                                        </p>
                                        <p>
                                            <input type="radio" value="0" name="hobbys" <?= PatroonModel::isRadioChecked($antwoorden['hobbys'] ?? null, 0) ?>>
                                            <label>Nee</label>
                                        </p>
                                    </div>
                                </div>
                                <div class="question">
                                    <p>- Hoeveel tijd per dag besteedt u aan hobby's, vrijetijdsinvulling?</p><textarea
                                        rows="1" cols="25" type="text"
                                        name="hobbys_bestedingstijd"><?= isset($antwoorden['hobbys_bestedingstijd']) ? htmlspecialchars($antwoorden['hobbys_bestedingstijd']) : '' ?></textarea>
                                </div>
                                <div class="question">
                                    <p>Zijn er activiteiten weggevallen als gevolg van uw huidige problemen?</p>
                                    <div class="checkboxes">
                                        <div class="question-answer">
                                            <input class="radio" type="radio" value="1" name="activiteiten_weggevallen"
                                                <?= PatroonModel::isRadioChecked($antwoorden['activiteiten_weggevallen'] ?? null, 1) ?>>
                                            <label>Ja</label>
                                            <textarea rows="1" cols="25" class="checkfield" type="text"
                                                placeholder="en wel?"
                                                name="activiteiten_weggevallen_welke"><?= isset($antwoorden['activiteiten_weggevallen_welke']) ? htmlspecialchars($antwoorden['activiteiten_weggevallen_welke']) : '' ?></textarea>
                                        </div>
                                        <p>
                                            <input type="radio" value="0" name="activiteiten_weggevallen"
                                                <?= PatroonModel::isRadioChecked($antwoorden['activiteiten_weggevallen'] ?? null, 0) ?>>
                                            <label>Nee</label>
                                        </p>
                                    </div>
                                </div>

                                <div class="observation">
                                    <h2>Verpleegkundige observatie bij dit patroon</h2>
                                    <div class="question">
                                        <div class="observe"><input type="checkbox" <?= PatroonModel::isChecked($antwoorden['observatie'], 0) ?> value="1" name="observatie1">
                                            <p>(Dreigend) verminderd activiteitsvermogen</p>
                                        </div>
                                    </div>
                                    <div class="question">
                                        <div class="observe"><input type="checkbox" <?= PatroonModel::isChecked($antwoorden['observatie'], 1) ?> value="1" name="observatie2">
                                            <p>Oververmoeidheid</p>
                                        </div>
                                    </div>
                                    <div class="question">
                                        <div class="observe"><input type="checkbox" <?= PatroonModel::isChecked($antwoorden['observatie'], 2) ?> value="1" name="observatie3">
                                            <p>Mobiliteitstekort</p>
                                        </div>
                                    </div>
                                    <div class="question">
                                        <div class="observe"><input type="checkbox" <?= PatroonModel::isChecked($antwoorden['observatie'], 3) ?> value="1" name="observatie4">
                                            <p>Ontspanningstekort</p>
                                        </div>
                                    </div>
                                    <div class="question">
                                        <div class="observe"><input type="checkbox" <?= PatroonModel::isChecked($antwoorden['observatie'], 4) ?> value="1" name="observatie5">
                                            <p>Moeheid</p>
                                        </div>
                                    </div>
                                    <div class="question">
                                        <div class="observe"><input type="checkbox" <?= PatroonModel::isChecked($antwoorden['observatie'], 5) ?> value="1" name="observatie6">
                                            <p>Verminderd huishoudvermogen</p>
                                        </div>
                                    </div>
                                    <div class="question">
                                        <div class="observe"><input type="checkbox" <?= PatroonModel::isChecked($antwoorden['observatie'], 6) ?> value="1" name="observatie7">
                                            <p>Volledig tekort aan persoonlijke zorg</p>
                                        </div>
                                    </div>
                                </div>
                                <div class="observation">
                                    <div class="question">
                                        <p>Zelfstandigheidstekort in:</p>
                                    </div>
                                    <div class="question">
                                        <div class="observe"><input type="checkbox" <?= PatroonModel::isChecked($antwoorden['observatie'], 7) ?> value="1" name="observatie8">
                                            <p>Wassen</p>
                                        </div>
                                    </div>
                                    <div class="question">
                                        <div class="observe"><input type="checkbox" <?= PatroonModel::isChecked($antwoorden['observatie'], 8) ?> value="1" name="observatie9">
                                            <p>Kleding/verzorging</p>
                                        </div>
                                    </div>
                                    <div class="question">
                                        <div class="observe"><input type="checkbox" <?= PatroonModel::isChecked($antwoorden['observatie'], 9) ?> value="1" name="observatie10">
                                            <p>Eten</p>
                                        </div>
                                    </div>
                                    <div class="question">
                                        <div class="observe"><input type="checkbox" <?= PatroonModel::isChecked($antwoorden['observatie'], 10) ?> value="1" name="observatie11">
                                            <p>Toiletgang</p>
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

