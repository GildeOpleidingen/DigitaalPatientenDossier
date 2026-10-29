<?php

class ClientModel
{
    private mysqli $db;

    // Client entity properties
    public ?int $id = null;
    public string $naam = '';
    public string $geslacht = 'M';
    public string $adres = '';
    public string $postcode = '';
    public string $woonplaats = '';
    public ?string $telefoonnummer = null;
    public ?string $email = null;
    public ?string $geboortedatum = null;
    public int $reanimatiestatus = 1;
    public string $nationaliteit = 'Nederlandse';
    public ?int $afdeling_id = null;
    public string $burgelijkestaat = 'ongehuwd';
    public ?string $foto = null;
    public int $is_deleted = 0;

    /**
     * @param array|mysqli|null $attributesOrDb
     * @param mysqli|null $db
     */
    public function __construct(array|mysqli|null $attributesOrDb = null, ?mysqli $db = null)
    {
        if ($attributesOrDb instanceof mysqli) {
            $this->db = $attributesOrDb;
        } else {
            $this->db = $db ?? DatabaseConnection::getConn();
            if (is_array($attributesOrDb)) {
                $this->fill($attributesOrDb);
            }
        }
    }

    /**
     * @param array $data
     * @return self
     */
    public function fill(array $data): self
    {
        if (isset($data['id'])) $this->id = (int)$data['id'];
        if (isset($data['naam'])) $this->naam = trim((string)$data['naam']);
        if (isset($data['geslacht'])) $this->geslacht = (string)$data['geslacht'];
        if (isset($data['adres'])) $this->adres = trim((string)$data['adres']);
        if (isset($data['postcode'])) $this->postcode = trim((string)$data['postcode']);
        if (isset($data['woonplaats'])) $this->woonplaats = trim((string)$data['woonplaats']);
        if (isset($data['telefoonnummer'])) $this->telefoonnummer = trim((string)$data['telefoonnummer']);
        if (isset($data['email'])) $this->email = trim((string)$data['email']);
        if (isset($data['geboortedatum'])) $this->geboortedatum = !empty($data['geboortedatum']) ? (string)$data['geboortedatum'] : null;
        if (isset($data['reanimatiestatus'])) $this->reanimatiestatus = (int)$data['reanimatiestatus'];
        if (isset($data['nationaliteit'])) $this->nationaliteit = trim((string)$data['nationaliteit']);
        if (isset($data['afdeling_id'])) $this->afdeling_id = !empty($data['afdeling_id']) ? (int)$data['afdeling_id'] : null;
        elseif (isset($data['afdeling'])) $this->afdeling_id = !empty($data['afdeling']) ? (int)$data['afdeling'] : null;
        if (isset($data['burgelijkestaat'])) $this->burgelijkestaat = (string)$data['burgelijkestaat'];
        if (array_key_exists('foto', $data)) $this->foto = $data['foto'];
        if (isset($data['is_deleted'])) $this->is_deleted = (int)$data['is_deleted'];

        return $this;
    }

    /**
     * @return array
     */
    public function toArray(): array
    {
        return [
            'id'               => $this->id,
            'naam'             => $this->naam,
            'geslacht'         => $this->geslacht,
            'adres'            => $this->adres,
            'postcode'         => $this->postcode,
            'woonplaats'       => $this->woonplaats,
            'telefoonnummer'   => $this->telefoonnummer,
            'email'            => $this->email,
            'geboortedatum'    => $this->geboortedatum,
            'reanimatiestatus' => $this->reanimatiestatus,
            'nationaliteit'    => $this->nationaliteit,
            'afdeling_id'      => $this->afdeling_id,
            'burgelijkestaat'  => $this->burgelijkestaat,
            'foto'             => $this->foto,
            'is_deleted'       => $this->is_deleted,
        ];
    }

