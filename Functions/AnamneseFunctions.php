<?php

/**
 * haalt de vragenlijst op gebaseerd op het megegeven clientid, returned het id van de vragenlijst.
 *
 * @return int|null
 */
function getVragenlijstId($clientId): int|null
{
    $result = DatabaseConnection::getConn()->prepare("
                    SELECT vl.id
                    from vragenlijst vl
                    left join verzorgerregel on verzorgerregel.id = vl.verzorgerregelid
                    where verzorgerregel.clientid = ?");
    $result->bind_param("i", $clientId);
    $result->execute();
    $result = $result->get_result()->fetch_assoc();

    return $result['id'];
}

/**
 * insert een nieuwe vragenlijst voor de meegegeven client/medewerkerid
 *
 * @return int|null
 */
function insertVragenlijst($clientId, $medewerkerId): int|null {
    $result = DatabaseConnection::getConn()->prepare("INSERT INTO `vragenlijst`(`verzorgerregelid`)
            VALUES ((SELECT id
            FROM verzorgerregel
            WHERE clientid = ?
            AND medewerkerid = ?))");
    $result->bind_param("ii", $clientId ,$medewerkerId);
    $result->execute();
    $result = $result->get_result();

    return $result['id'];
}

/**
 * Kijkt of er al een patroon bestaat met het megegeven vragenlijstid
 *
 * @return array|null
 */
function checkIfPatternExists($vragenlijstId): array|null
{
    $result = DatabaseConnection::getConn()->prepare("
                    SELECT p.id
                    FROM patroon08rollenrelatie p
                    WHERE p.vragenlijstid =  ?");
    $result->bind_param("i", $vragenlijstId);
    $result->execute();
    $result = $result->get_result()->fetch_assoc();

    return $result;
}