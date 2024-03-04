
<?php
session_start();
include '../../Database/DatabaseConnection.php';
include '../../Functions/Functions.php';

$antwoorden = getPatternAnswers($_SESSION['clientId'], 4);

$boolArrayObservatie = str_split($antwoorden['observatie']);

if (isset($_REQUEST['navbutton'])) {
    $clientId = $_GET['id'];
    //TODO: hier actie om data op te slaan in database.
    switch($_REQUEST['navbutton']) {
        case 'next': //action for next here
            header('Location: patroon05.php?id='.$clientId);
            break;
    
        case 'prev': //action for previous here
            header('Location: patroon03.php?id='.$clientId);
            break;
    }
    exit;
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
	<link rel="Stylesheet" href="patronen.css">
    <title>Anamnese</title>
</head>
<body>
    <form action="" method="post">
    <div class="main">
        <?php
        include '../../Includes/header.php';
        include '../../Includes/sidebar.php';
        ?>
        <div class="content">
            <div class="form-content">
            <div class="pages">4 Activiteitenpatroon</div>
                <div class="form">
                    <div class="questionnaire">
                        <!-- Zorg ervoor dat dit 0-4 is en als het niet is ingevuld null is en niet 0 -->
                        <div class="question"><p>In hoeverre bent u in staat de volgende activiteiten te doen?</p></div>
                        <div class="question"><p><i>0 - volledige zelfzorg<br>1 - gebruik van hulpmiddelen of plan<br>2 - vereist assistentie/supervisie van anderen<br>3 - Vereidst gebruik van hulpmiddelen of plan/methoden en assistentie van anderen<br>4 - is afhankelijk en/of participeert niet)</i></p></div>
                        <div class="question"><div class="observe"><input type="checkbox" name="voedingCheckbox" <?php if($antwoorden['voeding'] == "0" || $antwoorden['voeding'] > 0) { echo "checked"; } ?>><p>Voeding</p></div><input type="number" min="0" max="4" value="<?= $antwoorden['voeding'] ?? ""?>" name="voeding"></div>
                        <div class="question"><div class="observe"><input type="checkbox" name="aankledenCheckbox" <?php if($antwoorden['aankleden'] == "0" || $antwoorden['aankleden'] > 0) { echo "checked"; } ?>><p>Aankleden</p></div><input type="number" min="0" max="4" value="<?= $antwoorden['aankleden'] ?? "" ?>" name="aankleden"></div>
                        <div class="question"><div class="observe"><input type="checkbox" name="alg_mobiliteitCheckbox" <?php if($antwoorden['alg_mobiliteit'] == "0" || $antwoorden['alg_mobiliteit'] > 0) { echo "checked"; } ?>><p>Algemene mobiliteit</p></div><input type="number" min="0" max="4" value="<?= $antwoorden['alg_mobiliteit'] ?? "" ?>" name="alg_mobiliteit"></div>
                        <div class="question"><div class="observe"><input type="checkbox" name="kokenCheckbox" <?php if($antwoorden['koken'] == "0" || $antwoorden['koken'] > 0) { echo "checked"; } ?>><p>Koken</p></div><input type="number" min="0" max="4" value="<?= $antwoorden['koken'] ?? "" ?>" name="koken"></div>
                        <div class="question"><div class="observe"><input type="checkbox" name="huishoudenCheckbox" <?php if($antwoorden['huishouden'] == "0" || $antwoorden['huishouden'] > 0) { echo "checked"; } ?>><p>Huishouden</p></div><input type="number" min="0" max="4" value="<?= $antwoorden['huishouden'] ?? "" ?>" name="huishouden"></div>
                        <div class="question"><div class="observe"><input type="checkbox" name="financienCheckbox" <?php if($antwoorden['financien'] == "0" || $antwoorden['financien'] > 0) { echo "checked"; } ?>><p>Financiën</p></div><input type="number" min="0" max="4" value="<?= $antwoorden['financien'] ?? ""  ?>" name="financien"></div>
                        <div class="question"><div class="observe"><input type="checkbox" name="verzorgingCheckbox" <?php if($antwoorden['verzorging'] == "0" || $antwoorden['verzorging'] > 0) { echo "checked"; } ?>><p>Verzorging</p></div><input type="number" min="0" max="4" value="<?= $antwoorden['verzorging'] ?? ""  ?>" name="verzorging"></div>
                        <div class="question"><div class="observe"><input type="checkbox" name="badenCheckbox" <?php if($antwoorden['baden'] == "0" || $antwoorden['baden'] > 0) { echo "checked"; } ?>><p>Baden</p></div><input type="number" min="0" max="4" value="<?= $antwoorden['baden'] ?? "" ?>" name="baden"></div>
                        <div class="question"><div class="observe"><input type="checkbox" name="toiletgangCheckbox" <?php if($antwoorden['toiletgang'] == "0" || $antwoorden['toiletgang'] > 0) { echo "checked"; } ?>><p>Toiletgang</p></div><input type="number" min="0" max="4" value="<?= $antwoorden['toiletgang'] ?? "" ?>" name="toiletgang"></div>
                        <div class="question"><div class="observe"><input type="checkbox" name="uit_bed_komenCheckbox" <?php if($antwoorden['uit_bed_komen'] == "0" || $antwoorden['uit_bed_komen'] > 0) { echo "checked"; } ?>><p>Uit bed komen</p></div><input type="number" min="0" max="4" value="<?= $antwoorden['uit_bed_komen'] ?? "" ?>" name="uit_bed_komen"></div>
                        <div class="question"><div class="observe"><input type="checkbox" name="winkelenCheckbox" <?php if($antwoorden['winkelen'] == "0" || $antwoorden['winkelen'] > 0) { echo "checked"; } ?>><p>Winkelen</p></div><input type="number" min="0" max="4" value="<?= $antwoorden['winkelen'] ?? "" ?>" name="winkelen"></div>
                        <div class="question"><p>Neemt u meer tijd voor uzelf wanneer u dat nodig heeft?</p>
                            <div class="checkboxes">
                                <div class="question-answer">
                                    <input id="radio" type="radio" name="tijd_voor_uzelf_nodig" <?= $antwoorden['tijd_voor_uzelf_nodig'] ? "checked" : "" ?>>
                                    <label>Ja</label>
                                    <textarea  rows="1" cols="25" id="checkfield" type="text" placeholder="blijkt uit?" name="tijd_voor_uzelf_nodig_blijktuit"><?= $antwoorden['tijd_voor_uzelf_nodig_blijktuit'] ?? ""?></textarea>
                                </div>
                                <p>
                                    <input type="radio" name="tijd_voor_uzelf_nodig" <?= !$antwoorden['tijd_voor_uzelf_nodig'] ? "checked" : "" ?>>
                                    <label>Nee</label>
                                </p>
                            </div>
                        </div>
                        <div class="question"><p>- Wat zijn uw belangrijkste dagelijkse activiteiten?</p><textarea  rows="1" cols="25" type="text" name="dagelijkse_activiteiten"><?= $antwoorden['dagelijkse_activiteiten'] ?? "" ?></textarea></div>
                        <div class="question"><p>- Heeft u dagelijkse gewoonten?</p>
                            <div class="checkboxes">
                                <div class="question-answer">
                                    <input id="radio" type="radio" name="dagelijkse_gewoontes" <?= $antwoorden['dagelijkse_gewoontes'] ? "checked" : "" ?>>
                                    <label>Ja</label>
                                    <textarea  rows="1" cols="25" id="checkfield" type="text" placeholder="welke?" name="dagelijkse_gewoontes_welke"><?= $antwoorden['dagelijkse_gewoontes_welke'] ?? "" ?></textarea>
                                </div>
                                <p>
                                    <input type="radio" name="dagelijkse_gewoontes" <?= !$antwoorden['dagelijkse_gewoontes'] ? "checked" : "" ?>>
                                    <label>Nee</label>
                                </p>
                            </div>
                        </div>
                        <div class="question"><p>Zijn er lichamelijke beperkingen waardoor u in uw activiteiten wordt belemmerd?</p>
                            <div class="checkboxes">
                                <div class="question-answer">
                                    <input id="radio" type="radio" name="lichamelijke_beperking" <?= $antwoorden['lichamelijke_beperking'] ? "checked" : "" ?>>
                                    <label>Ja</label>
                                    <textarea rows="1" cols="25" id="checkfield" type="text" placeholder="welke?" name="lichamelijke_beperking_welke"><?= $antwoorden['lichamelijke_beperkingen_welke'] ?? "" ?></textarea>
                                </div>
                                <p>
                                    <input type="radio" name="lichamelijke_beperking" <?= !$antwoorden['lichamelijke_beperking'] ? "checked" : "" ?>>
                                    <label>Nee</label>
                                </p>
                            </div>
                        </div>
                        <div class="question"><p>Heeft u vermoeidheidsklachten?</p>
                            <div class="checkboxes">
                                <p>    
                                    <input type="radio" name="vermoeidheids_klachten" <?= $antwoorden['vermoeidheids_klachten'] ? "checked" : "" ?>>
                                    <label>Ja</label>
                                </p>
                                <p>
                                    <input type="radio" name="vermoeidheids_klachten" <?= !$antwoorden['vermoeidheids_klachten'] ? "checked" : "" ?>>
                                    <label>Nee</label>
                                </p>
                            </div>
                        </div>
                        <div class="question"><p>- Bent u de afgelopen tijd passiever geworden?</p>
                            <div class="checkboxes">
                                <div class="question-answer">
                                    <input id="radio" type="radio" name="passiever" <?= $antwoorden['passiever'] ? "checked" : "" ?>>
                                    <label>Ja</label>
                                    <textarea  rows="1" cols="25" id="checkfield" type="text" placeholder="blijkt uit?" name="passiever_blijktuit"><?= $antwoorden['passiever_blijktuit'] ?? "" ?></textarea>
                                </div>
                                <p>
                                    <input type="radio" name="passiever" <?= !$antwoorden['passiever'] ? "checked" : "" ?>>
                                    <label>Nee</label>
                                </p>
                            </div>
                        </div>
                        <div class="question"><p>- Heeft u problemen met het starten van de dag?</p>
                            <div class="checkboxes">
                                <div class="question-answer">
                                    <input id="radio" type="radio" name="problemen_starten_dag" <?= $antwoorden['problemen_starten_dag'] ? "checked" : "" ?>>
                                    <label>Ja</label>
                                    <textarea  rows="1" cols="25" id="checkfield" type="text" placeholder="blijkt uit?" name="problemen_starten_dag_blijktuit"><?= $antwoorden['problemen_starten_dag_blijktuit'] ?? "" ?></textarea>
                                </div>
                                <p>
                                    <input type="radio" name="problemen_starten_dag" <?= !$antwoorden['problemen_starten_dag'] ? "checked" : "" ?>>
                                    <label>Nee</label>
                                </p>
                            </div>
                        </div>
                        <div class="question"><p>Heeft u hobby's, doet u aan sport?</p>
                            <div class="checkboxes">
                                <p>    
                                    <input type="radio" name="hobbys" <?= $antwoorden['hobbys'] ? "checked" : "" ?>>
                                    <label>Ja</label>
                                </p>
                                <p>
                                    <input type="radio" name="hobbys" <?= !$antwoorden['hobbys'] ? "checked" : "" ?>>
                                    <label>Nee</label>
                                </p>
                            </div>
                        </div>
                        <div class="question"><p>- Hoeveel tijd per dag besteedt u aan hobby's, vrijetijdsinvulling?</p><textarea rows="1" cols="25" type="text" name="hobbys_bestedingstijd"><?= $antwoorden['hobbys_bestedingstijd'] ?? "" ?></textarea></div>
                        <div class="question"><p>Zijn er activiteiten weggevallen  als gevolg van uw huidige problemen?</p>
                            <div class="checkboxes">
                                <div class="question-answer">
                                    <input id="radio" type="radio" name="activiteiten_weggevallen" <?= $antwoorden['activiteiten_weggevallen'] ? "checked" : "" ?>>
                                    <label>Ja</label>
                                    <textarea  rows="1" cols="25" id="checkfield" type="text" placeholder="en wel?" name="activiteiten_weggevallen_welke"><?= $antwoorden['activiteiten_weggevallen_welke'] ?? "" ?></textarea>
                                </div>
                                <p>
                                    <input type="radio" name="activiteiten_weggevallen" <?= !$antwoorden['activiteiten_weggevallen'] ? "checked" : "" ?>>
                                    <label>Nee</label>
                                </p>
                            </div>
                        </div>

                        <div class="observation">
                            <h2>Verpleegkundige observatie bij dit patroon</h2>
                            <div class="question"><div class="observe"><input type="checkbox" <?= $boolArrayObservatie[0] ? "checked" : "" ?> name="observatie1"><p>(Dreigend) verminderd activiteitsvermogen</p></div></div>
                            <div class="question"><div class="observe"><input type="checkbox" <?= $boolArrayObservatie[1] ? "checked" : "" ?> name="observatie2"><p>Oververmoeidheid</p></div></div>
                            <div class="question"><div class="observe"><input type="checkbox" <?= $boolArrayObservatie[2] ? "checked" : "" ?> name="observatie3"><p>Mobiliteitstekort</p></div></div>
                            <div class="question"><div class="observe"><input type="checkbox" <?= $boolArrayObservatie[3] ? "checked" : "" ?> name="observatie4"><p>Ontspanningstekort</p></div></div>
                            <div class="question"><div class="observe"><input type="checkbox" <?= $boolArrayObservatie[4] ? "checked" : "" ?> name="observatie5"><p>Moeheid</p></div></div>
                            <div class="question"><div class="observe"><input type="checkbox" <?= $boolArrayObservatie[5] ? "checked" : "" ?> name="observatie6"><p>Verminderd huishoudvermogen</p></div></div>
                            <div class="question"><div class="observe"><input type="checkbox" <?= $boolArrayObservatie[6] ? "checked" : "" ?> name="observatie7"><p>Volledig tekort aan persoonlijke zorg</p></div></div>
                        </div>
                        <div class="observation">
                            <div class="question"><p>Zelfstandigheidstekort in:</p></div>
                            <div class="question"><div class="observe"><input type="checkbox" <?= $boolArrayObservatie[7] ? "checked" : "" ?> name="observatie8"><p>Wassen</p></div></div>
                            <div class="question"><div class="observe"><input type="checkbox" <?= $boolArrayObservatie[8] ? "checked" : "" ?> name="observatie9"><p>Kleding/verzorging</p></div></div>
                            <div class="question"><div class="observe"><input type="checkbox" <?= $boolArrayObservatie[9] ? "checked" : "" ?> name="observatie10"><p>Eten</p></div></div>
                            <div class="question"><div class="observe"><input type="checkbox" <?= $boolArrayObservatie[10] ? "checked" : "" ?> name="observatie11"><p>Toiletgang</p></div></div>
                        </div>
                    </div>
                </div>
                <div class="submit">
                    <button name="navbutton" type="submit" value="prev">< Vorige</button>
                    <button name="navbutton" type="submit" value="next">Volgende ></button>
                </div>
            </div>
        </div>
        </div>
        </form>
</body>
</html>