<?php
class Main extends Checks
{
    function updateClient($naam, $geslacht, $adres, $postcode, $woonplaats, $telefoonnummer, $email, $reanimatiestatus, $nationaliteit, $afdeling, $burgelijkestaat, $foto): bool
    {
        $result = DatabaseConnection::getConn()->prepare("UPDATE `client` SET `geslacht`=?,`adres`=?,`postcode`=?,`woonplaats`=?,`telefoonnummer`=?,`email`=?,`reanimatiestatus`=?,`nationaliteit`=?,`afdeling`=?,`burgelijkestaat`=?,`foto`=? WHERE `naam`=?;");
        $result->bind_param("ssssssssssss", $geslacht, $adres, $postcode, $woonplaats, $telefoonnummer, $email, $reanimatiestatus, $nationaliteit, $afdeling, $burgelijkestaat, $foto, $naam);
        $result->execute();

        if ($result->affected_rows == 1)
            return true;

        if ($result->affected_rows <= 0) {
            $query = DatabaseConnection::getConn()->prepare("SELECT * FROM `client` WHERE naam= ?");
            $query->execute();
            $query = $query->get_result()->fetch_all();
            $result->bind_param("s", $naam);
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

    function insertClientStory($clientid, $foto, $introductie, $familie, $belangrijkeinfo, $hobbies): bool
    {
        $medischOverzicht = $this->getMedischOverzichtByClientId($clientid);
        if ($this->checkIfClientExistsById($clientid) && count($medischOverzicht) > 0) {
            if (!$this->checkIfClientStoryExistsByClientId($clientid)) {
                if (!$this->checkIfMedischOverzichtExistsByClientId($clientid)) {
                    $result = DatabaseConnection::getConn()->prepare("INSERT INTO `medischoverzicht`(`clientid`) VALUES (?);");
                    $result->bind_param("i", $clientid);
                    $result->execute();

                    $medischOverzichtId = DatabaseConnection::getConn()->insert_id;
                } else {
                    $medischOverzicht = $this->getMedischOverzichtByClientId($clientid);
                    $medischOverzichtId = $medischOverzicht['id'];
                }

                $result = DatabaseConnection::getConn()->prepare("INSERT INTO `clientverhaal`(`id`, `medischoverzichtid`, `foto`, `introductie`, `gezinfamilie`, `belangrijkeinfo`, `hobbies`) VALUES (NULL, ?, ?, ?, ?, ?, ?);");
                $result->bind_param("isssss", $medischOverzichtId, $foto, $introductie, $familie, $belangrijkeinfo, $hobbies);
                if ($result->execute()) {
                    return true;
                } else {
                    return "Insert failed: " . $result->error;
                }
            } else {
                $result = DatabaseConnection::getConn()->prepare("UPDATE `clientverhaal` SET `foto`=?,`introductie`=?,`gezinfamilie`=?,`belangrijkeinfo`=?,`hobbies`=? WHERE medischoverzichtid = ?;");
                $result->bind_param("sssssi", $foto, $introductie, $familie, $belangrijkeinfo, $hobbies, $medischOverzicht['id']);
                if ($result->execute()) {
                    return true;
                } else {
                    return "Update failed: " . $result->error;
                }
            }
        } else {
            return false;
        }
    }

    function insertCarePlan($clientId, $opsteldatumtijd, $patroontypeid, $P, $E, $S, $doelen, $interventies, $evaluatiedoelen): bool
    {
        if ($this->checkIfClientExistsById($clientId)) {
            if (!$this->checkIfCarePlanPatternTypeExists($clientId, $patroontypeid)) {
                $result = DatabaseConnection::getConn()->prepare("INSERT INTO `zorgplan`(`id`, `clientid`, `opsteldatumtijd`, `patroontypeid`, `P`, `E`, `S`, `doelen`, `interventies`, `evaluatiedoelen`) VALUES (NULL, ?, ?, ?, ?, ?, ?, ?, ?, ?);");
                $result->bind_param("issssssss", $clientId, $opsteldatumtijd, $patroontypeid, $P, $E, $S, $doelen, $interventies, $evaluatiedoelen);
                $result->execute();
                return true;
            } else {
                $result = DatabaseConnection::getConn()->prepare("UPDATE `zorgplan` SET `opsteldatumtijd`=?,`patroontypeid`=?,`P`=?,`E`=?,`S`=?,`doelen`=?,`interventies`=?,`evaluatiedoelen`=? WHERE clientid = ? AND patroontypeid = ?;");
                $result->bind_param("ssssssssii", $opsteldatumtijd, $patroontypeid, $P, $E, $S, $doelen, $interventies, $evaluatiedoelen, $clientId, $patroontypeid);
                $result->execute();
                return true;
            }
        } else {
            return false;
        }
    }
}
