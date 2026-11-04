<?php
trait Medewerker
{
    /**
     * @param string $naam
     * @param string $klas
     * @param string|null $foto
     * @param string $email
     * @param string $telefoonnummer
     * @param string $wachtwoord
     * @return bool
     */
    public function updateMedewerker($naam, $klas, $foto, $email, $telefoonnummer, $wachtwoord): bool
    {
        $pdo = DatabaseConnection::getPdo();
        $stmt = $pdo->prepare("
            UPDATE medewerker 
            SET naam = :naam, klas = :klas, foto = :foto, email = :email, telefoonnummer = :telefoonnummer, wachtwoord = :wachtwoord 
            WHERE naam = :naamTarget
        ");
        $stmt->execute([
            'naam'           => $naam,
            'klas'           => $klas,
            'foto'           => $foto,
            'email'          => $email,
            'telefoonnummer' => $telefoonnummer,
            'wachtwoord'     => $wachtwoord,
            'naamTarget'     => $naam
        ]);

        if ($stmt->rowCount() > 0) {
            return true;
        }

        $checkStmt = $pdo->prepare("SELECT id FROM medewerker WHERE naam = :naam");
        $checkStmt->execute(['naam' => $naam]);
        if (!$checkStmt->fetch()) {
            $insertStmt = $pdo->prepare("
                INSERT INTO medewerker (naam, klas, foto, email, telefoonnummer, wachtwoord) 
                VALUES (:naam, :klas, :foto, :email, :telefoonnummer, :wachtwoord)
            ");
            $insertStmt->execute([
                'naam'           => $naam,
                'klas'           => $klas,
                'foto'           => $foto,
                'email'          => $email,
                'telefoonnummer' => $telefoonnummer,
                'wachtwoord'     => $wachtwoord
            ]);
            return true;
        }

        return false;
    }

    /**
     * @param int $id
     * @return bool
     */
    public function checkIfMedewerkerExistsById($id): bool
    {
        $result = $this->getMedewerkerById($id);
        if (sizeof($result) == 0)
            return false;

        return true;
    }

    /**
     * @param string $name
     * @return bool
     */
    public function checkIfMedewerkerExistsByName($name): bool
    {
        $result = $this->getMedewerkerByName($name);
        if (sizeof($result) == 0)
            return false;

        return true;
    }

    /**
     * @param int $id
     * @return array
     */
    public function getMedewerkerById($id): array
    {
        $stmt = DatabaseConnection::getPdo()->prepare("SELECT * FROM medewerker WHERE id = :id");
        $stmt->execute(['id' => (int)$id]);
        $res = $stmt->fetch(PDO::FETCH_BOTH);

        return $res ?: [];
    }

    /**
     * @param string $name
     * @return array
     */
    public function getMedewerkerByName($name): array
    {
        $stmt = DatabaseConnection::getPdo()->prepare("SELECT * FROM medewerker WHERE naam = :name");
        $stmt->execute(['name' => $name]);
        $res = $stmt->fetch(PDO::FETCH_BOTH);

        return $res ?: [];
    }
}
