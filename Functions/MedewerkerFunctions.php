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

function checkIfMedewerkerExistsById($id) {
    $result = DatabaseConnection::getConn()->query("SELECT * FROM `medewerker` WHERE id='${id}';")->fetch_array();
    if (sizeof($result) == 0) {
        return false;
    }

    return true;
}

function checkIfMedewerkerExistsByName($name) {
    $result = DatabaseConnection::getConn()->query("SELECT * FROM `medewerker` WHERE naam='${name}';")->fetch_array();
    if (sizeof($result) == 0) {
        return false;
    }

    return true;
}

function getMedewerkerById($id) {
    return DatabaseConnection::getConn()->query("SELECT * FROM `medewerker` WHERE id='${id}';")->fetch_array();
}

function getMedewerkerByName($name) {
    return DatabaseConnection::getConn()->query("SELECT * FROM `medewerker` WHERE naam='${name}';")->fetch_array();
}
