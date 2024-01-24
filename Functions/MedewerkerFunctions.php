<?php

include_once './Database/DatabaseConnection.php';

function updateMedewerker($naam, $klas, $foto, $email, $telefoonnummer, $wachtwoord): bool {
    $conn = DatabaseConnection::getConn();
    $conn->query("UPDATE `medewerker` SET `naam`='$naam',`klas`='$klas',`foto`='$foto',`email`='$email',`telefoonnummer`='$telefoonnummer',`wachtwoord`='$wachtwoord' WHERE `naam`='$naam';");

    if ($conn->affected_rows == 1)
        return true;

    if ($conn->affected_rows <= 0) {
        $result = $conn->query("SELECT * FROM `medewerker` WHERE naam='${naam}'")->fetch_all();
        if (sizeof($result) == 0) {
            $conn->query("INSERT INTO `medewerker`(`naam`, `klas`, `foto`, `email`, `telefoonnummer`, `wachtwoord`) VALUES ('${naam}','${klas}','${foto}','${email}','${telefoonnummer}','${wachtwoord}')");
            return true;
        }
    }
  
    return false;
}

function checkIfMedewerkerExistsById($id): bool {
    $result = getMedewerkerById($id);
    if (sizeof($result) == 0)
        return false;

    return true;
}

function checkIfMedewerkerExistsByName($name): bool {
    $result = getMedewerkerByName($name);
    if (sizeof($result) == 0)
        return false;

    return true;
}

function getMedewerkerById($id): array{
    $result = DatabaseConnection::getConn()->prepare("SELECT * FROM `medewerker` WHERE id = ?;");
    $result->bind_param("i", $id);
    $result->execute();

    return $result->get_result()->fetch_array();
}

function getMedewerkerByName($name): array {
    $result = DatabaseConnection::getConn()->prepare("SELECT * FROM `medewerker` WHERE naam = ?;");
    $result->bind_param("i", $name);
    $result->execute();

    return $result->get_result()->fetch_array();
}
