<?php
// Om deze functies te gebruiken moet je op de pagina waar je ze wilt gebruiken de databaseConnection includen en deze file includen
function updateClient($naam, $geslacht, $adres, $postcode, $woonplaats, $telefoonnummer, $email, $reanimatiestatus, $nationaliteit, $afdeling, $burgelijkestaat, $foto): bool {
    $result = DatabaseConnection::getConn()->prepare("UPDATE `client` SET `geslacht`=?,`adres`=?,`postcode`=?,`woonplaats`=?,`telefoonnummer`=?,`email`=?,`reanimatiestatus`=?,`nationaliteit`=?,`afdeling`=?,`burgelijkestaat`=?,`foto`=? WHERE `naam`=?;");
    $result->bind_param("ssssssssssss", $geslacht, $adres, $postcode, $woonplaats, $telefoonnummer, $email, $reanimatiestatus, $nationaliteit, $afdeling, $burgelijkestaat, $foto, $naam);
    $result->execute();

    if ($result->affected_rows == 1)
        return true;

    if ($result->affected_rows <= 0) {
        $query = DatabaseConnection::getConn()->prepare("SELECT * FROM `client` WHERE naam= ?")->fetch_all();
        $query.bind_param("s", $naam);
        $query.execute();
        if (sizeof($query) == 0) {
            //            DatabaseConnection::getConn()->prepare("INSERT INTO `client`(`naam`, `geslacht`, `adres`, `postcode`, `woonplaats`, `telefoonnummer`, `email`, `reanimatiestatus`, `nationaliteit`, `afdeling`, `burgelijkestaat`, `foto`) VALUES ('$naam','$geslacht','$adres','$postcode','$woonplaats','$telefoonnummer','$email','$reanimatiestatus','$nationaliteit','$afdeling','$burgelijkestaat','$foto');");
            $result = DatabaseConnection::getConn()->prepare("INSERT INTO `client`(`naam`, `geslacht`, `adres`, `postcode`, `woonplaats`, `telefoonnummer`, `email`, `reanimatiestatus`, `nationaliteit`, `afdeling`, `burgelijkestaat`, `foto`) VALUES (?,?,?,?,?,?,?,?,?,?,?,?);");
            $result->bind_param("sssssssssss", $naam, $geslacht, $adres, $postcode, $woonplaats, $telefoonnummer, $email, $reanimatiestatus, $nationaliteit, $afdeling, $burgelijkestaat, $foto);
            $result->execute();
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

    $mo =  (array) $result->get_result()->fetch_array();
    if($mo != null){
        return $mo;
    }
    else{
        $legeArray = [];
        $legeArray["medischevoorgeschiedenis"] = "Geen medische voorgeschiedenis ingevuld";
        $legeArray["medicatie"] = "Geen medicatie ingevuld";
        $legeArray["alergieen"] = "Geen allergieën ingevuld";
        $legeArray["opnamedatum"] = "Geen opnamedatum ingevuld";
        return $legeArray;
    }
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
    if(checkIfClientExistsById($clientid) && count($medischOverzicht) > 0){
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

function getAdmissionDateByClientId($id): string {
    $result = DatabaseConnection::getConn()->prepare("
    select opnamedatum
    from medischoverzicht
    where clientid = ?
    ");
    $result->bind_param("i", $id);
    $result->execute();
    $opnamedatum = $result->get_result()->fetch_assoc();

    if($opnamedatum != null){
        return $opnamedatum['opnamedatum'];
    }

    return "Geen opnamedatum ingevuld";
}

