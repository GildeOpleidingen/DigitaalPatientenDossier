<?php
// Om deze functies te gebruiken moet je op de pagina waar je ze wilt gebruiken de databaseConnection includen en deze file includen
function updateClient($naam, $geslacht, $adres, $postcode, $woonplaats, $telefoonnummer, $email, $reanimatiestatus, $nationaliteit, $afdeling, $burgelijkestaat, $foto): bool {
    $conn = DatabaseConnection::getConn();
    $conn->query("UPDATE `client` SET `naam`='$naam',`geslacht`='$geslacht',`adres`='$adres',`postcode`='$postcode',`woonplaats`='$woonplaats',`telefoonnummer`='$telefoonnummer',`email`='$email',`reanimatiestatus`='$reanimatiestatus',`nationaliteit`='$nationaliteit',`afdeling`='$afdeling',`burgelijkestaat`='$burgelijkestaat',`foto`='$foto' WHERE `naam`='$naam';");

    if ($conn->affected_rows == 1)
        return true;

    if ($conn->affected_rows <= 0) {
        $result = $conn->query("SELECT * FROM `client` WHERE naam='$naam'")->fetch_all();
        if (sizeof($result) == 0)    {
            $conn->query("INSERT INTO `client`(`naam`, `geslacht`, `adres`, `postcode`, `woonplaats`, `telefoonnummer`, `email`, `reanimatiestatus`, `nationaliteit`, `afdeling`, `burgelijkestaat`, `foto`) VALUES ('$naam','$geslacht','$adres','$postcode','$woonplaats','$telefoonnummer','$email','$reanimatiestatus','$nationaliteit','$afdeling','$burgelijkestaat','$foto');");
            return true;
        }
    }

    return false;
}

function checkIfClientExistsById(int $id): bool {
    $result = getClientById($id);

    return sizeof((array) $result) > 0;
}

function checkIfClientExistsByName(string $name): bool {
    $result = getClientByName($name);

    return sizeof((array) $result) > 0;
}

function getClientById($id): array {
    $result = DatabaseConnection::getConn()->prepare("SELECT * FROM `client` WHERE id = ?;");
    $result->bind_param("i", $id);
    $result->execute();

    return (array) $result->get_result()->fetch_array();
}

function getClientByName($name): array {
    $result = DatabaseConnection::getConn()->prepare("SELECT * FROM `client` WHERE naam = ?;");
    $result->bind_param("s", $name);
    $result->execute();

    return (array) $result->get_result()->fetch_array();
}

function getMedischOverzichtByClientId($id): array {
    $result = DatabaseConnection::getConn()->prepare("
    SELECT mo.*
    FROM client c
    JOIN medischoverzicht mo on mo.clientid = c.id 
    where c.id = ?
    ");
    
    $result->bind_param("i", $id);
    $result->execute();

    return (array) $result->get_result()->fetch_array();
}

function getClientStoryByClientId($id): array {
    $result = DatabaseConnection::getConn()->prepare("
    SELECT cv.*
    FROM client c
    JOIN medischoverzicht mo on mo.clientid = c.id 
    join clientverhaal cv on cv.medischoverzichtid = mo.id
    where c.id = ?
    ");

    $result->bind_param("i", $id);
    $result->execute();

    return (array) $result->get_result()->fetch_array();
}

function insertClientStory($clientid, $foto, $introductie, $familie, $belangrijkeinfo, $hobbies): bool {
    $medischOverzicht = getMedischOverzichtByClientId($clientid);
    if(checkIfClientExistsById($clientid)){
        if(!checkIfClientStoryExistsByClientId($clientid)){
            $result = DatabaseConnection::getConn()->prepare("INSERT INTO `clientverhaal`(`id`, `medischoverzichtid`, `foto`, `introductie`, `gezinfamilie`, `belangrijkeinfo`, `hobbies`) VALUES (NULL, ?, ?, ?, ?, ?, ?);");
            $result->bind_param("isssss", $medischOverzicht['id'], $foto, $introductie, $familie, $belangrijkeinfo, $hobbies);
            $result->execute();
            return true;
        } else {    
            $result = DatabaseConnection::getConn()->prepare("UPDATE `clientverhaal` SET `foto`=?,`introductie`=?,`gezinfamilie`=?,`belangrijkeinfo`=?,`hobbies`=? WHERE medischoverzichtid = ?;");
            $result->bind_param("sssssi", $foto, $introductie, $familie, $belangrijkeinfo, $hobbies, $medischOverzicht['id']);
            $result->execute();
            return true;
        }
    }else{
        return false;
    }
}

function checkIfClientStoryExistsByClientId($id): bool {
    $result = DatabaseConnection::getConn()->prepare("
    SELECT cv.*
    FROM client c
    JOIN medischoverzicht mo on mo.clientid = c.id 
    join clientverhaal cv on cv.medischoverzichtid = mo.id
    where c.id = ?
    ");
    $result->bind_param("i", $id);
    $result->execute();

    if($result->get_result()->num_rows > 0) {
        return true;
    } else {
        return false;
    }
}