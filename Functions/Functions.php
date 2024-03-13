<?php

function convertNumToBool($numbers, $index=0): ?bool {
    if ($index < mb_strlen($numbers)) {
        $num = str_split($numbers)[$index];

        if ($num == 1) {
            return true;
        }

        return false;
    }

    return null;
}

function convertNumToBoolArray($numbers): array {
    $numArr = str_split($numbers);
    $boolArr = array();

    foreach ($numArr as $num) {
        if ($num == 1) {
            $boolArr[] = true;
            continue;
        }
        $boolArr[] = false;
    }

    return $boolArr;
}

function convertBoolArrayToString($boolArr): string {
    $numbers = '';
    foreach ($boolArr as $bool) {
        $numbers .= $bool ? '1' : '0';
    }
    return $numbers;
}

/**
 * Haalt alle antwoorden op uit de database van een anamnese patroon
 *
 * @return      array|false
 */

function getPatternAnswers(int $clientId, int $patroonType) {
    // Patroontypes array van de database om maar 1 functie nodig te hebben
    $patroonTypes = [
        "patroon01gezondheidsbeleving",
        "patroon02voedingstofwisseling",
        "patroon03uitscheiding",
        "patroon04activiteiten",
        "patroon05slaaprust",
        "patroon06cognitiewaarneming",
        "patroon07zelfbeleving",
        "patroon08rollenrelatie",
        "patroon09seksualiteitvoorplanting",
        "patroon10stressverwerking",
        "patroon11waardelevensovertuiging"
    ];

    $patroon = $patroonTypes[$patroonType - 1];
    // Haal verzorgerregelid op via de clientid
    $result = DatabaseConnection::getConn()->prepare("
    SELECT vl.id
    FROM vragenlijst vl
    LEFT JOIN verzorgerregel ON verzorgerregel.id = vl.verzorgerregelid
    WHERE verzorgerregel.clientid = ?
    ");
    $result->bind_param("i", $clientId);
    $result->execute();
    $result = $result->get_result()->fetch_assoc();
    if(isset($result['id'])){
        $vlId = $result['id'];
        $result = DatabaseConnection::getConn()->prepare("SELECT * FROM $patroon WHERE vragenlijstid = ?");
        $result->bind_param("i", $vlId);
        $result->execute();
        $antwoorden = (array) $result->get_result()->fetch_array(MYSQLI_ASSOC);
        if(empty($antwoorden)) {
            // Dit is zodat als je geen data hebt dan krijg je geen error van undefined array key
            $result = DatabaseConnection::getConn()->query("
            SELECT COLUMN_NAME
            FROM INFORMATION_SCHEMA.COLUMNS
            WHERE TABLE_NAME = '$patroon'");
            $columns = $result->fetch_all(MYSQLI_ASSOC);
            foreach($columns as $column) {
                if($column['COLUMN_NAME'] == 'observatie') {
                    // Dit is echt een hacky manier om de error te fixen voor observatie als je een andere manier hebt aub zeggen
                    $antwoorden[$column['COLUMN_NAME'] ?? ""] = "00000000000000000000";
                }else{
                    $antwoorden[$column['COLUMN_NAME']] = "";
                }
            }
        }
        foreach($antwoorden as $antwoord) {
            if(!isset($antwoord)) {
                $antwoord = "";
            }
        }

        return $antwoorden;
    }else{
        $result = DatabaseConnection::getConn()->prepare("SELECT * FROM `verzorgerregel` WHERE clientid = ?;");
        $result->bind_param("i", $clientId);
        $result->execute();
        $verzorgerregelid = $result->get_result()->fetch_array(MYSQLI_ASSOC)['id'];

        $today = date('Y-m-d H:i:s');
        $vragenlijst = DatabaseConnection::getConn()->prepare("INSERT INTO `vragenlijst`(`id`, `verzorgerregelid`, `afnamedatumtijd`) VALUES (NULL, ?, ?)");
        $vragenlijst->bind_param("is", $verzorgerregelid, $today);
        $vragenlijst->execute();

        header("Refresh:0");
        die;
    }
}