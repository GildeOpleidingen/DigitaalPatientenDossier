<?php
trait Get
{
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
            return false; // Geen records gevonden
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

        if (!empty($opnamedatum) && !empty($opnamedatum['opnamedatum'])) {
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
