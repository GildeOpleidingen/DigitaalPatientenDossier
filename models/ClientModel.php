<?php

class ClientModel
{
    private mysqli $db;

    /**
     * @param mysqli|null $db
     */
    public function __construct(?mysqli $db = null)
    {
        $this->db = $db ?? DatabaseConnection::getConn();
    }

    /**
     * @param string $sql
     * @param string $types
     * @param mixed ...$params
     * @return array|null
     */
    private function queryOne(string $sql, string $types, mixed ...$params): ?array
    {
        try {
            $stmt = $this->db->prepare($sql);

            if (!$stmt) {
                return null;
            }

            $stmt->bind_param($types, ...$params);
            $stmt->execute();

            $result = $stmt->get_result();
            $row    = $result ? $result->fetch_assoc() : null;
            $stmt->close();

            return $row ?: null;
        } catch (mysqli_sql_exception $e) {
            error_log("ClientModel query error: " . $e->getMessage());
            return null;
        }
    }

    /**
     * @param string $sql
     * @param string $types
     * @param mixed ...$params
     * @return array
     */
    private function queryAll(string $sql, string $types, mixed ...$params): array
    {
        try {
            $stmt = $this->db->prepare($sql);

            if (!$stmt) {
                return [];
            }

            $stmt->bind_param($types, ...$params);
            $stmt->execute();

            $result = $stmt->get_result();
            $rows   = $result ? $result->fetch_all(MYSQLI_ASSOC) : [];
            $stmt->close();

            return $rows;
        } catch (mysqli_sql_exception $e) {
            error_log("ClientModel query error: " . $e->getMessage());
            return [];
        }
    }

    /**
     * @param string $sql
     * @param string $types
     * @param mixed ...$params
     * @return bool
     */
    private function queryExists(string $sql, string $types, mixed ...$params): bool
    {
        try {
            $stmt = $this->db->prepare($sql);

            if (!$stmt) {
                return false;
            }

            $stmt->bind_param($types, ...$params);
            $stmt->execute();
            $stmt->store_result();

            $exists = $stmt->num_rows > 0;
            $stmt->close();

            return $exists;
        } catch (mysqli_sql_exception $e) {
            error_log("ClientModel query error: " . $e->getMessage());
            return false;
        }
    }

    /**
     * @param string $sql
     * @param string $types
     * @param mixed ...$params
     * @return bool
     */
    private function execute(string $sql, string $types, mixed ...$params): bool
    {
        try {
            $stmt = $this->db->prepare($sql);

            if (!$stmt) {
                return false;
            }

            $stmt->bind_param($types, ...$params);
            $success = $stmt->execute();
            $stmt->close();

            return $success;
        } catch (mysqli_sql_exception $e) {
            error_log("ClientModel execute error: " . $e->getMessage());
            return false;
        }
    }

