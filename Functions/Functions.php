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

function getPatternAnswers(int $clientId, int $patroonType): array|false {
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
        $result = DatabaseConnection::getConn()->prepare("SELECT * FROM $patroon WHERE vragenlijstid = $vlId;");
        $result->execute();
        return (array) $result->get_result()->fetch_array(MYSQLI_ASSOC);
    }else{
        return false;
    }
}