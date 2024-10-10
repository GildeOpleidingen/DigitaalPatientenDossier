<?php
session_start();
include '../../database/DatabaseConnection.php';
include_once '../../classes/Main.php';
$Main = new Main();

$antwoorden = $Main->getPatternAnswers($_SESSION['clientId'], 2);

if(isset($antwoorden['observatie'])){
    $boolArrayObservatie = str_split($antwoorden['observatie']);
} else {
    $boolArrayObservatie = array();
}

$medewerkerId = $_SESSION['loggedin_id'];

if (isset($_REQUEST['navbutton'])) {
    $eetlust = $_POST['eetlust'];
    $dieet = $_POST['dieet'];
    $dieetWelk = strval($_POST['dieet_welk']);
    $gewichtVerandert = $_POST['gewicht_verandert'];
    $moeilijkSlikken = $_POST['moeilijk_slikken'];
    $gebitsProblemen = $_POST['gebitsproblemen'];
    $gebitsProthese = $_POST['gebitsprothese'];
    $huidProblemen = $_POST['huidproblemen'];
    $gevoel = $_POST['gevoel'];
    // array van checkboxes van observatie tab
    $arr = array(!empty($_POST['observatie1']), !empty($_POST['observatie2']), !empty($_POST['observatie3']), !empty($_POST['observatie4']), !empty($_POST['observatie5']), !empty($_POST['observatie6']));

    $observatie = $Main->convertBoolArrayToString($arr);

    $result = DatabaseConnection::getConn()->prepare("
                    SELECT vl.id
                    from vragenlijst vl
                    left join verzorgerregel on verzorgerregel.id = vl.verzorgerregelid
                    where verzorgerregel.clientid = ?");
    $result->bind_param("i", $_SESSION['clientId']);
    $result->execute();
    $result = $result->get_result()->fetch_assoc();

    if ($result != null) {
        $vragenlijstId = $result['id'];
    } else {
        $sql2 = DatabaseConnection::getConn()->prepare("INSERT INTO `vragenlijst`(`verzorgerregelid`)
            VALUES ((SELECT id
            FROM verzorgerregel
            WHERE clientid = ?
            AND medewerkerid = ?))");
        $sql2->bind_param("ii", $_SESSION['clientId'], $medewerkerId);
        $sql2->execute();
        $sql2 = $sql2->get_result();

        $result = DatabaseConnection::getConn()->prepare("SELECT vl.id
                    from vragenlijst vl
                    left join verzorgerregel on verzorgerregel.id = vl.verzorgerregelid
                    where verzorgerregel.clientid = ?");
        $result->bind_param("i", $_SESSION['clientId']);
        $result->execute();
        $result = $result->get_result()->fetch_assoc();

        $vragenlijstId = $result['id'];
    }

    // kijken of patroon02 bestaat door te kijken naar vragenlijst id
    $result = DatabaseConnection::getConn()->prepare("
                    SELECT p.id
                    FROM patroon02voedingstofwisseling p
                    WHERE p.vragenlijstid =  ?");
    $result->bind_param("i", $vragenlijstId);
    $result->execute();
    $result = $result->get_result()->fetch_assoc();

    if ($result != null) {
        //update
        $result1 = DatabaseConnection::getConn()->prepare("UPDATE `patroon02voedingstofwisseling`
            SET
            `eetlust`=?,
            `dieet`=?,
            `dieet_welk`=?,
            `gewicht_verandert`=?,
            `moeilijk_slikken`=?,
            `gebitsproblemen`=?,
            `gebitsprothese`=?,
            `huidproblemen`=?,
            `gevoel`=?,
            `observatie`=?
            WHERE `vragenlijstid`=?");
        if ($result1) {
            $result1->bind_param("iisiiiiisii", $eetlust, $dieet, $dieetWelk, $gewichtVerandert, $moeilijkSlikken, $gebitsProblemen, $gebitsProthese, $huidProblemen, $gevoel, $observatie, $vragenlijstId);
            $result1->execute();
        } else {
            // Handle error
            echo "Error preparing statement: " . DatabaseConnection::getConn()->error;
        }
    } else {
        //hier insert je alle data in patroon02
        $dieetWelk = "'$dieetWelk'";
        $result2 = DatabaseConnection::getConn()->prepare("INSERT INTO `patroon02voedingstofwisseling`(
                `vragenlijstid`,
                `eetlust`,
                `dieet`,
                `dieet_welk`,
                `gewicht_verandert`,
                `moeilijk_slikken`,
                `gebitsproblemen`,
                `gebitsprothese`,
                `huidproblemen`,
                `gevoel`,
                `observatie`)
            VALUES (
                    ?,
                    ?,
                    ?,
                    ?,
                    ?,
                    ?,
                    ?,
                    ?,
                    ?,
                    ?,
                    ?)");
        $result2->bind_param("iiisiiiiiis", $vragenlijstId, $eetlust, $dieet, $dieetWelk, $gewichtVerandert, $moeilijkSlikken, $gebitsProblemen, $gebitsProthese, $huidProblemen, $gevoel, $observatie);
        $result2->execute();
        $result2 = $result2->get_result();
    }
    switch ($_REQUEST['navbutton']) {
        case 'next': //action for next here
            header('Location: patroon03.php');
            break;

        case 'prev': //action for previous here
            header('Location: patroon01.php');
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
    <link rel="Stylesheet" href="../../assets/css/client/patronen.css">
    <link rel="stylesheet" href="../../assets/css/bootstrap.min.css">
    <title>Anamnese</title>
</head>

<body style="overflow: hidden;">
    <form action="" method="post">
        <div class="main">
            <?php
            include '../../includes/n-header.php';
            include '../../includes/n-sidebar.php';
            ?>

            <div class="mt-5 pt-5 content">
                <div class="mt-4 mb-3 bg-white p-3" style="height: 90%; overflow: auto;">
                    <p class="card-text">
                    <div class="form-content">
                        <div class="h4 text-primary">2. Voedings- en stofwisselingspatroon</div>
                        <div class="form">
                            <div class="questionnaire">
                                <div class="question">
                                    <p>Hoe is uw eetlust nu?</p>
                                    <div class="checkboxes">
                                        <p>
                                            <input type="radio" value="0" name="eetlust" <?= !isset($antwoorden['eetlust']) ? "checked" : "" ?>>
                                            <label>Normaal</label>
                                        </p>
                                        <p>
                                            <input type="radio" value="1" name="eetlust" <?= isset($antwoorden['eetlust']) ? "checked" : "" ?>>
                                            <label>Slecht</label>
                                        </p>
                                        <p>
                                            <input type="radio" value="2" name="eetlust" <?= isset($antwoorden['eetlust']) == 2 ? "checked" : ""  ?>>
                                            <label>Overmatig</label>
                                        </p>
                                    </div>
                                </div>
                                <div class="question">
                                    <p>- Heeft u een dieet?</p>
                                    <div class="checkboxes">
                                        <div class="question-answer">
                                            <input id="radio" type="radio" value="1" name="dieet" <?= isset($antwoorden['dieet']) ? "checked" : "" ?>>
                                            <label>Ja</label>
                                            <textarea rows="1" cols="25" id="checkfield" type="text" name="dieet_welk"
                                                placeholder="en wel?"><?= isset($antwoorden['dieet_welk']) ? $antwoorden['dieet_welk'] : '' ?></textarea>
                                        </div>
                                        <p>
                                            <input type="radio" value="0" name="dieet" <?= !isset($antwoorden['dieet']) ? "checked" : "" ?>>
                                            <label>Nee</label>
                                        </p>
                                    </div>
                                </div>
                                <div class="question">
                                    <p>- Is uw gewicht de laatste tijd veranderd?</p>
                                    <div class="checkboxes">
                                        <p>
                                            <input type="radio" value="1" name="gewicht_verandert" <?= isset($antwoorden['gewicht_verandert']) ? "checked" : "" ?>>
                                            <label>Ja</label>
                                        </p>
                                        <p>
                                            <input type="radio" value="0" name="gewicht_verandert" <?= !isset($antwoorden['gewicht_verandert']) ? "checked" : "" ?>>
                                            <label>Nee</label>
                                        </p>
                                    </div>
                                </div>
                                <div class="question">
                                    <p>Heeft u moeite met slikken?</p>
                                    <div class="checkboxes">
                                        <p>
                                            <input type="radio" value="1" name="moeilijk_slikken" <?= isset($antwoorden['moeilijk_slikken']) ? "checked" : "" ?>>
                                            <label>Ja</label>
                                        </p>
                                        <p>
                                            <input type="radio" value="0" name="moeilijk_slikken" <?= !isset($antwoorden['moeilijk_slikken']) ? "checked" : "" ?>>
                                            <label>Nee</label>
                                        </p>
                                    </div>
                                </div>
                                <div class="question">
                                    <p>Heeft u gebitsproblemen?</p>
                                    <div class="checkboxes">
                                        <p>
                                            <input type="radio" value="1" name="gebitsproblemen" <?= isset($antwoorden['gebitsproblemen']) ? "checked" : "" ?>>
                                            <label>Ja</label>
                                        </p>
                                        <p>
                                            <input type="radio" value="0" name="gebitsproblemen" <?= !isset($antwoorden['gebitsproblemen']) ? "checked" : "" ?>>
                                            <label>Nee</label>
                                        </p>
                                    </div>
                                </div>
                                <div class="question">
                                    <p>- Heeft u een gebitsprothese?</p>
                                    <div class="checkboxes">
                                        <p>
                                            <input type="radio" value="1" name="gebitsprothese" <?= isset($antwoorden['gebitsprothese']) ? "checked" : "" ?>>
                                            <label>Ja</label>
                                        </p>
                                        <p>
                                            <input type="radio" value="0" name="gebitsprothese" <?= !isset($antwoorden['gebitsprothese']) ? "checked" : "" ?>>
                                            <label>Nee</label>
                                        </p>
                                    </div>
                                </div>
                                <div class="question">
                                    <p>Heeft u huidproblemen?</p>
                                    <div class="checkboxes">
                                        <p>
                                            <input type="radio" value="1" name="huidproblemen" <?= isset($antwoorden['huidproblemen']) ? "checked" : "" ?>>
                                            <label>Ja</label>
                                        </p>
                                        <p>
                                            <input type="radio" value="0" name="huidproblemen" <?= !isset($antwoorden['huidproblemen']) ? "checked" : "" ?>>
                                            <label>Nee</label>
                                        </p>
                                    </div>
                                </div>
                                <div class="question">
                                    <p>Heeft u het koud of warm?</p>
                                    <div class="checkboxes">
                                        <p>
                                            <input type="radio" value="0" name="gevoel" <?= !isset($antwoorden['gevoel']) ? "checked" : "" ?>>
                                            <label>Normaal</label>
                                        </p>
                                        <p>
                                            <input type="radio" value="1" name="gevoel" <?= isset($antwoorden['gevoel']) ? "checked" : "" ?>>
                                            <label>Koud</label>
                                        </p>
                                        <p>
                                            <input type="radio" value="2" name="gevoel" <?= isset($antwoorden['gevoel']) == 2 ? "checked" : "" ?>>
                                            <label>Warm</label>
                                        </p>
                                    </div>
                                </div>


                                <div class=" observation">
                                    <h2>Verpleegkundige observatie bij dit patroon</h2>
                                    <div class="question">
                                        <div class="observe"><input type="checkbox" value="1" name="observatie1" <?= isset($boolArrayObservatie[0]) ? "checked" : "" ?>>
                                            <p>(Dreigend) voedingsteveel (zwaarlijvigheid)</p>
                                        </div>
                                    </div>
                                    <div class="question">
                                        <div class="observe"><input type="checkbox" value="1" name="observatie2" <?= isset($boolArrayObservatie[1]) ? "checked" : "" ?>>
                                            <p>Voedingstekort</p>
                                        </div>
                                    </div>
                                    <div class="question">
                                        <div class="observe"><input type="checkbox" value="1" name="observatie3" <?= isset($boolArrayObservatie[2]) ? "checked" : "" ?>>
                                            <p>(Dreigend) vochttekort</p>
                                        </div>
                                    </div>
                                    <div class="question">
                                        <div class="observe"><input type="checkbox" value="1" name="observatie4" <?= isset($boolArrayObservatie[3]) ? "checked" : "" ?>>
                                            <p>Falende warmteregulatie</p>
                                        </div>
                                    </div>
                                    <div class="question">
                                        <div class="observe"><input type="checkbox" value="1" name="observatie5" <?= isset($boolArrayObservatie[4]) ? "checked" : "" ?>>
                                            <p>Aspiratiegevaar</p>
                                        </div>
                                    </div>
                                    <div class="question">
                                        <div class="observe"><input type="checkbox" value="1" name="observatie6" <?= isset($boolArrayObservatie[5]) ? "checked" : "" ?>>
                                            <p>(Dreigende) huiddefect</p>
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
</body>

</html>