    /**
     * @param int $id
     * @return array|null
     */
    public function getById(int $id): ?array
    {
        return $this->queryOne("
            SELECT c.*, a.naam AS afdeling
            FROM client c
            LEFT JOIN afdelingen a ON a.id = c.afdeling_id
            WHERE c.id = ?
            LIMIT 1
        ", "i", $id);
    }

    /**
     * @param int $id
     * @return array
     */
    public function getClientById(int $id): array
    {
        return $this->getById($id) ?? [];
    }

    /**
     * @param string $name
     * @return array
     */
    public function getClientByName(string $name): array
    {
        return $this->queryOne("
            SELECT * FROM client WHERE naam = ? LIMIT 1
        ", "s", $name) ?? [];
    }

    /**
     * @param int $id
     * @return bool
     */
    public function checkIfClientExistsById(int $id): bool
    {
        return $this->queryExists("
            SELECT id FROM client WHERE id = ? LIMIT 1
        ", "i", $id);
    }

    /**
     * @param string $name
     * @return bool
     */
    public function checkIfClientExistsByName(string $name): bool
    {
        return $this->queryExists("
            SELECT id FROM client WHERE naam = ? LIMIT 1
        ", "s", $name);
    }

    /**
     * @param int $clientId
     * @param int $employeeId
     * @return bool
     */
    public function checkIfCareRelationExists(int $clientId, int $employeeId): bool
    {
        try {
            $exists = $this->queryExists("
                SELECT id FROM verzorgerregel
                WHERE clientid = ? AND medewerkerid = ?
            ", "ii", $clientId, $employeeId);

            if (!$exists) {
                return $this->execute("
                    INSERT INTO verzorgerregel (clientid, medewerkerid)
                    VALUES (?, ?)
                ", "ii", $clientId, $employeeId);
            }

            return true;
        } catch (Exception $e) {
            return false;
        }
    }

    /**
     * @param int $clientId
     * @param int $medewerkerId
     * @return bool
     */
    public function CheckIfVerzorgregelExists($clientId, $medewerkerId): bool
    {
        return $this->checkIfCareRelationExists((int)$clientId, (int)$medewerkerId);
    }

    /**
     * @param int $id
     * @return array
     */
    public function getCareRelationsByClientId(int $id): array
    {
        return $this->queryAll("
            SELECT * FROM verzorgerregel WHERE clientid = ?
        ", "i", $id);
    }

    /**
     * @param int $id
     * @return array
     */
    public function getVerzorgerregelByClientId($id): array
    {
        return $this->getCareRelationsByClientId((int)$id);
    }

    /**
     * @param int $id
     * @return array
     */
    public function getCaregiversById(int $id): array
    {
        return $this->queryOne("
            SELECT * FROM medewerker WHERE id = ? LIMIT 1
        ", "i", $id) ?? [];
    }

    /**
     * @param int $id
     * @return array
     */
    public function getVerzorgersById($id): array
    {
        return $this->getCaregiversById((int)$id);
    }

    /**
     * @param int $id
     * @param 'clientRelations'|'contactPersonen'|'medischOverzicht'|'verzorgersArr'|string $type
     * @return array
     */
    public function getPatientData(int $id, string $type): array
    {
        $sql = match ($type) {
            'clientRelations'  => "SELECT * FROM verzorgerregel WHERE clientid = ?",
            'contactPersonen'  => "SELECT * FROM relatie WHERE clientid = ?",
            'medischOverzicht' => "SELECT * FROM medischoverzicht WHERE clientid = ?",
            'verzorgersArr'    => "
                SELECT m.*
                FROM medewerker m
                JOIN verzorgerregel vr ON m.id = vr.medewerkerid
                WHERE vr.clientid = ?
            ",
            default => null,
        };

        if ($sql === null) {
            return [];
        }

        return $this->queryAll($sql, "i", $id);
    }

    /**
     * @param int $id
     * @param 'clientRelations'|'contactPersonen'|'medischOverzicht'|'verzorgersArr'|string $type
     * @return array
     */
    public function getPatientGegevens($id, $type): array
    {
        return $this->getPatientData((int)$id, (string)$type);
    }

    /**
     * @param int $id
     * @return array
     */
    public function getMedicalOverviewByClientId(int $id): array
    {
        $overview = $this->queryOne("
            SELECT mo.*
            FROM client c
            JOIN medischoverzicht mo ON mo.clientid = c.id
            WHERE c.id = ?
            LIMIT 1
        ", "i", $id);

        return $overview ?? [
            "medischevoorgeschiedenis" => "Geen medische voorgeschiedenis ingevuld",
            "medicatie"                => "Geen medicatie ingevuld",
            "alergieen"                => "Geen allergieën ingevuld",
            "opnamedatum"              => "Geen opnamedatum ingevuld",
        ];
    }

    /**
     * @param int $id
     * @return array
     */
    public function getMedischOverzichtByClientId($id): array
    {
        return $this->getMedicalOverviewByClientId((int)$id);
    }

    /**
     * @param int $id
     * @return string
     */
    public function getAdmissionDateByClientId(int $id): string
    {
        $row = $this->queryOne("
            SELECT opnamedatum
            FROM medischoverzicht
            WHERE clientid = ?
            LIMIT 1
        ", "i", $id);

        if (
            $row
            && !empty($row['opnamedatum'])
            && $row['opnamedatum'] !== '0000-00-00 00:00:00'
            && $row['opnamedatum'] !== '0000-00-00'
        ) {
            return (string) $row['opnamedatum'];
        }

        return "Geen opnamedatum ingevuld";
    }

    /**
     * @param int $id
     * @return bool
     */
    public function checkIfMedicalOverviewExistsByClientId(int $id): bool
    {
        return $this->queryExists("
            SELECT id FROM medischoverzicht WHERE clientid = ? LIMIT 1
        ", "i", $id);
    }

    /**
     * @param int $clientid
     * @return bool
     */
    public function checkIfMedischOverzichtExistsByClientId($clientid): bool
    {
        return $this->checkIfMedicalOverviewExistsByClientId((int)$clientid);
    }

    /**
     * @param int $id
     * @return bool
     */
    public function checkIfClientStoryExistsByClientId(int $id): bool
    {
        return $this->queryExists("
            SELECT cv.id
            FROM client c
            JOIN medischoverzicht mo ON mo.clientid = c.id
            JOIN clientverhaal cv   ON cv.medischoverzichtid = mo.id
            WHERE c.id = ?
            LIMIT 1
        ", "i", $id);
    }

    /**
     * @param int $id
     * @return array
     */
    public function getClientStoryByClientId(int $id): array
    {
        return $this->queryOne("
            SELECT cv.*
            FROM client c
            JOIN medischoverzicht mo ON mo.clientid = c.id
            JOIN clientverhaal cv   ON cv.medischoverzichtid = mo.id
            WHERE c.id = ?
            LIMIT 1
        ", "i", $id) ?? [];
    }

    /**
     * @param int $clientId
     * @param string|null $photo
     * @param string $introduction
     * @param string $family
     * @param string $importantInfo
     * @param string $hobbies
     * @return bool
     */
    public function insertClientStory(
        int     $clientId,
        ?string $photo,
        string  $introduction,
        string  $family,
        string  $importantInfo,
        string  $hobbies
    ): bool {
        $overview = $this->getMedicalOverviewByClientId($clientId);

        if (!$this->checkIfClientExistsById($clientId) || empty($overview)) {
            return false;
        }

        if (!$this->checkIfClientStoryExistsByClientId($clientId)) {
            $overviewId = $this->ensureMedicalOverviewId($clientId, $overview);

            if (!$overviewId) {
                return false;
            }

            return $this->execute("
                INSERT INTO clientverhaal
                    (id, medischoverzichtid, foto, introductie, gezinfamilie, belangrijkeinfo, hobbies)
                VALUES (NULL, ?, ?, ?, ?, ?, ?)
            ", "isssss", $overviewId, $photo, $introduction, $family, $importantInfo, $hobbies);
        }

        $overviewId = isset($overview['id']) ? (int) $overview['id'] : 0;

        return $this->execute("
            UPDATE clientverhaal
            SET foto = ?, introductie = ?, gezinfamilie = ?, belangrijkeinfo = ?, hobbies = ?
            WHERE medischoverzichtid = ?
        ", "sssssi", $photo, $introduction, $family, $importantInfo, $hobbies, $overviewId);
    }

    /**
     * @return bool
     */
    public function 🥶()
    {
        return true;
    }

    /**
     * @param int $clientId
     * @param array $overview
     * @return int|null
     */
    private function ensureMedicalOverviewId(int $clientId, array $overview): ?int
    {
        if ($this->checkIfMedicalOverviewExistsByClientId($clientId)) {
            return isset($overview['id']) ? (int) $overview['id'] : null;
        }

        if (!$this->execute("INSERT INTO medischoverzicht (clientid) VALUES (?)", "i", $clientId)) {
            return null;
        }

        return (int) $this->db->insert_id;
    }

    /**
     * @param int $id
     * @return bool
     */
    public function checkIfCarePlanExistsByClientId(int $id): bool
    {
        return $this->queryExists("
            SELECT cp.id
            FROM client c
            JOIN zorgplan cp ON cp.clientid = c.id
            WHERE c.id = ?
            LIMIT 1
        ", "i", $id);
    }

    /**
     * @param int $id
     * @return array
     */
    public function getCarePlanByClientId(int $id): array
    {
        return $this->queryOne("
            SELECT cp.*
            FROM client c
            JOIN zorgplan cp ON cp.clientid = c.id
            WHERE c.id = ?
            LIMIT 1
        ", "i", $id) ?? [];
    }

    /**
     * @param string $name
     * @param string $gender
     * @param string $address
     * @param string $postalCode
     * @param string $city
     * @param string $phone
     * @param string $email
     * @param string $resuscitationStatus
     * @param string $nationality
     * @param string $department
     * @param string $maritalStatus
     * @param string|null $photo
     * @return bool
     */
    public function updateClient(
        string  $name,
        string  $gender,
        string  $address,
        string  $postalCode,
        string  $city,
        string  $phone,
        string  $email,
        string  $resuscitationStatus,
        string  $nationality,
        string  $department,
        string  $maritalStatus,
        ?string $photo
    ): bool {
        try {
            $stmt = $this->db->prepare("
                UPDATE client
                SET geslacht = ?, adres = ?, postcode = ?, woonplaats = ?,
                    telefoonnummer = ?, email = ?, reanimatiestatus = ?,
                    nationaliteit = ?, afdeling = ?, burgelijkestaat = ?, foto = ?
                WHERE naam = ?
            ");

            if (!$stmt) {
                return false;
            }

            $stmt->bind_param(
                "ssssssssssss",
                $gender,
                $address,
                $postalCode,
                $city,
                $phone,
                $email,
                $resuscitationStatus,
                $nationality,
                $department,
                $maritalStatus,
                $photo,
                $name
            );
            $stmt->execute();

            $affected = $stmt->affected_rows;
            $stmt->close();

            if ($affected == 1) {
                return true;
            }

            if (!$this->checkIfClientExistsByName($name)) {
                return $this->insertNewClient(
                    $name,
                    $gender,
                    $address,
                    $postalCode,
                    $city,
                    $phone,
                    $email,
                    $resuscitationStatus,
                    $nationality,
                    $department,
                    $maritalStatus,
                    $photo
                );
            }

            return false;
        } catch (mysqli_sql_exception $e) {
            error_log("ClientModel::updateClient error: " . $e->getMessage());
            return false;
        }
    }

    /**
     * @param string $name
     * @param string $gender
     * @param string $address
     * @param string $postalCode
     * @param string $city
     * @param string $phone
     * @param string $email
     * @param string $resuscitationStatus
     * @param string $nationality
     * @param string $department
     * @param string $maritalStatus
     * @param string|null $photo
     * @return bool
     */
    private function insertNewClient(
        string  $name,
        string  $gender,
        string  $address,
        string  $postalCode,
        string  $city,
        string  $phone,
        string  $email,
        string  $resuscitationStatus,
        string  $nationality,
        string  $department,
        string  $maritalStatus,
        ?string $photo
    ): bool {
        return $this->execute(
            "
            INSERT INTO client
                (naam, geslacht, adres, postcode, woonplaats, telefoonnummer,
                 email, reanimatiestatus, nationaliteit, afdeling, burgelijkestaat, foto)
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
        ",
            "ssssssssssss",
            $name,
            $gender,
            $address,
            $postalCode,
            $city,
            $phone,
            $email,
            $resuscitationStatus,
            $nationality,
            $department,
            $maritalStatus,
            $photo
        );
    }
}
