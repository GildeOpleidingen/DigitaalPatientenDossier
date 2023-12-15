<?php

include_once './Database/DatabaseConnection.php';

function updateMedewerker($naam, $klas, $foto, $email, $telefoonnummer, $wachtwoord): bool {
    $conn = DatabaseConnection::getConn();
    $conn->query("UPDATE `medewerker` SET `naam`='${naam}',`klas`='${klas}',`foto`='${foto}',`email`='${email}',`telefoonnummer`='${telefoonnummer}',`wachtwoord`='${wachtwoord}' WHERE `naam`='${naam}';");

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