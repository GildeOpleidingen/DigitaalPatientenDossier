<?php
session_start();
include '../../database/DatabaseConnection.php';
include_once '../../classes/Main.php';
$Main = new Main();

$antwoorden = $Main->getPatternAnswers($_SESSION['clientId'], 7);

$boolArrayGevoelOpDitMoment = str_split($antwoorden['gevoel_op_dit_moment']);
$boolArrayGevoelMomenteel = str_split($antwoorden['gevoel_momenteel']);
$boolArrayLichamelijkeEnergie = str_split($antwoorden['lichamelijke_energie']);
$boolArrayObservatie = str_split($antwoorden['observatie']);

if (isset($_REQUEST['navbutton'])) {
    //TODO: hier actie om data op te slaan in database.
    switch($_REQUEST['navbutton']) {
        case 'next': //action for next here
            header('Location: patroon08.php');
            break;
    
        case 'prev': //action for previous here
            header('Location: patroon06.php');
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
    <link rel="stylesheet" href="../../assets/css/bootstrap.min.css">
    <title>Anamnese</title>
</head>
<body>
    <form action="" method="post">
    <div class="main">
        <?php
        include '../../includes/n-header.php';
        include '../../includes/n-sidebar.php';
        ?>
        <div class="content mt-3">
            <div class="form-content">
            <div class="pages">7. Zelfbelevingspatroon</div>
                <div class="form">
                    <div class="questionnaire">
                        <div class="question"><p>Kunt u uzelf, in het kort, beschijven?</p><textarea  rows="1" cols="25" type="text"><?= $antwoorden['zelfbeschrijving'] ?></textarea></div>
                        <div class="question"><p>Kunt u voor uzelf opkomen?</p>
                            <div class="checkboxes">
                                <p>    
                                    <input type="radio" name="opkomen_voor_uzelf" <?= isset($antwoorden['opkomen_voor_uzelf']) ? "checked" : "" ?>>
                                    <label>Ja</label>
                                </p>
                                <p>
                                    <input type="radio" name="opkomen_voor_uzelf" <?= !isset($antwoorden['opkomen_voor_uzelf']) ? "checked" : "" ?>>
                                    <label>Nee</label>
                                </p>
                            </div>
                        </div>
                        <div class="question"><p>Waar blijkt dat uit?</p><textarea  rows="1" cols="25" type="text" name="wel_niet_opkomen_blijktuit"><?= isset($antwoorden['wel_niet_opkomen_blijktuit']) ?></textarea></div>
                        <div class="question"><p>Is uw stemming de laatste tijd veranderd?</p>
                            <div class="checkboxes">
                                <div class="question-answer">
                                    <input id="radio" type="radio" name="opkomen_voor_uzelf" <?= isset($antwoorden['verandering_stemming']) ? "checked" : "" ?>>
                                    <label>Ja</label>
                                    <textarea  rows="1" cols="25" id="checkfield" type="text" placeholder="en wel?" name="verandering_stemming_welke"> <?= isset($antwoorden['verandering_stemming_welke']) ?>
                                </div>
                                <p>
                                    <input type="radio" name="opkomen_voor_uzelf" <?= !isset($antwoorden['verandering_stemming']) ? "checked" : "" ?>>
                                    <label>Nee</label>
                                </p>
                            </div>
                        </div>
                        <div class="question"><p>- Hoe voelt u zich op dit moment?</p> 
                            <div class="observation">
                                <div class="question"><div class="observe"><input type="checkbox" <?= isset($boolArrayGevoelOpDitMoment[0]) ? "checked" : "" ?> name="gevoel1"><p>Neerslachtig</p></div></div>
                                <div class="question"><div class="observe"><input type="checkbox" <?= isset($boolArrayGevoelOpDitMoment[1])? "checked" : "" ?> name="gevoel2"><p>Wanhopig</p></div></div>
                                <div class="question"><div class="observe"><input type="checkbox" <?= isset($boolArrayGevoelOpDitMoment[2]) ? "checked" : "" ?> name="gevoel3"><p>Machteloos</p></div></div>
                                <div class="question"><div class="observe"><input type="checkbox" <?= isset($boolArrayGevoelOpDitMoment[3]) ? "checked" : "" ?> name="gevoel4"><p>Opgewekt</p></div></div>
                                <div class="question"><div class="observe"><input type="checkbox" <?= isset($boolArrayGevoelOpDitMoment[4]) ? "checked" : "" ?> name="gevoel5"><p>Somber</p></div></div>
                                <div class="question"><div class="observe"><input type="checkbox" <?= isset($boolArrayGevoelOpDitMoment[5]) ? "checked" : "" ?> name="gevoel6"><p>Eufoor</p></div></div>
                                <div class="question"><div class="observe"><input type="checkbox" <?= isset($boolArrayGevoelOpDitMoment[6]) ? "checked" : "" ?> name="gevoel7"><p>Labiel</p></div></div>
                                <div class="question"><div class="observe"><input type="checkbox" <?= isset($boolArrayGevoelOpDitMoment[7]) ? "checked" : "" ?> name="gevoel8"><p>Gespannen</p></div></div>
                                <div class="question"><div class="observe"><input type="checkbox" <?= isset($boolArrayGevoelOpDitMoment[8]) ? "checked" : "" ?> name="gevoel9"><p>Verdrietig</p></div></div>
                                <div class="question"><div class="observe"><input type="checkbox" <?= isset($boolArrayGevoelOpDitMoment[9]) ? "checked" : "" ?> name="gevoel10"><p>Anders, namelijk:</p></div><textarea  rows="1" cols="25" type="text"><?= isset($antwoorden['anders']) ?></textarea></div>
                            </div>
                        </div>
                        <div class="question"><p>- Is er de afgelopen tijd iets veranderd in uw concentratievermogen ten gevolgen van u stemming?</p>
                            <div class="checkboxes">
                                <p>    
                                    <input type="radio" name="verandering_concentratie" <?= isset($antwoorden['verandering_concentratie']) ? "checked" : "" ?>>
                                    <label>Ja</label>
                                </p>
                                <p>
                                    <input type="radio" name="verandering_concentratie" <?= !isset($antwoorden['verandering_concentratie']) ? "checked" : "" ?>>
                                    <label>Nee</label>
                                </p>
                            </div>
                        </div>
                        <div class="question"><p>- Is er de afgelopen tijd iets veranderd in uw denkpatroon ten gevolgen van u stemming?</p>
                            <div class="checkboxes">
                                <p>    
                                    <input type="radio" name="verandering_denkpatroon" <?= isset($antwoorden['verandering_denkpatroon']) ? "checked" : "" ?>>
                                    <label>Ja</label>
                                </p>
                                <p>
                                    <input type="radio" name="verandering_denkpatroon" <?= !isset($antwoorden['verandering_denkpatroon']) ? "checked" : "" ?>>
                                    <label>Nee</label>
                                </p>
                            </div>
                        </div>
                        <div class="question"><p>Ervaart u uzelf nu anders dan voorheen?</p>
                            <div class="checkboxes">
                                <p>    
                                    <input type="radio" name="ervaring_voorheen" <?= isset($antwoorden['ervaring_voorheen']) ? "checked" : "" ?>>
                                    <label>Ja</label>
                                </p>
                                <p>
                                    <input type="radio" name="ervaring_voorheen" <?= !isset($antwoorden['ervaring_voorheen']) ? "checked" : "" ?>>
                                    <label>Nee</label>
                                </p>
                            </div>
                        </div>
                        <div class="question"><p>- Zijn er veranderingen in uw uiterlijk en/of mogelijkheden waardoor u zich anders voelt?</p>
                            <div class="checkboxes">
                                <p>    
                                    <input type="radio" name="verandering_uiterlijk" <?= isset($antwoorden['verandering_uiterlijk']) ? "checked" : "" ?>>
                                    <label>Ja</label>
                                </p>
                                <p>
                                    <input type="radio" name="verandering_uiterlijk" <?= !isset($antwoorden['verandering_uiterlijk']) ? "checked" : "" ?>>
                                    <label>Nee</label>
                                </p>
                            </div>
                        </div>
                        <div class="question"><p>- Voelt u (lichamelijke) sensaties?</p>
                            <div class="checkboxes">
                                <div class="question-answer">
                                    <input id="radio" type="radio" name="sensaties" <?= isset($antwoorden['sensaties']) ? "checked" : "" ?>>
                                    <label>Ja</label>
                                    <textarea  rows="1" cols="25" id="checkfield" type="text" placeholder="wat voelt u?" name="sensaties_wat"> <?= isset($antwoorden['sensaties_wat']) ?> </textarea>
                                </div>
                                <p>
                                    <input type="radio" name="sensaties" <?= !isset($antwoorden['sensaties']) ? "checked" : "" ?>>
                                    <label>Nee</label>
                                </p>
                            </div>
                        </div>
                        <div class="question"><p>- Hoe voelt u zich momenteel?</p> 
                            <div class="observation">
                                <div class="question"><div class="observe"><input type="checkbox" <?= isset($boolArrayGevoelMomenteel[0]) ? "checked" : "" ?> name="gevoelMomenteel1"><p>Sterk</p></div></div>
                                <div class="question"><div class="observe"><input type="checkbox" <?= isset($boolArrayGevoelMomenteel[1]) ? "checked" : "" ?> name="gevoelMomenteel2"><p>Zwak</p></div></div>
                                <div class="question"><div class="observe"><input type="checkbox" <?= isset($boolArrayGevoelMomenteel[2]) ? "checked" : "" ?> name="gevoelMomenteel3"><p>Krachteloos</p></div></div>
                            </div>
                        </div>
                        <div class="question"><p>- Hoe staat het met uw lichamelijke energie?</p> 
                            <div class="observation">
                                <div class="question"><div class="observe"><input type="checkbox" <?= isset($boolArrayLichamelijkeEnergie[0]) ? "checked" : "" ?> name="lichamelijkeEnergie1"><p>Genoeg</p></div></div>
                                <div class="question"><div class="observe"><input type="checkbox" <?= isset($boolArrayLichamelijkeEnergie[1]) ? "checked" : "" ?> name="lichamelijkeEnergie2"><p>Te veel</p></div></div>
                                <div class="question"><div class="observe"><input type="checkbox" <?= isset($boolArrayLichamelijkeEnergie[2]) ? "checked" : "" ?> name="lichamelijkeEnergie3"><p>Te weinig</p></div></div>
                            </div>
                        </div>
                        <div class="question"><p>Zorgt u goed voor u zelf, of vindt u dat het beter kan?</p><textarea  rows="1" cols="25" type="text" name="zelfverzorging"><?= isset($antwoorden['zelfverzorging']) ?></textarea></div>

                        <div class="observation">
                            <h2>Verpleegkundige observatie bij dit patroon</h2>
                            <div class="question"><div class="observe"><input type="checkbox" <?= isset($boolArrayObservatie[0]) ? "checked" : ""?> name="observatie1"><p>Lichte angst</p></div></div>
                            <div class="question"><div class="observe"><input type="checkbox" <?= isset($boolArrayObservatie[1]) ? "checked" : ""?> name="observatie2"><p>Matige angst</p></div></div>
                            <div class="question"><div class="observe"><input type="checkbox" <?= isset($boolArrayObservatie[2]) ? "checked" : ""?> name="observatie3"><p>Hevige (paniek) angst</p></div></div>
                            <div class="question"><div class="observe"><input type="checkbox" <?= isset($boolArrayObservatie[3]) ? "checked" : ""?> name="observatie4"><p>Lichte anticiperende angst</p></div></div>
                            <div class="question"><div class="observe"><input type="checkbox" <?= isset($boolArrayObservatie[4]) ? "checked" : ""?> name="observatie5"><p>Matige anticiperende angst</p></div></div>
                            <div class="question"><div class="observe"><input type="checkbox" <?= isset($boolArrayObservatie[5]) ? "checked" : ""?> name="observatie6"><p>Hevige anticiperende angst</p></div></div>
                            <div class="question"><div class="observe"><input type="checkbox" <?= isset($boolArrayObservatie[6]) ? "checked" : ""?> name="observatie7"><p>Vrees</p></div></div>
                            <div class="question"><div class="observe"><input type="checkbox" <?= isset($boolArrayObservatie[7]) ? "checked" : ""?> name="observatie8"><p>Reactieve depressie</p></div></div>
                            <div class="question"><div class="observe"><input type="checkbox" <?= isset($boolArrayObservatie[8]) ? "checked" : ""?> name="observatie9"><p>Moedeloosheid</p></div></div>
                            <div class="question"><div class="observe"><input type="checkbox" <?= isset($boolArrayObservatie[9]) ? "checked" : ""?> name="observatie10"><p>Identiteitsstoornis</p></div></div>
                            <div class="question"><div class="observe"><input type="checkbox" <?= isset($boolArrayObservatie[10]) ? "checked" : "" ?> name="observatie11"><p>Lichte machteloosheid</p></div></div>
                            <div class="question"><div class="observe"><input type="checkbox" <?= isset($boolArrayObservatie[11]) ? "checked" : "" ?> name="observatie12"><p>Matige machteloosheid</p></div></div>
                            <div class="question"><div class="observe"><input type="checkbox" <?= isset($boolArrayObservatie[12]) ? "checked" : "" ?> name="observatie13"><p>Ernstige machteloosheid</p></div></div>
                            <div class="question"><div class="observe"><input type="checkbox" <?= isset($boolArrayObservatie[13]) ? "checked" : "" ?> name="observatie14"><p>Geringe zelfachting</p></div></div>
                            <div class="question"><div class="observe"><input type="checkbox" <?= isset($boolArrayObservatie[14]) ? "checked" : "" ?> name="observatie15"><p>Chronisch geringe zelfachting</p></div></div>
                            <div class="question"><div class="observe"><input type="checkbox" <?= isset($boolArrayObservatie[15]) ? "checked" : "" ?> name="observatie16"><p>Reactief geringe zelfachting</p></div></div>
                            <div class="question"><div class="observe"><input type="checkbox" <?= isset($boolArrayObservatie[16]) ? "checked" : "" ?> name="observatie17"><p>Verstoord lichaamsbeeld</p></div></div>
                            <div class="question"><div class="observe"><input type="checkbox" <?= isset($boolArrayObservatie[17]) ? "checked" : "" ?> name="observatie18"><p>Hopeloosheid</p></div></div>
                            <div class="question"><div class="observe"><input type="checkbox" <?= isset($boolArrayObservatie[18]) ? "checked" : "" ?> name="observatie19"><p>Dreigende zelfverminking (automutilatie)</p></div></div>
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