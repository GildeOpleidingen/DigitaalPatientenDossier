<?php

class MedewerkerModel
{
    private PDO $db;

    // Medewerker entity properties
    public ?int $id = null;
    public string $naam = '';
    public string $klas = '';
    public ?string $foto = null;
    public ?string $email = null;
    public ?string $telefoonnummer = null;
    public ?string $wachtwoord = null;
    public string $grens_assistent = '0';
    public string $rol = 'medewerker';
    public ?int $is_deleted = 0;

    /**
     * @param array|PDO|mysqli|null $attributesOrDb
     * @param PDO|null $db
     */
    public function __construct(array|PDO|mysqli|null $attributesOrDb = null, ?PDO $db = null)
    {
        if ($attributesOrDb instanceof PDO) {
            $this->db = $attributesOrDb;
        } else {
            $this->db = $db ?? DatabaseConnection::getPdo();
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
        if (isset($data['klas'])) $this->klas = trim((string)$data['klas']);
        if (array_key_exists('foto', $data)) $this->foto = $data['foto'];
        if (isset($data['email'])) $this->email = trim((string)$data['email']);
        if (isset($data['telefoonnummer'])) $this->telefoonnummer = trim((string)$data['telefoonnummer']);
        if (isset($data['wachtwoord'])) $this->wachtwoord = (string)$data['wachtwoord'];
        if (isset($data['grens_assistent'])) $this->grens_assistent = (string)$data['grens_assistent'];
        if (isset($data['rol'])) $this->rol = (string)$data['rol'];
        if (isset($data['is_deleted'])) $this->is_deleted = (int)$data['is_deleted'];

        return $this;
    }

    /**
     * @return array
     */
    public function toArray(): array
    {
        return [
            'id'              => $this->id,
            'naam'            => $this->naam,
            'klas'            => $this->klas,
            'foto'            => $this->foto,
            'email'           => $this->email,
            'telefoonnummer'  => $this->telefoonnummer,
            'wachtwoord'      => $this->wachtwoord,
            'grens_assistent' => $this->grens_assistent,
            'rol'             => $this->rol,
            'is_deleted'      => $this->is_deleted,
        ];
    }

    /**
     * Valideert de medewerker data
     * @param array $errors
     * @param bool $isUpdate
     * @return bool
     */
    public function validate(array &$errors = [], bool $isUpdate = false): bool
    {
        $errors = [];

        if (empty($this->naam)) {
            $errors['naam'] = "Volledige naam is verplicht.";
        }

        if (empty($this->klas)) {
            $errors['klas'] = "Klas is verplicht.";
        }

        if (empty($this->email)) {
            $errors['email'] = "E-mailadres is verplicht.";
        } elseif (!filter_var($this->email, FILTER_VALIDATE_EMAIL)) {
            $errors['email'] = "Het ingevulde e-mailadres is ongeldig.";
        } else {
            // Uniciteitscontrole op e-mailadres
            try {
                if ($isUpdate && $this->id !== null) {
                    $stmt = $this->db->prepare("SELECT id FROM medewerker WHERE email = :email AND id != :id AND is_deleted = 0 LIMIT 1");
                    $stmt->execute(['email' => $this->email, 'id' => $this->id]);
                } else {
                    $stmt = $this->db->prepare("SELECT id FROM medewerker WHERE email = :email AND is_deleted = 0 LIMIT 1");
                    $stmt->execute(['email' => $this->email]);
                }
                if ($stmt->fetch()) {
                    $errors['email'] = "Er bestaat al een medewerker met dit e-mailadres.";
                }
            } catch (PDOException $e) {
                error_log("MedewerkerModel::validate email check error: " . $e->getMessage());
            }
        }

        // Telefoonnummer validatie: maximaal 9 cijfers
        if (!empty($this->telefoonnummer)) {
            $digits = preg_replace('/\D/', '', $this->telefoonnummer);
            if (strlen($digits) > 9) {
                $errors['telefoonnummer'] = "Het telefoonnummer mag maximaal 9 cijfers bevatten.";
            }
        }

        // Wachtwoord validatie
        if (!$isUpdate && empty($this->wachtwoord)) {
            $errors['wachtwoord'] = "Wachtwoord is verplicht.";
        } elseif (!empty($this->wachtwoord)) {
            if (password_needs_rehash($this->wachtwoord, PASSWORD_DEFAULT) && strlen($this->wachtwoord) < 4) {
                $errors['wachtwoord'] = "Het wachtwoord moet minimaal 4 tekens bevatten.";
            }
        }

        if (!in_array($this->rol, ['medewerker', 'beheerder'])) {
            $this->rol = 'medewerker';
        }

        return empty($errors);
    }

    /**
     * Voegt een nieuwe medewerker toe aan de database
     * @param array $data
     * @param array $errors
     * @return int|false Het nieuwe medewerker ID of false bij fout
     */
    public function create(array $data, array &$errors = []): int|false
    {
        $this->fill($data);

        if (!$this->validate($errors, false)) {
            return false;
        }

        try {
            $cleanPhone = !empty($this->telefoonnummer) ? preg_replace('/\D/', '', $this->telefoonnummer) : null;
            $is_deleted = 0;

            // Hash wachtwoord indien nog niet gehasht
            $passwordHash = $this->wachtwoord;
            if (password_needs_rehash($passwordHash, PASSWORD_DEFAULT)) {
                $passwordHash = password_hash($passwordHash, PASSWORD_DEFAULT);
            }

            $stmt = $this->db->prepare("
                INSERT INTO medewerker (
                    naam, klas, foto, email, telefoonnummer, wachtwoord, rol, grens_assistent, is_deleted
                ) VALUES (
                    :naam, :klas, :foto, :email, :telefoonnummer, :wachtwoord, :rol, :grens_assistent, :is_deleted
                )
            ");

            $stmt->execute([
                'naam'            => $this->naam,
                'klas'            => $this->klas,
                'foto'            => $this->foto,
                'email'           => $this->email,
                'telefoonnummer'  => $cleanPhone,
                'wachtwoord'      => $passwordHash,
                'rol'             => $this->rol,
                'grens_assistent' => $this->grens_assistent,
                'is_deleted'      => $is_deleted
            ]);

            $newId = (int)$this->db->lastInsertId();
            $this->id = $newId;
            $this->wachtwoord = $passwordHash;

            return $newId;
        } catch (PDOException $e) {
            error_log("MedewerkerModel::create error: " . $e->getMessage());
            $errors['db'] = "Databasefout bij opslaan: " . $e->getMessage();
            return false;
        }
    }

    /**
     * Werkt een bestaande medewerker bij op basis van uniek ID
     * @param int $id
     * @param array $data
     * @param array $errors
     * @return bool
     */
    public function updateById(int $id, array $data, array &$errors = []): bool
    {
        $existing = self::findById($id);
        if (!$existing) {
            $errors['not_found'] = "Medewerker met ID $id niet gevonden.";
            return false;
        }

        // Basisgegevens vullen
        $this->fill($existing);
        $this->fill($data);
        $this->id = $id;

        // Wachtwoord logica: als er geen nieuw wachtwoord is opgegeven, behoud het oude
        $newPasswordRaw = $data['wachtwoord'] ?? $data['password'] ?? null;
        if (!empty($newPasswordRaw)) {
            $this->wachtwoord = password_needs_rehash($newPasswordRaw, PASSWORD_DEFAULT)
                ? password_hash($newPasswordRaw, PASSWORD_DEFAULT)
                : $newPasswordRaw;
        } else {
            $this->wachtwoord = $existing['wachtwoord'];
        }

        // Foto logica: als er geen nieuwe foto is geüpload, behoud de oude
        $hasNewPhoto = array_key_exists('foto', $data) && $data['foto'] !== null;
        if (!$hasNewPhoto) {
            $this->foto = $existing['foto'];
        }

        if (!$this->validate($errors, true)) {
            return false;
        }

        try {
            $cleanPhone = !empty($this->telefoonnummer) ? preg_replace('/\D/', '', $this->telefoonnummer) : null;

            if ($hasNewPhoto) {
                $stmt = $this->db->prepare("
                    UPDATE medewerker 
                    SET naam = :naam, klas = :klas, foto = :foto, email = :email, 
                        telefoonnummer = :telefoonnummer, wachtwoord = :wachtwoord, 
                        rol = :rol, grens_assistent = :grens_assistent 
                    WHERE id = :id
                ");
                return $stmt->execute([
                    'naam'            => $this->naam,
                    'klas'            => $this->klas,
                    'foto'            => $this->foto,
                    'email'           => $this->email,
                    'telefoonnummer'  => $cleanPhone,
                    'wachtwoord'      => $this->wachtwoord,
                    'rol'             => $this->rol,
                    'grens_assistent' => $this->grens_assistent,
                    'id'              => $id
                ]);
            } else {
                $stmt = $this->db->prepare("
                    UPDATE medewerker 
                    SET naam = :naam, klas = :klas, email = :email, 
                        telefoonnummer = :telefoonnummer, wachtwoord = :wachtwoord, 
                        rol = :rol, grens_assistent = :grens_assistent 
                    WHERE id = :id
                ");
                return $stmt->execute([
                    'naam'            => $this->naam,
                    'klas'            => $this->klas,
                    'email'           => $this->email,
                    'telefoonnummer'  => $cleanPhone,
                    'wachtwoord'      => $this->wachtwoord,
                    'rol'             => $this->rol,
                    'grens_assistent' => $this->grens_assistent,
                    'id'              => $id
                ]);
            }
        } catch (PDOException $e) {
            error_log("MedewerkerModel::updateById error: " . $e->getMessage());
            $errors['db'] = "Databasefout bij bijwerken: " . $e->getMessage();
            return false;
        }
    }

    /**
     * Slaat de huidige instantie op (insert of update)
     * @param array $errors
     * @return bool
     */
    public function save(array &$errors = []): bool
    {
        if ($this->id !== null && $this->id > 0) {
            return $this->updateById($this->id, $this->toArray(), $errors);
        }

        $newId = $this->create($this->toArray(), $errors);
        return $newId !== false;
    }

    /**
     * Statische find helper om direct een MedewerkerModel instantie op te halen
     * @param int $id
     * @return self|null
     */
    public static function find(int $id): ?self
    {
        $row = self::findById($id);
        if (!$row) {
            return null;
        }
        $instance = new self();
        return $instance->fill($row);
    }

    // ==========================================
    // Lookup & Authentication Helpers
    // ==========================================

    /**
     * @param int $id
     * @return array|null
     */
    public static function findById(int $id): ?array
    {
        try {
            $stmt = DatabaseConnection::getPdo()->prepare("SELECT * FROM medewerker WHERE id = :id");
            $stmt->execute(['id' => $id]);
            $result = $stmt->fetch();

            return $result ?: null;
        } catch (PDOException $e) {
            error_log("MedewerkerModel::findById error: " . $e->getMessage());
            return null;
        }
    }

    /**
     * @param string $email
     * @return array|null
     */
    public static function findByEmail(string $email): ?array
    {
        try {
            $stmt = DatabaseConnection::getPdo()->prepare("SELECT * FROM medewerker WHERE email = :email");
            $stmt->execute(['email' => $email]);
            $result = $stmt->fetch();

            return $result ?: null;
        } catch (PDOException $e) {
            error_log("MedewerkerModel::findByEmail error: " . $e->getMessage());
            return null;
        }
    }

    /**
     * @param string $email
     * @param string $wachtwoord
     * @return array|null
     */
    public static function authenticate(string $email, string $wachtwoord): ?array
    {
        $user = self::findByEmail($email);

        $isDeleted = (int)($user['is_deleted'] ?? 0);
        if ($user && $isDeleted === 0 && !empty($user['wachtwoord']) && password_verify($wachtwoord, $user['wachtwoord'])) {
            return $user;
        }

        return null;
    }

    /**
     * @param int $id
     * @return bool
     */
    public static function delete(int $id): bool
    {
        try {
            $stmt = DatabaseConnection::getPdo()->prepare(
                "UPDATE medewerker SET is_deleted = 1 WHERE id = :id"
            );
            return $stmt->execute(['id' => $id]);
        } catch (PDOException $e) {
            error_log("MedewerkerModel::delete error: " . $e->getMessage());
            return false;
        }
    }
}
