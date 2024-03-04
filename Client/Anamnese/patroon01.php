<?php
session_start();
include '../../Database/DatabaseConnection.php';

// id van die client
$client_id = $_GET['id'];

//id van de medewerker
$medewerker_id = $_SESSION['loggedin_id'];

if (isset($_REQUEST['navbutton'])) {
    $algemene_gezondheid = $_POST['algemene_gezondheid'];
    $gezondheids_bezigheid = $_POST['gezondheids_bezigheid'];
    $rookt = ($_POST['rookt']);
    $rookt_hoeveelheid = $_POST['rookt_hoeveelheid'];
    $drinkt = $_POST['drinkt'];
    $drinkt_hoeveelheid = $_POST['drinkt_hoeveelheid'];
    $besmettelijke_aandoening = $_POST['besmettelijke_aandoening'];
    $besmettelijke_aandoening_welke = $_POST['besmettelijke_aandoening_welke'];
    $alergieen = $_POST['alergieen'];
    $alergieen_welke = $_POST['alergieen_welke'];
    $oorzaak_huidige_toestand = $_POST['oorzaak_huidige_toestand'];
    $oht_actie = $_POST['oht_actie'];
    $oht_hoe_effectief = $_POST['$oht_hoe_effectief'];
    $oht_wat_nodig = $_POST['oht_wat_nodig'];
    $oht_wat_belangrijk = $_POST['oht_wat_belangrijk'];
    $oht_reactie_op_advies = $_POST['oht_reactie_op_advies'];
    $preventie= $_POST['oht_reactie_op_advies'];
    $observatie = $_POST['observatie'];

    switch($_REQUEST['navbutton']) {
        case 'next': //action for next here
            header('Location: patroon02.php?id='.$client_id);
            break;

        case 'prev': //action for previous here
            header('Location: patroon11.php?id='.$client_id); //TODO: hier moet naar de hoofdpagina genavigeerd worden.
            break;
    }
    exit;
}

$client = DatabaseConnection::getConn()->prepare("SELECT * FROM client WHERE id = ?");
$client->bind_param("i", $client_id);
$client->execute();
$client = $client->get_result()->fetch_assoc();

