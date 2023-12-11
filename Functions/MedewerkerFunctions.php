<?php

include './Database/DatabaseConnection.php';

//createNewMedewerker("Test", "11", imgData, "test@gmail.com", "310600000000", "test");
function createNewMedewerker($naam, $klas, $foto, $email, $telefoonnummer, $wachtwoord): bool {
    $result = DatabaseConnection::getConn()->query("SELECT * FROM `medewerker` WHERE naam='${naam}'")->fetch_all();

    if (sizeof($result) == 0) {
        DatabaseConnection::getConn()->query("INSERT INTO `medewerker`(`naam`, `klas`, `foto`, `email`, `telefoonnummer`, `wachtwoord`) VALUES ('${naam}','${klas}','${foto}','${email}','${telefoonnummer}','${wachtwoord}')");
        return true;
    }

    return false;
}