    /**
     * Valideert de client data
     * @param array $errors
     * @return bool
     */
    public function validate(array &$errors = []): bool
    {
        $errors = [];

        if (empty($this->naam)) {
            $errors['naam'] = "Volledige naam is verplicht.";
        }
        if (empty($this->adres)) {
            $errors['adres'] = "Adres is verplicht.";
        }
        if (empty($this->postcode)) {
            $errors['postcode'] = "Postcode is verplicht.";
        }
        if (empty($this->woonplaats)) {
            $errors['woonplaats'] = "Woonplaats is verplicht.";
        }
        if (empty($this->geboortedatum)) {
            $errors['geboortedatum'] = "Geboortedatum is verplicht.";
        }
        if (!in_array($this->geslacht, ['M', 'V', 'O', 'X'])) {
            $errors['geslacht'] = "Ongeldig geslacht geselecteerd.";
        }

        // Telefoonnummer validatie: de gebruiker vereist specifiek 9 cijfers zodat het geen echt nummer kan zijn
        if (!empty($this->telefoonnummer)) {
            $digits = preg_replace('/\D/', '', $this->telefoonnummer);
            if (strlen($digits) !== 9) {
                $errors['telefoonnummer'] = "Het telefoonnummer moet exact 9 cijfers bevatten.";
            }
        }

        if (!empty($this->email) && !filter_var($this->email, FILTER_VALIDATE_EMAIL)) {
            $errors['email'] = "Het ingevulde e-mailadres is ongeldig.";
        }

        return empty($errors);
    }

