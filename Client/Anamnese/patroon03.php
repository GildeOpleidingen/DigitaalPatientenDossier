<?php
session_start();
include '../../Database/DatabaseConnection.php';
include '../../Functions/Functions.php';

$antwoorden = getPatternAnswers($_SESSION['clientId'], 3);

$boolArrayObservatie = str_split($antwoorden['observatie']);

$medewerkerId = $_SESSION['loggedin_id'];

if (isset($_REQUEST['navbutton'])) {
    $ontlasting_probleem = $_POST['ontlasting_probleem'];
    $op_welke = $_POST['op_welke'];
    $op_preventie = ($_POST['op_preventie']);
    $op_medicijnen = $_POST['op_medicijnen'];
    $op_medicijnen_welke = $_POST['op_medicijnen_welke'];
    $urineer_probleem = $_POST['urineer_probleem'];
    $up_incontinentie = $_POST['up_incontinentie'];
    $up_incontinentie_behandeling = $_POST['up_incontinentie_behandeling'];
    $up_incontinentie_behandeling_welke = $_POST['up_incontinentie_behandeling_welke'];
    $transpiratie = $_POST['transpiratie'];
    $transpiratie_welke = $_POST['transpiratie_welke'];

// array van checkboxes van observatie tab
    $observatie = array(!empty($_POST['observatie1']), !empty($_POST['observatie2']), !empty($_POST['observatie3']), !empty($_POST['observatie4']), !empty($_POST['observatie5']), !empty($_POST['observatie6']), !empty($_POST['observatie7']), !empty($_POST['observatie8']), !empty($_POST['observatie9']), !empty($_POST['observatie10']));

    $observatie = convertBoolArrayToString($observatie);

    $result = DatabaseConnection::getConn()->prepare("
                    SELECT vl.id
                    from vragenlijst vl
                    left join verzorgerregel on verzorgerregel.id = vl.verzorgerregelid
                    where verzorgerregel.clientid = ?");
    $result->bind_param("i", $_SESSION['clientId']);
    $result->execute();
    $result = $result->get_result()->fetch_assoc();

    if ($result != null){
        $vragenlijstId = $result['id'];
    } else {
        $sql2 = DatabaseConnection::getConn()->prepare("INSERT INTO `vragenlijst`(`verzorgerregelid`)
            VALUES ((SELECT id
            FROM verzorgerregel
            WHERE clientid = ?
            AND medewerkerid = ?))");
        $sql2->bind_param("ii", $_SESSION['clientId'] ,$medewerkerId);
        $sql2->execute();
        $sql2 = $sql2->get_result();

        $result = DatabaseConnection::getConn()->prepare("SELECT vl.id
                    from vragenlijst vl
                    left join verzorgerregel on verzorgerregel.id = vl.verzorgerregelid
                    where verzorgerregel.clientid = ?");
        $result->bind_param("i", $_SESSION['clientId']);
        $result->execute();
        $result = $result->get_result()->fetch_assoc();

        $vragenlijstId=$result['id'];
    }

    // kijken of patroon02 bestaat door te kijken naar vragenlijst id
    $result = DatabaseConnection::getConn()->prepare("
                    SELECT p.id
                    FROM patroon03uitscheiding p
                    WHERE p.vragenlijstid =  ?");
    $result->bind_param("i", $vragenlijstId);
    $result->execute();
    $result = $result->get_result()->fetch_assoc();

    if ($result != null) {
        //update
        $result1 = DatabaseConnection::getConn()->prepare("UPDATE `patroon03uitscheiding` SET
        `ontlasting_probleem`=?,
        `op_welke`=?,
        `op_preventie`=?,
        `op_medicijnen`=?,
        `op_medicijnen_welke`=?,
        `urineer_probleem`= ?,
        `up_incontinentie`=?,
        `up_incontinentie_behandeling`=?,
        `up_incontinentie_behandeling_welke`=?,
        `transpiratie`=?,
        `transpiratie_welke`=?,
        `observatie`=?
           WHERE `vragenlijstid`=?");
        $result1->bind_param("issisiiisissi", $ontlasting_probleem, $op_welke, $op_preventie, $op_medicijnen, $op_medicijnen_welke, $urineer_probleem, $up_incontinentie, $up_incontinentie_behandeling, $up_incontinentie_behandeling_welke, $transpiratie, $transpiratie_welke, $observatie,$vragenlijstId);
        $result1->execute();
        $result1 = $result1->get_result();

    }else{
        //hier insert je alle data in patroon02

        $result2 = DatabaseConnection::getConn()->prepare( "INSERT INTO `patroon03uitscheiding`(
                `vragenlijstid`,
                `ontlasting_probleem`,
                `op_welke`,
                `op_preventie`,
                `op_medicijnen`,
                `op_medicijnen_welke`,
                `urineer_probleem`,
                `up_incontinentie`,
                `up_incontinentie_behandeling`,
                `up_incontinentie_behandeling_welke`,
                `transpiratie`,
                `transpiratie_welke`,
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
                    ?,
                    ?,
                    ?)");
        $result2->bind_param("iissisiiisiss", $vragenlijstId, $ontlasting_probleem, $op_welke, $op_preventie, $op_medicijnen, $op_medicijnen_welke, $urineer_probleem, $up_incontinentie, $up_incontinentie_behandeling, $up_incontinentie_behandeling_welke, $transpiratie, $transpiratie_welke, $observatie);
        $result2->execute();
        $result2 = $result2->get_result();

    }
    switch ($_REQUEST['navbutton']) {
        case 'next': //action for next here
            header('Location: patroon04.php');
            break;

        case 'prev': //action for previous here
            header('Location: patroon02.php');
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
            <div class="pages">3 Uitscheidingspatroon</div>
                <div class="form">
                    <div class="questionnaire">
                        <div class="question"><p>Heeft u problemen met ontlasting?</p>
                            <div class="checkboxes">
                                <div class="question-answer">
                                    <input id="radio" type="radio" name="ontlasting_probleem" <?= $antwoorden['ontlasting_probleem'] ? "checked" : "" ?>>
                                    <label>Ja</label>
                                    <textarea  rows="1" cols="25" id="checkfield" type="text" placeholder="en wel?" name="op_welke"><?= $antwoorden['op_welke']?></textarea>
                                </div>
                                <p>
                                    <input type="radio" name="ontlasting_probleem" <?= !$antwoorden['ontlasting_probleem'] ? "checked" : "" ?>>
                                    <label>Nee</label>
                                </p>
                            </div>
                        </div>
                        <div class="question"><p>- Wat doet u om deze problemen te bestrijden?</p><textarea  rows="1" cols="25" type="text" name="op_preventie"><?= $antwoorden['op_preventie'] ?></textarea></div>
                        <div class="question"><p>- Gebruikt u iets om uw stoelgang te reguleren?</p>
                            <div class="checkboxes">
                                <div class="question-answer">
                                    <input id="radio" type="radio" name="op_medicijnen" <?= $antwoorden['op_medicijnen'] ? "checked" : "" ?>>
                                    <label>Ja</label>
                                    <textarea  rows="1" cols="25" id="checkfield" type="text" placeholder="en wel?" name="op_medicijnen_welke"><?= $antwoorden['op_medicijnen_welke']?></textarea>
                                </div>
                                <p>
                                    <input type="radio" name="op_medicijnen" <?= !$antwoorden['op_medicijnen'] ? "checked" : "" ?>>
                                    <label>Nee</label>
                                </p>
                            </div>
                        </div>
                        <div class="question"><p>Heeft u problemen ten aanzien van het urineren?</p>
                            <div class="checkboxes">
                                <p>    
                                    <input type="radio" name="urineer_probleem" <?= $antwoorden['urineer_probleem'] ? "checked" : "" ?>>
                                    <label>Ja</label>
                                </p>
                                <p>
                                    <input type="radio" name="urineer_probleem" <?= !$antwoorden['urineer_probleem'] ? "checked" : "" ?>>
                                    <label>Nee</label>
                                </p>
                            </div>
                        </div>
                        <div class="question"><p>- Heeft u last van incontinentie?</p>
                            <div class="checkboxes">
                                <p>    
                                    <input type="radio" name="up_incontinentie" <?= $antwoorden['up_incontinentie'] ? "checked" : "" ?>>
                                    <label>Ja</label>
                                </p>
                                <p>
                                    <input type="radio" name="up_incontinentie" <?= !$antwoorden['up_incontinentie'] ? "checked" : "" ?>>
                                    <label>Nee</label>
                                </p>
                            </div>
                        </div>
                        <div class="question"><p>- Bent u hiervoor in behandeling?</p>
                            <div class="checkboxes">
                                <div class="question-answer">
                                    <input id="radio" type="radio" name="up_incontinentie_behandeling" <?= $antwoorden['up_incontinentie_behandeling'] ? "checked" : "" ?>>
                                    <label>Ja</label>
                                    <textarea  rows="1" cols="25" id="checkfield" type="text" placeholder="en wel?" name="up_incontinentie_behandeling_welke"><?= $antwoorden['up_incontinentie_behandeling_welke'] ?></textarea>
                                </div>
                                <p>
                                    <input type="radio" name="up_incontinentie_behandeling" <?= !$antwoorden['up_incontinentie_behandeling'] ? "checked" : "" ?>>
                                    <label>Nee</label>
                                </p>
                            </div>
                        </div>
                        <div class="question"><p>Hebt u last van transpiratie?</p>
                            <div class="checkboxes">
                                <div class="question-answer">
                                    <input id="radio" type="radio" name="transpiratie" <?= $antwoorden['transpiratie'] ? "checked" : "" ?>>
                                    <label>Ja</label>
                                    <textarea  rows="1" cols="25" id="checkfield" type="text" placeholder="en wel?" name="transpiratie_welke"><?= $antwoorden['transpiratie_welke'] ?></textarea>
                                </div>
                                <p>
                                    <input type="radio" name="transpiratie" <?= !$antwoorden['transpiratie'] ? "checked" : "" ?>>
                                    <label>Nee</label>
                                </p>
                            </div>
                        </div>

                        <div class="observation">
                            <h2>Verpleegkundige observatie bij dit patroon</h2>
                            <div class="question"><div class="observe"><input type="checkbox" <?= $boolArrayObservatie[0] ? "checked" : ""?> name="observatie1"><p>Colon-obstipatie</p></div></div>
                            <div class="question"><div class="observe"><input type="checkbox" <?= $boolArrayObservatie[1] ? "checked" : ""?> name="observatie2"><p>Subjectief ervaren obstipatie</p></div></div>
                            <div class="question"><div class="observe"><input type="checkbox" <?= $boolArrayObservatie[2] ? "checked" : ""?> name="observatie3"><p>Diarree</p></div></div>
                            <div class="question"><div class="observe"><input type="checkbox" <?= $boolArrayObservatie[3] ? "checked" : ""?> name="observatie4"><p>Incontinentie van feces</p></div></div>
                            <div class="question"><div class="observe"><input type="checkbox" <?= $boolArrayObservatie[4] ? "checked" : ""?> name="observatie5"><p>Verstoorde urine-uitscheiding</p></div></div>
                            <div class="question"><div class="observe"><input type="checkbox" <?= $boolArrayObservatie[5] ? "checked" : ""?> name="observatie6"><p>Functionele urine-incontinentie</p></div></div>
                            <div class="question"><div class="observe"><input type="checkbox" <?= $boolArrayObservatie[6] ? "checked" : ""?> name="observatie7"><p>Reflex-urine-incontinentie</p></div></div>
                            <div class="question"><div class="observe"><input type="checkbox" <?= $boolArrayObservatie[7] ? "checked" : ""?> name="observatie8"><p>Stress-urine-incontinentie</p></div></div>
                            <div class="question"><div class="observe"><input type="checkbox" <?= $boolArrayObservatie[8] ? "checked" : ""?> name="observatie9"><p>Volledige urine-incontinentie</p></div></div>
                            <div class="question"><div class="observe"><input type="checkbox" <?= $boolArrayObservatie[9] ? "checked" : ""?> name="observatie10"><p>Urineretentie</p></div></div>
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