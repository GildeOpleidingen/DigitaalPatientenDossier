<?php
class Main
{
    function convertNumToBool($numbers, $index = 0): ?bool
    {
        if ($index < mb_strlen($numbers)) {
            $num = str_split($numbers)[$index];

            if ($num == 1) {
                return true;
            }

            return false;
        }

        return null;
    }

    function convertNumToBoolArray($numbers): array
    {
        $numArr = str_split($numbers);
        $boolArr = array();

        foreach ($numArr as $num) {
            if ($num == 1) {
                $boolArr[] = true;
                continue;
            }
            $boolArr[] = false;
        }

        return $boolArr;
    }

    function convertBoolArrayToString($boolArr): string
    {
        $numbers = '';
        foreach ($boolArr as $bool) {
            $numbers .= $bool ? '1' : '0';
        }
        return $numbers;
    }

    /**
     * Haalt alle antwoorden op uit de database van een anamnese patroon
     *
     * @return      array|false
     */

    function getPatternAnswers(int $clientId, int $patroonType)
    {
        $patroonTypes = [
            "patroon01gezondheidsbeleving",
            "patroon02voedingstofwisseling",
            "patroon03uitscheiding",
            "patroon04activiteiten",
            "patroon05slaaprust",
            "patroon06cognitiewaarneming",
            "patroon07zelfbeleving",
            "patroon08rollenrelatie",
            "patroon09seksualiteitvoorplanting",
            "patroon10stressverwerking",
            "patroon11waardelevensovertuiging"
        ];

        if ($patroonType < 1 || $patroonType > count($patroonTypes)) {
            return false; // Invalid patroonType
        }

        $patroon = $patroonTypes[$patroonType - 1];
        $conn = DatabaseConnection::getConn();

        $stmt = $conn->prepare("
            SELECT vl.id
            FROM vragenlijst vl
            LEFT JOIN verzorgerregel ON verzorgerregel.id = vl.verzorgerregelid
            WHERE verzorgerregel.clientid = ?
        ");
        if (!$stmt) {
            die('Prepare statement failed: ' . $conn->error);
        }

        $stmt->bind_param("i", $clientId);
        $stmt->execute();
        $result = $stmt->get_result()->fetch_assoc();

        if (!$result || !isset($result['id'])) {
            return false; // No matching record found
        }

        $vlId = $result['id'];

        $stmt = $conn->prepare("SELECT * FROM $patroon WHERE vragenlijstid = ?");
        if (!$stmt) {
            die('Prepare statement failed: ' . $conn->error);
        }

        $stmt->bind_param("i", $vlId);
        $stmt->execute();
        $antwoorden = $stmt->get_result()->fetch_array(MYSQLI_ASSOC);

        if (!$antwoorden) {
            $columnsResult = $conn->query("
                SELECT COLUMN_NAME
                FROM INFORMATION_SCHEMA.COLUMNS
                WHERE TABLE_NAME = '$patroon'
            ");
            $columns = $columnsResult->fetch_all(MYSQLI_ASSOC);

            $antwoorden = [];
            foreach ($columns as $column) {
                $columnName = $column['COLUMN_NAME'];
                $antwoorden[$columnName] = ($columnName === 'observatie') ? "00000000000000000000" : "";
            }
        }

        return $antwoorden;
    }

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

    function checkIfClientExistsById(int $id): bool
    {
        $result = $this->getClientById($id);

        return sizeof((array) $result) > 0;
    }

    function checkIfClientExistsByName(string $name): bool
    {
        $result = $this->getClientByName($name);

        return sizeof((array) $result) > 0;
    }

    function getClientById($ClientId): array
    {
        $result = DatabaseConnection::getConn()->prepare("SELECT * FROM `client` WHERE id = ?;");
        $result->bind_param("i", $ClientId);
        $result->execute();

        return (array) $result->get_result()->fetch_array(MYSQLI_ASSOC);
    }

    function getClientByName($name): array
    {
        $result = DatabaseConnection::getConn()->prepare("SELECT * FROM `client` WHERE naam = ?;");
        $result->bind_param("s", $name);
        $result->execute();

        return (array) $result->get_result()->fetch_array();
    }

    function getClientStoryByClientId($id): array
    {
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

    function insertClientStory($clientid, $foto, $introductie, $familie, $belangrijkeinfo, $hobbies): bool
    {
        $medischOverzicht = $this->getMedischOverzichtByClientId($clientid);
        if ($this->checkIfClientExistsById($clientid) && count($medischOverzicht) > 0) {
            if (!$this->checkIfClientStoryExistsByClientId($clientid)) {
                $result = DatabaseConnection::getConn()->prepare("INSERT INTO `clientverhaal`(`id`, `medischoverzichtid`, `foto`, `introductie`, `gezinfamilie`, `belangrijkeinfo`, `hobbies`) VALUES (NULL, ?, ?, ?, ?, ?, ?);");
                $result->bind_param("isssss", $medischOverzicht['id'], $foto, $introductie, $familie, $belangrijkeinfo, $hobbies);
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

    function checkIfClientStoryExistsByClientId($id): bool
    {
        $result = DatabaseConnection::getConn()->prepare("
        SELECT cv.*
        FROM client c
        JOIN medischoverzicht mo on mo.clientid = c.id 
        JOIN clientverhaal cv on cv.medischoverzichtid = mo.id
        WHERE c.id = ?
        ");
        $result->bind_param("i", $id);
        $result->execute();

        if ($result->get_result()->num_rows > 0) {
            return true;
        } else {
            return false;
        }
    }

    function checkIfCarePlanExistsByClientId($id): bool
    {
        $result = DatabaseConnection::getConn()->prepare("
        SELECT cp.*
        FROM client c
        JOIN zorgplan cp on cp.clientid = c.id
        WHERE c.id = ?
        ");
        $result->bind_param("i", $id);
        $result->execute();

        if ($result->get_result()->num_rows > 0) {
            return true;
        } else {
            return false;
        }
    }

    function getCarePlanByClientId($id): array
    {
        $result = DatabaseConnection::getConn()->prepare("
        SELECT cp.*
        FROM client c
        JOIN zorgplan cp on cp.clientid = c.id
        WHERE c.id = ?
        ");

        $result->bind_param("i", $id);
        $result->execute();
        return (array) $result->get_result()->fetch_array(MYSQLI_ASSOC);
    }

    function checkIfCarePlanPatternTypeExists($id, $patternId): bool
    {
        $result = DatabaseConnection::getConn()->prepare("
        SELECT cp.*
        FROM client c
        JOIN zorgplan cp on cp.clientid = c.id
        WHERE c.id = ? AND cp.patroontypeid = ?
        ");

        $result->bind_param("ii", $id, $patternId);
        $result->execute();
        $carePlan = $result->get_result()->fetch_array(MYSQLI_ASSOC);
        return sizeof((array) $carePlan) > 0;
    }

    function getPatternTypes(): ?array
    {
        $result = DatabaseConnection::getConn()->query("SELECT * FROM `patroontype`");
        return $result->fetch_all(MYSQLI_NUM);
    }

    function getPatternType($patternId): ?array
    {
        $result = DatabaseConnection::getConn()->prepare("SELECT * FROM `zorgplan` WHERE patroontypeid = ?");
        $result->bind_param("i", $patternId);
        $result->execute();
        return $result->get_result()->fetch_array(MYSQLI_ASSOC);
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


    function getAdmissionDateByClientId($id): string
    {
        $result = DatabaseConnection::getConn()->prepare("
        select opnamedatum
        from medischoverzicht
        where clientid = ?
        ");
        $result->bind_param("i", $id);
        $result->execute();
        $opnamedatum = $result->get_result()->fetch_assoc();

        if ($opnamedatum != null) {
            return $opnamedatum['opnamedatum'];
        }

        return "Geen opnamedatum ingevuld";
    }

    function getMedischOverzichtByClientId($id): array
    {
        $result = DatabaseConnection::getConn()->prepare("
        SELECT mo.*
        FROM client c
        JOIN medischoverzicht mo on mo.clientid = c.id 
        where c.id = ?
        ");

        $result->bind_param("i", $id);
        $result->execute();

        $mo =  (array) $result->get_result()->fetch_array();
        if ($mo != null) {
            return $mo;
        } else {
            $legeArray = [];
            $legeArray["medischevoorgeschiedenis"] = "Geen medische voorgeschiedenis ingevuld";
            $legeArray["medicatie"] = "Geen medicatie ingevuld";
            $legeArray["alergieen"] = "Geen allergieën ingevuld";
            $legeArray["opnamedatum"] = "Geen opnamedatum ingevuld";
            return $legeArray;
        }
    }
}
