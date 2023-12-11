<?php

//include_once '../Database/DatabaseConnection.php';
include './Database/DatabaseConnection.php';

function createNewMedewerker($naam, $klas, $foto, $email, $telefoonnummer, $wachtwoord): void {
    $result = DatabaseConnection::getConn()->query("SELECT * FROM `medewerker` WHERE naam='${naam}'")->fetch_all();

    if (sizeof($result) == 0) {
        DatabaseConnection::getConn()->query("INSERT INTO `medewerker`(`naam`, `klas`, `foto`, `email`, `telefoonnummer`, `wachtwoord`) VALUES ('${naam}','${klas}','${foto}','${email}','${telefoonnummer}','${wachtwoord}')");
    }
}