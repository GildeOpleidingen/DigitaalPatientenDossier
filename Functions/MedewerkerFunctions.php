<?php

include './Database/DatabaseConnection.php';

//createNewMedewerker("Test", "11", imgData, "test@gmail.com", "310600000000", "test");
function updateMedewerker($naam, $klas, $foto, $email, $telefoonnummer, $wachtwoord): bool {
    $conn = DatabaseConnection::getConn();
    $conn->query("UPDATE `medewerker` SET `naam`='${naam}',`klas`='${klas}',`foto`='${foto}',`email`='${email}',`telefoonnummer`='${telefoonnummer}',`wachtwoord`='${wachtwoord}' WHERE `naam`='${naam}';");

    if ($conn->affected_rows == 1)
        return true;

    if ($conn->affected_rows < 0) {
        $conn->query("INSERT INTO `medewerker`(`naam`, `klas`, `foto`, `email`, `telefoonnummer`, `wachtwoord`) VALUES ('${naam}','${klas}','${foto}','${email}','${telefoonnummer}','${wachtwoord}')");
        return true;
    }

    return false;
}