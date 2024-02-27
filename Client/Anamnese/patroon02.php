<?php
session_start();
include '../../Database/DatabaseConnection.php';
include '../../Functions/Functions.php';

$id = $_GET['id'];

if (isset($_REQUEST['navbutton'])) {
        $eetlust = $_POST['eetlust'];
        $dieet = $_POST['dieet'];
        $dieet_welk = strval($_POST['dieet_welk']);
        $gewicht_verandert = $_POST['gewicht_verandert'];
        $moeilijk_slikken = $_POST['moeilijk_slikken'];
        $gebitsproblemen = $_POST['gebitsproblemen'];
        $gebitsprothese = $_POST['gebitsprothese'];
        $huidproblemen = $_POST['huidproblemen'];
        $gevoel = $_POST['gevoel'];
        $observatie = $_POST['observatie'];

        $result = DatabaseConnection::getConn()->prepare("SELECT vl.id
                    from vragenlijst vl
                    left join verzorgerregel on verzorgerregel.id = vl.verzorgerregelid
                    where verzorgerregel.clientid = $id");
    $result->execute();
    $result = $result->get_result()->fetch_assoc();

    if ($result != null){
        //TODO: here action to save data in the database.
        $id_vl=$result['id'];
//
    } else  {
        // Als vragenlijstid niet bestaat, maak een nieuwe aan

        $sql2 = "INSERT INTO `vragenlijst`(`verzorgerregelid`)
            VALUES ((SELECT id
            FROM verzorgerregel
            WHERE clientid = $id
            AND medewerkerid = 1))";


        $result = DatabaseConnection::getConn()->prepare("SELECT vl.id
                    from vragenlijst vl
                    left join verzorgerregel on verzorgerregel.id = vl.verzorgerregelid
                    where verzorgerregel.clientid = $id");
        $result->execute();
        $result = $result->get_result()->fetch_assoc();

        $id_vl=$result['id'];
//    $result2 = DatabaseConnection::getConn()->query($sql2);
//        //print_r($result->get_result()->fetch_array()['id']);
//
}

    // kijken of patroon02 bestaat door te kijken naar vragenlijst id
$result = DatabaseConnection::getConn()->prepare("SELECT p.id
                    from patroon02voedingstofwisseling p
                    where p.vragenlijstid = $id_vl");
$result->execute();
$result = $result->get_result()->fetch_assoc();

if ($result != null) {

    //update
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
            WHERE `vragenlijstid`=$id_vl");



}else{
//    print_r("INSERT INTO `patroon02voedingstofwisseling`(
//                `vragenlijstid`,
//                `eetlust`,
//                `dieet`,
//                `dieet_welk`,
//                `gewicht_verandert`,
//                `moeilijk_slikken`,
//                `gebitsproblemen`,
//                `gebitsprothese`,
//                `huidproblemen`,
//                `gevoel`,
//                `observatie`)
//            VALUES (
//                    $id_vl,
//                    $eetlust,
//                    $dieet,
//                    `$dieet_welk`,
//                    $gewicht_verandert,
//                    $moeilijk_slikken,
//                    $gebitsproblemen,
//                    $gebitsprothese,
//                    $huidproblemen,
//                    $gevoel,
//                    $observatie)");
    //insert
    $result2 = DatabaseConnection::getConn()->query( "INSERT INTO `patroon02voedingstofwisseling`(
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
                    $id_vl,
                    $eetlust,
                    $dieet,
                    '$dieet_welk',
                    $gewicht_verandert,
                    $moeilijk_slikken,
                    $gebitsproblemen,
                    $gebitsprothese,
                    $huidproblemen,
                    $gevoel,
                    $observatie)");


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
                                            <input type="radio" value=1 name="eetlust">
                                            <label>Normaal</label>
                                        </p>
                                        <p>
                                            <input type="radio" value=2 name="eetlust">
                                            <label>Slecht</label>
                                        </p>
                                        <p>
                                            <input type="radio" value=1 name="eetlust">
                                            <label>Overmatig</label>
                                        </p>
                                    </div>
                                </div>
                                <div class="question">
                                    <p>- Heeft u een dieet?</p>
                                    <div class="checkboxes">
                                        <div class="question-answer">
                                            <input id="radio" type="radio" value=1 name="dieet">
                                            <label>Ja</label>
                                            <textarea rows="1" cols="25" id="checkfield" type="text" name="dieet_welk"
                                                placeholder="en wel?"></textarea>
                                        </div>
                                        <p>
                                            <input type="radio" value=0 name="dieet">
                                            <label>Nee</label>
                                        </p>
                                    </div>
                                </div>
                                <div class="question">
                                    <p>- Is uw gewicht de laatste tijd veranderd?</p>
                                    <div class="checkboxes">
                                        <p>
                                            <input type="radio" value=1 name="gewicht_verandert">
                                            <label>Ja</label>
                                        </p>
                                        <p>
                                            <input type="radio" value=0 name="gewicht_verandert">
                                            <label>Nee</label>
                                        </p>
                                    </div>
                                </div>
                                <div class="question">
                                    <p>Heeft u moeite met slikken?</p>
                                    <div class="checkboxes">
                                        <p>
                                            <input type="radio" value=1 name="moeilijk_slikken">
                                            <label>Ja</label>
                                        </p>
                                        <p>
                                            <input type="radio" value=0 name="moeilijk_slikken">
                                            <label>Nee</label>
                                        </p>
                                    </div>
                                </div>
                                <div class="question">
                                    <p>Heeft u gebitsproblemen?</p>
                                    <div class="checkboxes">
                                        <p>
                                            <input type="radio" value=1 name="gebitsproblemen">
                                            <label>Ja</label>
                                        </p>
                                        <p>
                                            <input type="radio" value=0 name="gebitsproblemen">
                                            <label>Nee</label>
                                        </p>
                                    </div>
                                </div>
                                <div class="question">
                                    <p>- Heeft u een gebitsprothese?</p>
                                    <div class="checkboxes">
                                        <p>
                                            <input type="radio" value=1 name="gebitsprothese">
                                            <label>Ja</label>
                                        </p>
                                        <p>
                                            <input type="radio" value=0 name="gebitsprothese">
                                            <label>Nee</label>
                                        </p>
                                    </div>
                                </div>
                                <div class="question">
                                    <p>Heeft u huidproblemen?</p>
                                    <div class="checkboxes">
                                        <p>
                                            <input type="radio" value=1 name="huidproblemen">
                                            <label>Ja</label>
                                        </p>
                                        <p>
                                            <input type="radio" value=0 name="huidproblemen">
                                            <label>Nee</label>
                                        </p>
                                    </div>
                                </div>
                                <div class="question">
                                    <p>Heeft u het koud of warm?</p>
                                    <div class="checkboxes">
                                        <p>
                                            <input type="radio" value=0 name="gevoel">
                                            <label>Normaal</label>
                                        </p>
                                        <p>
                                            <input type="radio" value=1 name="gevoel">
                                            <label>Koud</label>
                                        </p>
                                        <p>
                                            <input type="radio" value=2 name="gevoel">
                                            <label>Warm</label>
                                        </p>
                                    </div>
                                </div>


                                <div class=" observation">
                                    <h2>Verpleegkundige observatie bij dit patroon</h2>
                                    <div class="question">
                                        <div class="observe"><input type="checkbox" value=0 name="observatie">
                                            <p>(Dreigend) voedingsteveel (zwaarlijvigheid)</p>
                                        </div>
                                    </div>
                                    <div class="question">
                                        <div class="observe"><input type="checkbox" value=1 name="observatie">
                                            <p>Voedingstekort</p>
                                        </div>
                                    </div>
                                    <div class="question">
                                        <div class="observe"><input type="checkbox" value=1 name="observatie">
                                            <p>(Dreigend) vochttekort</p>
                                        </div>
                                    </div>
                                    <div class="question">
                                        <div class="observe"><input type="checkbox" value=1 name="observatie">
                                            <p>Falende warmteregulatie</p>
                                        </div>
                                    </div>
                                    <div class="question">
                                        <div class="observe"><input type="checkbox" value=1 name="observatie">
                                            <p>Aspiratiegevaar</p>
                                        </div>
                                    </div>
                                    <div class="question">
                                        <div class="observe"><input type="checkbox" value=1 name="observatie">
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