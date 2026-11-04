<?php
trait Client
{
    /**
     * @param int $clientId
     * @param int $medewerkerId
     * @return bool
     */
    public function CheckIfVerzorgregelExists($clientId, $medewerkerId)
    {
        try {
            $stmt = DatabaseConnection::getPdo()->prepare("
                INSERT INTO verzorgerregel (clientid, medewerkerid, is_deleted)
                VALUES (:clientId, :medewerkerId, 0)
                ON DUPLICATE KEY UPDATE is_deleted = 0
            ");
            return $stmt->execute([
                'clientId'     => $clientId,
                'medewerkerId' => $medewerkerId
            ]);
        } catch (Exception $e) {
            return false;
        }
    }

    /**
     * @param int $clientid
     * @param string|null $foto
     * @param string|null $introductie
     * @param string|null $familie
     * @param string|null $belangrijkeinfo
     * @param string|null $hobbies
     * @return bool
     */
    public function insertClientStory($clientid, $foto, $introductie, $familie, $belangrijkeinfo, $hobbies): bool
    {
        $medischOverzicht = $this->getMedischOverzichtByClientId($clientid);
        if ($this->checkIfClientExistsById($clientid) && count($medischOverzicht) > 0) {
            $pdo = DatabaseConnection::getPdo();
            if (!$this->checkIfClientStoryExistsByClientId($clientid)) {
                if (!$this->checkIfMedischOverzichtExistsByClientId($clientid)) {
                    $stmtMo = $pdo->prepare("INSERT INTO medischoverzicht (clientid) VALUES (:clientId)");
                    $stmtMo->execute(['clientId' => $clientid]);
                    $medischOverzichtId = (int)$pdo->lastInsertId();
                } else {
                    $medischOverzicht = $this->getMedischOverzichtByClientId($clientid);
                    $medischOverzichtId = (int)$medischOverzicht['id'];
                }

                $stmt = $pdo->prepare("
                    INSERT INTO clientverhaal (
                        medischoverzichtid, foto, introductie, gezinfamilie, belangrijkeinfo, hobbies
                    ) VALUES (
                        :medischoverzichtid, :foto, :introductie, :gezinfamilie, :belangrijkeinfo, :hobbies
                    )
                ");
                return $stmt->execute([
                    'medischoverzichtid' => $medischOverzichtId,
                    'foto'               => $foto,
                    'introductie'        => $introductie,
                    'gezinfamilie'       => $familie,
                    'belangrijkeinfo'    => $belangrijkeinfo,
                    'hobbies'            => $hobbies
                ]);
            } else {
                $stmt = $pdo->prepare("
                    UPDATE clientverhaal 
                    SET foto = :foto, 
                        introductie = :introductie, 
                        gezinfamilie = :gezinfamilie, 
                        belangrijkeinfo = :belangrijkeinfo, 
                        hobbies = :hobbies 
                    WHERE medischoverzichtid = :medischoverzichtid
                ");
                return $stmt->execute([
                    'foto'               => $foto,
                    'introductie'        => $introductie,
                    'gezinfamilie'       => $familie,
                    'belangrijkeinfo'    => $belangrijkeinfo,
                    'hobbies'            => $hobbies,
                    'medischoverzichtid' => $medischOverzicht['id']
                ]);
            }
        } else {
            return false;
        }
    }

    /**
     * @param string $naam
     * @param string|null $geslacht
     * @param string|null $adres
     * @param string|null $postcode
     * @param string|null $woonplaats
     * @param string|null $telefoonnummer
     * @param string|null $email
     * @param string|null $reanimatiestatus
     * @param string|null $nationaliteit
     * @param string|null $afdeling
     * @param string|null $burgelijkestaat
     * @param string|null $foto
     * @return bool
     */
    public function updateClient($naam, $geslacht, $adres, $postcode, $woonplaats, $telefoonnummer, $email, $reanimatiestatus, $nationaliteit, $afdeling, $burgelijkestaat, $foto): bool
    {
        $pdo = DatabaseConnection::getPdo();
        $stmt = $pdo->prepare("
            UPDATE client 
            SET geslacht = :geslacht, adres = :adres, postcode = :postcode, woonplaats = :woonplaats, 
                telefoonnummer = :telefoonnummer, email = :email, reanimatiestatus = :reanimatiestatus, 
                nationaliteit = :nationaliteit, afdeling = :afdeling, burgelijkestaat = :burgelijkestaat, 
                foto = :foto 
            WHERE naam = :targetNaam
        ");
        $stmt->execute([
            'geslacht'         => $geslacht,
            'adres'            => $adres,
            'postcode'         => $postcode,
            'woonplaats'       => $woonplaats,
            'telefoonnummer'   => $telefoonnummer,
            'email'            => $email,
            'reanimatiestatus' => $reanimatiestatus,
            'nationaliteit'    => $nationaliteit,
            'afdeling'         => $afdeling,
            'burgelijkestaat'  => $burgelijkestaat,
            'foto'             => $foto,
            'targetNaam'       => $naam
        ]);

        if ($stmt->rowCount() > 0) {
            return true;
        }

        $checkStmt = $pdo->prepare("SELECT id FROM client WHERE naam = :naam");
        $checkStmt->execute(['naam' => $naam]);
        if (!$checkStmt->fetch()) {
            $insertStmt = $pdo->prepare("
                INSERT INTO client (
                    naam, geslacht, adres, postcode, woonplaats, telefoonnummer, 
                    email, reanimatiestatus, nationaliteit, afdeling, burgelijkestaat, foto
                ) VALUES (
                    :naam, :geslacht, :adres, :postcode, :woonplaats, :telefoonnummer, 
                    :email, :reanimatiestatus, :nationaliteit, :afdeling, :burgelijkestaat, :foto
                )
            ");
            return $insertStmt->execute([
                'naam'             => $naam,
                'geslacht'         => $geslacht,
                'adres'            => $adres,
                'postcode'         => $postcode,
                'woonplaats'       => $woonplaats,
                'telefoonnummer'   => $telefoonnummer,
                'email'            => $email,
                'reanimatiestatus' => $reanimatiestatus,
                'nationaliteit'    => $nationaliteit,
                'afdeling'         => $afdeling,
                'burgelijkestaat'  => $burgelijkestaat,
                'foto'             => $foto
            ]);
        }

        return false;
    }

    /**
     * @param int $id
     * @return bool
     */
    public function checkIfClientStoryExistsByClientId($id): bool
    {
        $stmt = DatabaseConnection::getPdo()->prepare("
            SELECT clientverhaal.id
            FROM client
            INNER JOIN medischoverzicht ON medischoverzicht.clientid = client.id 
            INNER JOIN clientverhaal    ON clientverhaal.medischoverzichtid = medischoverzicht.id
            WHERE client.id = :clientId
            LIMIT 1
        ");
        $stmt->execute(['clientId' => $id]);

        return (bool)$stmt->fetch();
    }

    /**
     * @param int $clientid
     * @return bool
     */
    public function checkIfMedischOverzichtExistsByClientId($clientid): bool
    {
        $stmt = DatabaseConnection::getPdo()->prepare("
            SELECT id
            FROM medischoverzicht
            WHERE clientid = :clientId
            LIMIT 1
        ");
        $stmt->execute(['clientId' => $clientid]);

        return (bool)$stmt->fetch();
    }

    /**
     * @param int $id
     * @return bool
     */
    public function checkIfCarePlanExistsByClientId($id): bool
    {
        $stmt = DatabaseConnection::getPdo()->prepare("
            SELECT zorgplan.id
            FROM client
            INNER JOIN zorgplan ON zorgplan.clientid = client.id
            WHERE client.id = :clientId
            LIMIT 1
        ");
        $stmt->execute(['clientId' => $id]);

        return (bool)$stmt->fetch();
    }

    /**
     * @param int $id
     * @return array
     */
    public function getClientStoryByClientId($id): array
    {
        $stmt = DatabaseConnection::getPdo()->prepare("
            SELECT clientverhaal.*
            FROM client
            INNER JOIN medischoverzicht ON medischoverzicht.clientid = client.id 
            INNER JOIN clientverhaal    ON clientverhaal.medischoverzichtid = medischoverzicht.id
            WHERE client.id = :clientId
        ");
        $stmt->execute(['clientId' => $id]);
        $row = $stmt->fetch(PDO::FETCH_BOTH);

        return $row ?: [];
    }

    /**
     * @param int $id
     * @return array
     */
    public function getCarePlanByClientId($id): array
    {
        $stmt = DatabaseConnection::getPdo()->prepare("
            SELECT zorgplan.*
            FROM client
            INNER JOIN zorgplan ON zorgplan.clientid = client.id
            WHERE client.id = :clientId
        ");
        $stmt->execute(['clientId' => $id]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        return $row ?: [];
    }

    /**
     * @param int $id
     * @return array
     */
    public function getVerzorgerregelByClientId($id): array
    {
        $stmt = DatabaseConnection::getPdo()->prepare("
            SELECT *
            FROM verzorgerregel
            WHERE clientid = :clientId AND is_deleted = 0
        ");
        $stmt->execute(['clientId' => $id]);

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * @param int $id
     * @return string
     */
    public function getAdmissionDateByClientId($id): string
    {
        $stmt = DatabaseConnection::getPdo()->prepare("
            SELECT opnamedatum
            FROM medischoverzicht
            WHERE clientid = :clientId
        ");
        $stmt->execute(['clientId' => $id]);
        $opnamedatum = $stmt->fetchColumn();

        if ($opnamedatum && $opnamedatum !== '0000-00-00 00:00:00') {
            return $opnamedatum;
        }

        return "Geen opnamedatum ingevuld";
    }

    /**
     * @param int $id
     * @return array
     */
    public function getMedischOverzichtByClientId($id): array
    {
        $stmt = DatabaseConnection::getPdo()->prepare("
            SELECT medischoverzicht.*
            FROM client
            INNER JOIN medischoverzicht ON medischoverzicht.clientid = client.id 
            WHERE client.id = :clientId
        ");
        $stmt->execute(['clientId' => $id]);
        $mo = $stmt->fetch(PDO::FETCH_BOTH);

        if (!empty($mo)) {
            return $mo;
        }

        return [
            "medischevoorgeschiedenis" => "Geen medische voorgeschiedenis ingevuld",
            "medicatie"                => "Geen medicatie ingevuld",
            "alergieen"                => "Geen allergieën ingevuld",
            "opnamedatum"              => "Geen opnamedatum ingevuld",
        ];
    }

    /**
     * @param int $id
     * @return bool
     */
    public function checkIfClientExistsById(int $id): bool
    {
        $result = $this->getClientById($id);

        return sizeof((array) $result) > 0;
    }

    /**
     * @param string $name
     * @return bool
     */
    public function checkIfClientExistsByName(string $name): bool
    {
        $result = $this->getClientByName($name);

        return sizeof((array) $result) > 0;
    }

    /**
     * @param int $ClientId
     * @return array
     */
    public function getClientById($ClientId): array
    {
        $stmt = DatabaseConnection::getPdo()->prepare("
            SELECT 
                client.*, 
                afdelingen.naam AS afdeling 
            FROM client
            LEFT JOIN afdelingen ON afdelingen.id = client.afdeling_id 
            WHERE client.id = :clientId
        ");
        $stmt->execute(['clientId' => $ClientId]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        return $row ?: [];
    }

    /**
     * @param string $name
     * @return array
     */
    public function getClientByName($name): array
    {
        $stmt = DatabaseConnection::getPdo()->prepare("SELECT * FROM client WHERE naam = :name");
        $stmt->execute(['name' => $name]);
        $row = $stmt->fetch(PDO::FETCH_BOTH);

        return $row ?: [];
    }

    /**
     * @param int $id
     * @return array
     */
    public function getVerzorgersById($id): array
    {
        $stmt = DatabaseConnection::getPdo()->prepare("SELECT * FROM medewerker WHERE id = :id");
        $stmt->execute(['id' => $id]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        return $row ?: [];
    }

    /**
     * @param int $id
     * @param 'clientRelations'|'contactPersonen'|'medischOverzicht'|'verzorgersArr'|string $type
     * @return array|false
     */
    public function getPatientGegevens($id, $type)
    {
        $pdo = DatabaseConnection::getPdo();
        if ($type == 'clientRelations') {
            $stmt = $pdo->prepare("
                SELECT * 
                FROM verzorgerregel 
                WHERE clientid = :clientId AND is_deleted = 0
            ");
            $stmt->execute(['clientId' => $id]);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } elseif ($type == 'contactPersonen') {
            $stmt = $pdo->prepare("
                SELECT * 
                FROM relatie 
                WHERE clientid = :clientId
            ");
            $stmt->execute(['clientId' => $id]);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } elseif ($type == 'medischOverzicht') {
            $stmt = $pdo->prepare("
                SELECT * 
                FROM medischoverzicht 
                WHERE clientid = :clientId
            ");
            $stmt->execute(['clientId' => $id]);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } elseif ($type == 'verzorgersArr') {
            $stmt = $pdo->prepare("
                SELECT medewerker.* 
                FROM medewerker
                INNER JOIN verzorgerregel ON medewerker.id = verzorgerregel.medewerkerid
                WHERE verzorgerregel.clientid = :clientId 
                  AND medewerker.is_deleted = 0 
                  AND verzorgerregel.is_deleted = 0
            ");
            $stmt->execute(['clientId' => $id]);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } else {
            return false;
        }
    }
}
