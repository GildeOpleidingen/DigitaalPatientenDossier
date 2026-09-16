<?php

class PatroonModel
{
    public static function findQuestionnaireId(int $clientId): ?int
    {
        try {
            $conn = DatabaseConnection::getConn();

            $stmt = $conn->prepare("
                SELECT vragenlijst.id
                FROM vragenlijst
                INNER JOIN verzorgerregel ON verzorgerregel.id = vragenlijst.verzorgerregelid
                WHERE verzorgerregel.clientid = ?
            ");
            $stmt->bind_param("i", $clientId);
            $stmt->execute();
            $result = $stmt->get_result()->fetch_assoc();

            return $result ? (int) $result['id'] : null;
        } catch (mysqli_sql_exception $e) {
            error_log("PatroonModel::findQuestionnaireId error: " . $e->getMessage());
            return null;
        }
    }

    public static function createQuestionnaire(int $clientId, int $employeeId): ?int
    {
        try {
            $conn = DatabaseConnection::getConn();

            $stmt = $conn->prepare("
                INSERT INTO `vragenlijst` (`verzorgerregelid`)
                VALUES ((SELECT id FROM verzorgerregel WHERE clientid = ? AND medewerkerid = ?))
            ");
            $stmt->bind_param("ii", $clientId, $employeeId);
            $stmt->execute();

            if ($conn->insert_id) {
                return (int) $conn->insert_id;
            }

            return self::findQuestionnaireId($clientId);
        } catch (mysqli_sql_exception $e) {
            error_log("PatroonModel::createQuestionnaire error: " . $e->getMessage());
            return null;
        }
    }

    public static function getQuestionnaireId(int $clientId, int $employeeId): ?int
    {
        $id = self::findQuestionnaireId($clientId);
        if ($id !== null) {
            return $id;
        }

        return self::createQuestionnaire($clientId, $employeeId);
    }

    private static function getPatternTable(int $patternType): ?string
    {
        $patternTables = [
            1  => "patroon01gezondheidsbeleving",
            2  => "patroon02voedingstofwisseling",
            3  => "patroon03uitscheiding",
            4  => "patroon04activiteiten",
            5  => "patroon05slaaprust",
            6  => "patroon06cognitiewaarneming",
            7  => "patroon07zelfbeleving",
            8  => "patroon08rollenrelatie",
            9  => "patroon09seksualiteitvoorplanting",
            10 => "patroon10stressverwerking",
            11 => "patroon11waardelevensovertuiging"
        ];

        return $patternTables[$patternType] ?? null;
    }

    private static function fetchPatternAnswers(string $patternTable, int $questionnaireId): array|false
    {
        try {
            $conn = DatabaseConnection::getConn();

            $stmt = $conn->prepare("SELECT * FROM $patternTable WHERE vragenlijstid = ?");
            $stmt->bind_param("i", $questionnaireId);
            $stmt->execute();
            $result = $stmt->get_result()->fetch_assoc();

            if (!$result) {
                $columnsResult = $conn->query("
                    SELECT COLUMN_NAME
                    FROM INFORMATION_SCHEMA.COLUMNS
                    WHERE TABLE_NAME = '$patternTable'
                ");
                $columns = $columnsResult->fetch_all(MYSQLI_ASSOC);

                $result = [];
                foreach ($columns as $column) {
                    $columnName = $column['COLUMN_NAME'];
                    $result[$columnName] = ($columnName === 'observatie') ? "00000000000000000000" : "";
                }
            }

            return $result;
        } catch (mysqli_sql_exception $e) {
            error_log("PatroonModel::fetchPatternAnswers error: " . $e->getMessage());
            return false;
        }
    }

    public static function getPatternAnswers(int $clientId, int $patternType): array|false
    {
        $patternTable = self::getPatternTable($patternType);
        if (!$patternTable) {
            return false; // Invalid patternType
        }

        $questionnaireId = self::findQuestionnaireId($clientId);
        if ($questionnaireId === null) {
            return false; // Geen records gevonden
        }

        return self::fetchPatternAnswers($patternTable, $questionnaireId);
    }

    public static function getPatternTypes(): ?array
    {
        try {
            $result = DatabaseConnection::getConn()->query("SELECT * FROM `patroontype`");
            return $result ? $result->fetch_all(MYSQLI_NUM) : null;
        } catch (mysqli_sql_exception $e) {
            error_log("PatroonModel::getPatternTypes error: " . $e->getMessage());
            return null;
        }
    }

    public static function getPatternType(int $patternId): ?array
    {
        try {
            $stmt = DatabaseConnection::getConn()->prepare("SELECT * FROM `zorgplan` WHERE patroontypeid = ?");
            $stmt->bind_param("i", $patternId);
            $stmt->execute();
            $result = $stmt->get_result()->fetch_assoc();

            return $result ?: null;
        } catch (mysqli_sql_exception $e) {
            error_log("PatroonModel::getPatternType error: " . $e->getMessage());
            return null;
        }
    }

    public static function checkValue(int $value, int $min, int $max): bool
    {
        return $value >= $min && $value <= $max;
    }
}
