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

class Medewerkers
{
    function updateMedewerker($naam, $klas, $foto, $email, $telefoonnummer, $wachtwoord): bool
    {
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

    function checkIfMedewerkerExistsById($id): bool
    {
        $result = $this->getMedewerkerById($id);
        if (sizeof($result) == 0)
            return false;

        return true;
    }

    function checkIfMedewerkerExistsByName($name): bool
    {
        $result = $this->getMedewerkerByName($name);
        if (sizeof($result) == 0)
            return false;

        return true;
    }

    function getMedewerkerById($id): array
    {
        $result = DatabaseConnection::getConn()->prepare("SELECT * FROM `medewerker` WHERE id = ?;");
        $result->bind_param("i", $id);
        $result->execute();

        return $result->get_result()->fetch_array();
    }

    function getMedewerkerByName($name): array
    {
        $result = DatabaseConnection::getConn()->prepare("SELECT * FROM `medewerker` WHERE naam = ?;");
        $result->bind_param("s", $name);
        $result->execute();

        return $result->get_result()->fetch_array();
    }
}

class Metingen
{
    function getMeting($metingtijden)
    {
        $metingen = [];
        $tijden = [];
        foreach ($metingtijden as $metingtijd) {
            $datumtijd = $metingtijd['datumtijd'];
            $metingid = $metingtijd['id'];
            $verzorgerregelid = $metingtijd['verzorgerregelid'];
            $medewerkerid = $metingtijd['medewerkerid'];
            $tijd = date('H:i', strtotime($datumtijd));
            $tijden[] = $tijd;
            $query = "(SELECT
            'hartslag' AS meting,
            MAX(CASE WHEN datumtijd = '$datumtijd' THEN hartslag ELSE null END) AS '$tijd'
            FROM meting
            WHERE verzorgerregelid = ?)

            UNION
            
            (SELECT
            'ademhaling' AS meting,
            MAX(CASE WHEN datumtijd = '$datumtijd' THEN ademhaling ELSE null END) AS '$tijd'
            FROM meting
            WHERE verzorgerregelid = ?)

            UNION
            
            (SELECT
            'bloeddruklaag' AS meting,
            MAX(CASE WHEN datumtijd = '$datumtijd' THEN bloeddruklaag ELSE null END) AS '$tijd'
            FROM meting
            WHERE verzorgerregelid = ?)

            UNION
            
            (SELECT
            'temperatuur' AS meting,
            MAX(CASE WHEN datumtijd = '$datumtijd' THEN temperatuur ELSE null END) AS '$tijd'
            FROM meting
            WHERE verzorgerregelid = ?)

            UNION
            
            (SELECT
            'vochtinname' AS meting,
            MAX(CASE WHEN datumtijd = '$datumtijd' THEN vochtinname ELSE null END) AS '$tijd'
            FROM meting
            WHERE verzorgerregelid = ?)

            UNION
            
            (SELECT
            'pijn' AS meting,
            MAX(CASE WHEN datumtijd = '$datumtijd' THEN pijn ELSE null END) AS '$tijd'
            FROM meting
            WHERE verzorgerregelid = ?)

            UNION
            
            (SELECT
            'bloeddrukhoog' AS meting,
            MAX(CASE WHEN datumtijd = '$datumtijd' THEN bloeddrukhoog ELSE null END) AS '$tijd'
            FROM meting
            WHERE verzorgerregelid = ?)

            UNION
            
            (SELECT
            'samenstelling' AS metingontlasting,
            MAX(CASE WHEN datumtijd = '$datumtijd' THEN samenstellingid ELSE null END) AS '$tijd'
            FROM metingontlasting
            WHERE metingid = ?)
            
            UNION
            
            (SELECT
            'uitscheiding' AS metingontlasting,
            MAX(CASE WHEN datumtijd = '$datumtijd' THEN uitscheiding ELSE null END) AS '$tijd'
            FROM metingontlasting
            WHERE metingid = ?)
            
            UNION
            
            (SELECT
            'hoeveelheid' AS metingurine,
            MAX(CASE WHEN datumtijd = '$datumtijd' THEN hoeveelheid ELSE null END) AS '$tijd'
            FROM metingurine
            WHERE metingid = ?)
            
            UNION
            
            (SELECT 'naam' as meting, naam FROM medewerker WHERE id = ?)";
            $result = DatabaseConnection::getConn()->prepare($query);
            $result->bind_param("iiiiiiiiiii", $verzorgerregelid, $verzorgerregelid, $verzorgerregelid, $verzorgerregelid, $verzorgerregelid, $verzorgerregelid, $verzorgerregelid, $metingid, $metingid, $metingid, $medewerkerid);
            $result->execute();
            $result = $result->get_result()->fetch_all(MYSQLI_ASSOC);

            $metingen[] = $result;
        }
        $arrays = [];
        $arrays[] = $tijden;
        $arrays[] = $metingen;
        return $arrays;
    }

    function vindGelijkeWaarde($array, $time)
    {
        foreach ($array as $measurement) {
            if (isset($measurement[$time]) && $measurement["meting"] === "bloeddrukhoog") {
                return $measurement[$time];
            }
        }
        return "";
    }
}
