<?php
trait Patroon
{
    public function getVragenlijstId(int $clientId, int $medewerkerId)
    {
        $result = DatabaseConnection::getConn()->prepare("
                    SELECT vl.id
                    from vragenlijst vl
                    left join verzorgerregel on verzorgerregel.id = vl.verzorgerregelid
                    where verzorgerregel.clientid = ?");
        $result->bind_param("i", $clientId);
        $result->execute();
        $result = $result->get_result()->fetch_assoc();

        if ($result != null) {
            $vragenlijstId = $result['id'];
        } else {
            $sql = DatabaseConnection::getConn()->prepare("INSERT INTO `vragenlijst`(`verzorgerregelid`)
                VALUES ((SELECT id
                FROM verzorgerregel
                WHERE clientid = ?
                AND medewerkerid = ?))");
            $sql->bind_param("ii", $clientId, $medewerkerId);
            $sql->execute();
            $sql = $sql->get_result();

            $result = DatabaseConnection::getConn()->prepare("SELECT vl.id
                        from vragenlijst vl
                        left join verzorgerregel on verzorgerregel.id = vl.verzorgerregelid
                        where verzorgerregel.clientid = ?");
            $result->bind_param("i", $clientId);
            $result->execute();
            $result = $result->get_result()->fetch_assoc();

            $vragenlijstId = $result['id'];
        }
        return $vragenlijstId;
    }
    
    public function getPatternAnswers(int $clientId, int $patroonType)
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

    public function getPatternTypes(): ?array
    {
        $result = DatabaseConnection::getConn()->query("SELECT * FROM `patroontype`");
        return $result->fetch_all(MYSQLI_NUM);
    }

    public function getPatternType($patternId): ?array
    {
        $result = DatabaseConnection::getConn()->prepare("SELECT * FROM `zorgplan` WHERE patroontypeid = ?");
        $result->bind_param("i", $patternId);
        $result->execute();
        return $result->get_result()->fetch_array(MYSQLI_ASSOC);
    }

    public function CheckValue($value, $min, $max){
        if($value >= $min && $value <= $max){
            return true;
        } else {
            return false;
        }
    }
}