if ($client == null) {
    header("Location: ../../index.php");
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
        ?>

        <?php
        include '../../Includes/sidebar.php';
        ?>
            <div class="content">
                <div class="form-content">
                    <div class="pages">1 Patroon van gezondheidsbeleving en -instandhouding</div>
                    <div class="form">
                        <div class="questionnaire">
                            <div class="question"><p>Hoe is uw gezondheid in het algemeen?</p><textarea  name="algemene_gezondheid" rows="1" cols="25" type="text"></textarea></div>
                            <div class="question"><p>Wat doet u om gezond te blijven?</p><textarea  name="gezondheids_bezigheid" rows="1" cols="25" type="text"></textarea></div>
                            <div class="question"><p>- Rookt u?</p>
                                <div class="checkboxes">
                                    <div class="question-answer">
                                        <input id="radio" type="radio" name="rookt">
                                        <label>Ja</label>
                                        <textarea  rows="1" cols="25" id="checkfield" name="rookt_hoeveelheid" type="text" placeholder="hoeveel?"></textarea>
                                    </div>
                                    <p>
                                        <input type="radio" name="rookt">
                                        <label>Nee</label>
                                    </p>
                                </div>
                            </div>
                            <div class="question"><p>- Drinkt u?</p>
                                <div class="checkboxes">
                                    <div class="question-answer">
                                        <input id="radio" type="radio" name="drinkt">
                                        <label>Ja</label>
                                        <textarea  rows="1" cols="25" name="drinkt_hoeveelheid" id="checkfield" type="text" placeholder="hoeveel?"></textarea>
                                    </div>
                                    <p>
                                        <input type="radio" name="drinkt">
                                        <label>Nee</label>
                                    </p>
                                </div>
                            </div>
                            <div class="question"><p>Heeft u momenteel een infectie of overdraagbare besmettelijke aandoening?</p>
                                <div class="checkboxes">
                                    <div class="question-answer">
                                        <input id="radio" type="radio" name="besmettelijke_aandoening">
                                        <label>Ja</label>
                                        <textarea  rows="1" cols="25" id="checkfield" name="besmettelijke_aandoening_welke" type="text" placeholder="en wel?"></textarea>
                                    </div>
                                    <p>
                                        <input type="radio" name="besmettelijke_aandoening">
                                        <label>Nee</label>
                                    </p>
                                </div>
                            </div>
                            <div class="question"><p>Bent u ergens allergisch voor?</p>
                                <div class="checkboxes">
                                    <div class="question-answer">
                                        <input id="radio" type="radio" name="alergieen">
                                        <label>Ja</label>
                                        <textarea  rows="1" cols="25" name="alergieen_welke" id="checkfield" type="text" placeholder="en wel?"></textarea>
                                    </div>
                                    <p>
                                        <input type="radio" name="alergieen">
                                        <label>Nee</label>
                                    </p>
                                </div>
                            </div>
                            <div class="question"><p>Wat denkt u dat de oorzaak is van uw huidige situatie/toestand?</p><textarea  rows="1" cols="25" name="oorzaak_huidige_toestand" type="text"></textarea></div>
                            <div class="question"><p>- Wat heeft u eraan gedaan?</p><textarea  rows="1" cols="25" type="text" name="oht_actie"></textarea></div>
                            <div class="question"><p>- Hoe effectief was dat?</p><textarea  rows="1" cols="25" name="oht_hoe_effectief" type="text"></textarea></div>
                            <div class="question"><p>- Hoe kunnen wij u helpen?</p><textarea  rows="1" cols="25" name="oht_wat_nodig" type="text"></textarea></div>
                            <div class="question"><p>- Wat is voor u belangrijk tijdens het verblijf op deze afdeling?</p><textarea  rows="1" cols="25" name="oht_wat_belangrijk" type="text"></textarea></div>
                            <div class="question"><p>- Vind u het gemakkelijk om dingen te doen of te laten op advies van de arts of verpleegkundige?</p><textarea  rows="1" cols="25" name="oht_reactie_op_advies" type="text"></textarea></div>
                            <div class="question"><p>Wat moet u in de toekomst doen ter voorkoming van het weer ziek worden?</p><textarea  rows="1" cols="25" name="preventie" type="text"></textarea></div>


                            <div class="observation">
                                <h2>Verpleegkundige observatie bij dit patroon</h2>
                                <div class="question"><div class="observe"><input type="checkbox" value="1" name="observatie1">
                                        <p>Gezondheidszoekend gedrag</p></div></div>
                                <div class="question"><div class="observe"><input type="checkbox" value="1" name="observatie2">
                                        <p>Tekort in gezondheidsonderhoud</p></div></div>
                                <div class="question"><div class="observe"><input type="checkbox" value="1" name="observatie3">
                                        <p>(Dreigende) inadequate opvolging van de behandeling</p></div></div>
                                <div class="question"><div class="observe"><input type="checkbox" value="1" name="observatie4">
                                        <p>(Dreigend) tekort in gezondheidsinstandhouding</p></div></div>
                                <div class="question"><div class="observe"><input type="checkbox" value="1" name="observatie5">
                                        <p>(Dreigende) therapieontrouw</p></div></div>
                                <div class="question"><div class="observe"><input type="checkbox" value="1" name="observatie6">
                                        <p>Vergiftigingsgevaar</p></div></div>
                                <div class="question"><div class="observe"><input type="checkbox" value="1" name="observatie7">
                                        <p>Infectiegevaar</p></div></div>
                                <div class="question"><div class="observe"><input type="checkbox" value="1" name="observatie8">
                                        <p>Gevaar voor letsel (trauma)</p></div></div>
                                <div class="question"><div class="observe"><input type="checkbox" value="1" name="observatie9">
                                        <p>Verstikkingsgevaar</p></div></div>
                                <div class="question"><div class="observe"><input type="checkbox" value="1" name="observatie10">
                                        <p>Beschermingstekort</p></div></div>
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