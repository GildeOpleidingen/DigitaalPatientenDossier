<?php
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
    $result = DatabaseConnection::getConn()->prepare("SELECT * FROM `medischoverzicht` WHERE clientid = ?;");
    $result->bind_param("i", $id);
    $result->execute();

    return (array) $result->get_result()->fetch_array();
}

function insertClientStory($medischoverzichtid, $foto, $introductie, $familie, $hobbys, $omgang): void {
    $result = DatabaseConnection::getConn()->prepare("INSERT INTO `clientverhaal`(`id`, `medischoverzichtid`, `foto`, `introductie`, `gezinfamilie`, `belangrijkeinfo`, `hobbies`) VALUES (NULL, ?, ?, ?, ?, ?, ?);");
    $result->bind_param("ibssss", $medischoverzichtid, $foto, $introductie, $familie, $hobbys, $omgang);
    $result->execute();
}