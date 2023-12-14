<?php

include_once './Database/DatabaseConnection.php';

function updateClient($naam, $geslacht, $adres, $postcode, $woonplaats, $telefoonnummer, $email, $reanimatiestatus, $nationaliteit, $afdeling, $burgelijkestaat, $foto): bool {
    $conn = DatabaseConnection::getConn();
    $conn->query("UPDATE `client` SET `naam`='${naam}',`geslacht`='${geslacht}',`adres`='${adres}',`postcode`='${postcode}',`woonplaats`='${woonplaats}',`telefoonnummer`='${telefoonnummer}',`email`='${email}',`reanimatiestatus`='${reanimatiestatus}',`nationaliteit`='${nationaliteit}',`afdeling`='${afdeling}',`burgelijkestaat`='${burgelijkestaat}',`foto`='${foto}' WHERE `naam`='${naam}';");

    echo $conn->affected_rows;
    if ($conn->affected_rows == 1)
        return true;

    if ($conn->affected_rows <= 0) {
        $result = $conn->query("SELECT * FROM `client` WHERE naam='${naam}'")->fetch_all();
        if (sizeof($result) == 0) {
            $conn->query("INSERT INTO `client`(`naam`, `geslacht`, `adres`, `postcode`, `woonplaats`, `telefoonnummer`, `email`, `reanimatiestatus`, `nationaliteit`, `afdeling`, `burgelijkestaat`, `foto`) VALUES ('${naam}','${geslacht}','${adres}','${postcode}','${woonplaats}','${telefoonnummer}','${email}','${reanimatiestatus}','${nationaliteit}','${afdeling}','${burgelijkestaat}','${foto}');");
            return true;
        }
    }

    return false;
}