    /**
     * Voegt een nieuwe cliënt toe aan de database
     * @param array $data
     * @param int|null $caregiverId
     * @param array $errors
     * @return int|false Het nieuwe client ID of false bij fout
     */
    public function create(array $data, ?int $caregiverId = null, array &$errors = []): int|false
    {
        $this->fill($data);

        if (!$this->validate($errors)) {
            return false;
        }

        try {
            $cleanPhone = !empty($this->telefoonnummer) ? preg_replace('/\D/', '', $this->telefoonnummer) : '';
            $is_deleted = 0;

            $stmt = $this->db->prepare("
                INSERT INTO client (
                    naam, geslacht, adres, postcode, woonplaats, telefoonnummer,
                    email, geboortedatum, reanimatiestatus, nationaliteit,
                    burgelijkestaat, foto, afdeling_id, is_deleted
                ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
            ");

            if (!$stmt) {
                $errors['db'] = "Database voorbereidingsfout: " . $this->db->error;
                return false;
            }

            $stmt->bind_param(
                "ssssssssssssii",
                $this->naam,
                $this->geslacht,
                $this->adres,
                $this->postcode,
                $this->woonplaats,
                $cleanPhone,
                $this->email,
                $this->geboortedatum,
                $this->reanimatiestatus,
                $this->nationaliteit,
                $this->burgelijkestaat,
                $this->foto,
                $this->afdeling_id,
                $is_deleted
            );

            if ($this->foto !== null) {
                $stmt->send_long_data(11, $this->foto);
            }

            if (!$stmt->execute()) {
                $errors['db'] = "Fout bij opslaan client: " . $stmt->error;
                $stmt->close();
                return false;
            }

            $newId = (int)$this->db->insert_id;
            $this->id = $newId;
            $stmt->close();

            // Automatische koppeling aan de huidige verzorger/medewerker
            if ($caregiverId !== null) {
                $this->checkIfCareRelationExists($newId, $caregiverId);
            }

            // Automatische aanmaak van het medisch overzicht zodat dossier & cliëntverhaal direct werken
            $checkMO = $this->db->prepare("INSERT INTO medischoverzicht (clientid, opnamedatum, opnameindicatie) VALUES (?, NOW(), '')");
            if ($checkMO) {
                $checkMO->bind_param("i", $newId);
                $checkMO->execute();
                $checkMO->close();
            }

            return $newId;
        } catch (mysqli_sql_exception $e) {
            error_log("ClientModel::create error: " . $e->getMessage());
            $errors['db'] = "Databasefout bij opslaan: " . $e->getMessage();
            return false;
        }
    }

    /**
     * Werkt een bestaande cliënt bij op basis van uniek primary key ID
     * @param int $id
     * @param array $data
     * @param array $errors
     * @return bool
     */
    public function updateById(int $id, array $data, array &$errors = []): bool
    {
        $existing = $this->getById($id);
        if (!$existing) {
            $errors['not_found'] = "Client met ID $id niet gevonden.";
            return false;
        }

        $this->fill($existing);
        $this->fill($data);
        $this->id = $id;

        if (!$this->validate($errors)) {
            return false;
        }

        try {
            $cleanPhone = !empty($this->telefoonnummer) ? preg_replace('/\D/', '', $this->telefoonnummer) : '';

            // Als er een nieuwe foto is geüpload
            if ($this->foto !== null) {
                $sql = "UPDATE client SET naam = ?, geslacht = ?, adres = ?, postcode = ?, woonplaats = ?, telefoonnummer = ?, email = ?, geboortedatum = ?, reanimatiestatus = ?, nationaliteit = ?, afdeling_id = ?, burgelijkestaat = ?, foto = ? WHERE id = ?";
                $stmt = $this->db->prepare($sql);
                if (!$stmt) {
                    $errors['db'] = "Database voorbereidingsfout: " . $this->db->error;
                    return false;
                }
                $stmt->bind_param(
                    "sssssssssssssi",
                    $this->naam,
                    $this->geslacht,
                    $this->adres,
                    $this->postcode,
                    $this->woonplaats,
                    $cleanPhone,
                    $this->email,
                    $this->geboortedatum,
                    $this->reanimatiestatus,
                    $this->nationaliteit,
                    $this->afdeling_id,
                    $this->burgelijkestaat,
                    $this->foto,
                    $id
                );
                $stmt->send_long_data(12, $this->foto);
            } else {
                // Foto blijft ongewijzigd
                $sql = "UPDATE client SET naam = ?, geslacht = ?, adres = ?, postcode = ?, woonplaats = ?, telefoonnummer = ?, email = ?, geboortedatum = ?, reanimatiestatus = ?, nationaliteit = ?, afdeling_id = ?, burgelijkestaat = ? WHERE id = ?";
                $stmt = $this->db->prepare($sql);
                if (!$stmt) {
                    $errors['db'] = "Database voorbereidingsfout: " . $this->db->error;
                    return false;
                }
                $stmt->bind_param(
                    "ssssssssssssi",
                    $this->naam,
                    $this->geslacht,
                    $this->adres,
                    $this->postcode,
                    $this->woonplaats,
                    $cleanPhone,
                    $this->email,
                    $this->geboortedatum,
                    $this->reanimatiestatus,
                    $this->nationaliteit,
                    $this->afdeling_id,
                    $this->burgelijkestaat,
                    $id
                );
            }

            $success = $stmt->execute();
            $stmt->close();
            return $success;
        } catch (mysqli_sql_exception $e) {
            error_log("ClientModel::updateById error: " . $e->getMessage());
            $errors['db'] = "Databasefout bij bijwerken: " . $e->getMessage();
            return false;
        }
    }

    /**
     * Slaat de huidige instantie op (insert of update)
     * @param int|null $caregiverId
     * @param array $errors
     * @return bool
     */
    public function save(?int $caregiverId = null, array &$errors = []): bool
    {
        if ($this->id !== null && $this->id > 0) {
            return $this->updateById($this->id, $this->toArray(), $errors);
        }

        $newId = $this->create($this->toArray(), $caregiverId, $errors);
        return $newId !== false;
    }

    /**
     * Statische find helper om direct een ClientModel instantie op te halen
     * @param int $id
     * @return self|null
     */
    public static function find(int $id): ?self
    {
        $instance = new self();
        $row = $instance->getById($id);
        if (!$row) {
            return null;
        }
        return $instance->fill($row);
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
    public function getCaregiversById(int $id): array
    {
        return $this->queryOne("
            SELECT * FROM medewerker WHERE id = ? LIMIT 1
        ", "i", $id) ?? [];
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
                WHERE vr.clientid = ? AND m.is_deleted = 0
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
            $deptId = (!empty($department) && is_numeric($department)) ? (int)$department : null;
            $stmt = $this->db->prepare("
                UPDATE client
                SET geslacht = ?, adres = ?, postcode = ?, woonplaats = ?,
                    telefoonnummer = ?, email = ?, reanimatiestatus = ?,
                    nationaliteit = ?, afdeling_id = ?, burgelijkestaat = ?, foto = ?
                WHERE naam = ?
            ");

            if (!$stmt) {
                return false;
            }

            $stmt->bind_param(
                "ssssssssisss",
                $gender,
                $address,
                $postalCode,
                $city,
                $phone,
                $email,
                $resuscitationStatus,
                $nationality,
                $deptId,
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
        $deptId = (!empty($department) && is_numeric($department)) ? (int)$department : null;
        $createdId = $this->create([
            'naam'             => $name,
            'geslacht'         => $gender,
            'adres'            => $address,
            'postcode'         => $postalCode,
            'woonplaats'       => $city,
            'telefoonnummer'   => $phone,
            'email'            => $email,
            'geboortedatum'    => '2000-01-01',
            'reanimatiestatus' => (int)$resuscitationStatus,
            'nationaliteit'    => $nationality,
            'afdeling_id'      => $deptId,
            'burgelijkestaat'  => $maritalStatus,
            'foto'             => $photo,
        ]);

        return $createdId !== false;
    }
}
