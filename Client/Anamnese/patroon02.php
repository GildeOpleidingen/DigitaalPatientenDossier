<?php
session_start();
include '../../Database/DatabaseConnection.php';

$id = $_GET['id'];

if (isset($_REQUEST['navbutton'])) {
        $eetlust = $_POST['eetlust'];
        $dieet = $_POST['dieet'];
        $dieet_welk = $_POST['dieet_welk'];
        $gewicht_verandert = $_POST['gewicht_verandert'];
        $moeilijk_slikken = $_POST['moeilijk_slikken'];
        $gebitsproblemen = $_POST['gebitsproblemen'];
        $gebitsprothese = $_POST['gebitsprothese'];
        $huidproblemen = $_POST['huidproblemen'];
        $gevoel = $_POST['gevoel'];
        $observatie = $_POST['observatie'];
        
    $result = DatabaseConnection::getConn()->query("SELECT *
                from vragenlijst vl
                left join verzorgerregel on verzorgerregel.id = vl.verzorgerregelid
                where verzorgerregel.clientid = $id");
            
    if (!empty($result)){   
        //TODO: here action to save data in the database.
        $result1 = DatabaseConnection::getConn()->query("UPDATE `patroon02voedingstofwisseling` 
            SET
            `eetlust`='$eetlust',
            `dieet`='$dieet',
            `dieet_welk`='$dieet_welk',
            `gewicht_verandert`='$gewicht_verandert',
            `moeilijk_slikken`='$moeilijk_slikken',
            `gebitsproblemen`='$gebitsproblemen',
            `gebitsprothese`='$gebitsprothese',
            `huidproblemen`='$huidproblemen',
            `gevoel`='$gevoel',
            `observatie`='$observatie' 
            WHERE `vragenlijstid`=(SELECT vl.id
            FROM verzorgerregel vr
            JOIN vragenlijst vl ON vl.verzorgerregelid = vr.id
            WHERE vr.clientid = $id
            AND vr.medewerkerid = 1)");
    } else {
        // Als vragenlijstid niet bestaat, maak een nieuwe aan
        $sql2 = "INSERT INTO `patroon02voedingstofwisseling`(
            `vragenlijstid`, 
            `eetlust`, 
            `dieet`, 
            `dieet_welk`, 
            `gewicht_verandert`, 
            `moeilijk_slikken`, 
            `gebitsproblemen`, `gebitsprothese`, 
            `huidproblemen`, `gevoel`, `observatie`) 
            VALUES (
                (SELECT vl.id
                FROM verzorgerregel vr
                JOIN vragenlijst vl ON vl.verzorgerregelid = vr.id
                WHERE vr.clientid = $id 
                AND vr.medewerkerid = 1),
                    '2',
                    $eetlust,
                    $dieet,
                    $dieet_welk,
                    $gewicht_verandert,
                    $moeilijk_slikken,
                    $gebitsproblemen,
                    $gebitsprothese,
                    $huidproblemen,
                    $gevoel,
                    $observatie)";
        $result2 = DatabaseConnection::getConn()->query($sql2);
                      
    }

    switch ($_REQUEST['navbutton']) {
        case 'next': //action for next here
            header('Location: patroon03.php?id=' . $id);
            break;

        case 'prev': //action for previous here
            header('Location: patroon01.php?id=' . $id);
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
        ?>
            <?php
        include '../../Includes/sidebar.php';
        ?>

            <div class="main-content">
                <div class="content">
                    <div class="form-content">
                        <div class="pages">2 Voedings- en stofwisselingspatroon</div>
                        <div class="form">
                            <div class="questionnaire">
                                <div class="question">
                                    <p>Hoe is uw eetlust nu?</p>
                                    <div class="checkboxes">
                                        <p>
                                            <input type="radio" name="eetlust">
                                            <label>Normaal</label>
                                        </p>
                                        <p>
                                            <input type="radio" name="eetlust">
                                            <label>Slecht</label>
                                        </p>
                                        <p>
                                            <input type="radio" name="eetlust">
                                            <label>Overmatig</label>
                                        </p>
                                    </div>
                                </div>
                                <div class="question">
                                    <p>- Heeft u een dieet?</p>
                                    <div class="checkboxes">
                                        <div class="question-answer">
                                            <input id="radio" type="radio" name="dieet">
                                            <label>Ja</label>
                                            <textarea rows="1" cols="25" id="checkfield" type="text" name="dieet_welk"
                                                placeholder="en wel?"></textarea>
                                        </div>
                                        <p>
                                            <input type="radio" name="dieet">
                                            <label>Nee</label>
                                        </p>
                                    </div>
                                </div>
                                <div class="question">
                                    <p>- Is uw gewicht de laatste tijd veranderd?</p>
                                    <div class="checkboxes">
                                        <p>
                                            <input type="radio" name="gewicht_verandert">
                                            <label>Ja</label>
                                        </p>
                                        <p>
                                            <input type="radio" name="gewicht_verandert">
                                            <label>Nee</label>
                                        </p>
                                    </div>
                                </div>
                                <div class="question">
                                    <p>Heeft u moeite met slikken?</p>
                                    <div class="checkboxes">
                                        <p>
                                            <input type="radio" name="moeilijk_slikken">
                                            <label>Ja</label>
                                        </p>
                                        <p>
                                            <input type="radio" name="moeilijk_slikken">
                                            <label>Nee</label>
                                        </p>
                                    </div>
                                </div>
                                <div class="question">
                                    <p>Heeft u gebitsproblemen?</p>
                                    <div class="checkboxes">
                                        <p>
                                            <input type="radio" name="gebitsproblemen">
                                            <label>Ja</label>
                                        </p>
                                        <p>
                                            <input type="radio" name="gebitsproblemen">
                                            <label>Nee</label>
                                        </p>
                                    </div>
                                </div>
                                <div class="question">
                                    <p>- Heeft u een gebitsprothese?</p>
                                    <div class="checkboxes">
                                        <p>
                                            <input type="radio" name="gebitsprothese">
                                            <label>Ja</label>
                                        </p>
                                        <p>
                                            <input type="radio" name="gebitsprothese">
                                            <label>Nee</label>
                                        </p>
                                    </div>
                                </div>
                                <div class="question">
                                    <p>Heeft u huidproblemen?</p>
                                    <div class="checkboxes">
                                        <p>
                                            <input type="radio" name="huidproblemen">
                                            <label>Ja</label>
                                        </p>
                                        <p>
                                            <input type="radio" name="huidproblemen">
                                            <label>Nee</label>
                                        </p>
                                    </div>
                                </div>
                                <div class="question">
                                    <p>Heeft u het koud of warm?</p>
                                    <div class="checkboxes">
                                        <p>
                                            <input type="radio" name="gevoel">
                                            <label>Normaal</label>
                                        </p>
                                        <p>
                                            <input type="radio" name="gevoel">
                                            <label>Koud</label>
                                        </p>
                                        <p>
                                            <input type="radio" name="gevoel">
                                            <label>Warm</label>
                                        </p>
                                    </div>
                                </div>


                                <div class=" observation">
                                    <h2>Verpleegkundige observatie bij dit patroon</h2>
                                    <div class="question">
                                        <div class="observe"><input type="checkbox" name="observatie">
                                            <p>(Dreigend) voedingsteveel (zwaarlijvigheid)</p>
                                        </div>
                                    </div>
                                    <div class="question">
                                        <div class="observe"><input type="checkbox" name="observatie">
                                            <p>Voedingstekort</p>
                                        </div>
                                    </div>
                                    <div class="question">
                                        <div class="observe"><input type="checkbox" name="observatie">
                                            <p>(Dreigend) vochttekort</p>
                                        </div>
                                    </div>
                                    <div class="question">
                                        <div class="observe"><input type="checkbox" name="observatie">
                                            <p>Falende warmteregulatie</p>
                                        </div>
                                    </div>
                                    <div class="question">
                                        <div class="observe"><input type="checkbox" name="observatie">
                                            <p>Aspiratiegevaar</p>
                                        </div>
                                    </div>
                                    <div class="question">
                                        <div class="observe"><input type="checkbox" name="observatie">
                                            <p>(Dreigende) huiddefect</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="submit">
                            <button name="navbutton" type="submit" value="prev">
                                Vorige</button>
                            <button name="navbutton" type="submit" value="next">Volgende</button>
                        </div>
                    </div>
                </div>
    </form>
    </div>
    </div>

</body>

</html>