<?php

class ClientModel
{
    private mysqli $db;

    public function __construct(?mysqli $db = null)
    {
        $this->db = $db ?? DatabaseConnection::getConn();
    }

    // ── Query Helpers ────────────────────────────────────────────────

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

    // ── Client Lookups ───────────────────────────────────────────────

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

    public function getClientById(int $id): array
    {
        return $this->getById($id) ?? [];
    }

    public function getClientByName(string $name): array
    {
        return $this->queryOne("
            SELECT * FROM client WHERE naam = ? LIMIT 1
        ", "s", $name) ?? [];
    }

    public function checkIfClientExistsById(int $id): bool
    {
        return $this->queryExists("
            SELECT id FROM client WHERE id = ? LIMIT 1
        ", "i", $id);
    }

    public function checkIfClientExistsByName(string $name): bool
    {
        return $this->queryExists("
            SELECT id FROM client WHERE naam = ? LIMIT 1
        ", "s", $name);
    }

    // ── Care Relations (verzorgerregel) ──────────────────────────────

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

    public function getCareRelationsByClientId(int $id): array
    {
        return $this->queryAll("
            SELECT * FROM verzorgerregel WHERE clientid = ?
        ", "i", $id);
    }

    public function getCaregiversById(int $id): array
    {
        return $this->queryOne("
            SELECT * FROM medewerker WHERE id = ? LIMIT 1
        ", "i", $id) ?? [];
    }

    // ── Patient Data ─────────────────────────────────────────────────

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

    // ── Medical Overview ─────────────────────────────────────────────

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
            "medischevoorgeschiedenis" => "No medical history recorded",
            "medicatie"                => "No medication recorded",
            "alergieen"                => "No allergies recorded",
            "opnamedatum"              => "No admission date recorded",
        ];
    }

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

        return "No admission date recorded";
    }

    public function checkIfMedicalOverviewExistsByClientId(int $id): bool
    {
        return $this->queryExists("
            SELECT id FROM medischoverzicht WHERE clientid = ? LIMIT 1
        ", "i", $id);
    }

    // ── Client Story ─────────────────────────────────────────────────

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

    public function 🥶()
    {
        return true;
    }

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

    // ── Care Plan ────────────────────────────────────────────────────

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

    // ── Client Update / Insert ───────────────────────────────────────

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
