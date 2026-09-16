<?php

class ClientModel
{
    private mysqli $db;

    public function __construct(?mysqli $db = null)
    {
        $this->db = $db ?? DatabaseConnection::getConn();
    }

    public function CheckIfVerzorgregelExists($clientId, $medewerkerId): bool
    {
        try {
            $stmt = $this->db->prepare("
                        SELECT id
                        FROM verzorgerregel
                        WHERE clientid = ?
                        AND medewerkerid = ?");
            if (!$stmt) {
                return false;
            }
            $stmt->bind_param("ii", $clientId, $medewerkerId);
            $stmt->execute();
            $stmt->store_result();

            if ($stmt->num_rows == 0) {
                $stmt->close();
                $insert = $this->db->prepare("INSERT INTO verzorgerregel (clientid, medewerkerid) VALUES (?, ?)");
                if (!$insert) {
                    return false;
                }
                $insert->bind_param("ii", $clientId, $medewerkerId);
                $success = $insert->execute();
                $insert->close();
                return (bool)$success;
            } else {
                $stmt->close();
                return true;
            }
        } catch (Exception $e) {
            return false;
        }
    }

    public function insertClientStory($clientid, $foto, $introductie, $familie, $belangrijkeinfo, $hobbies): bool
    {
        $medischOverzicht = $this->getMedischOverzichtByClientId($clientid);
        if ($this->checkIfClientExistsById((int)$clientid) && count($medischOverzicht) > 0) {
            if (!$this->checkIfClientStoryExistsByClientId($clientid)) {
                if (!$this->checkIfMedischOverzichtExistsByClientId($clientid)) {
                    $result = $this->db->prepare("INSERT INTO `medischoverzicht`(`clientid`) VALUES (?);");
                    if (!$result) {
                        return false;
                    }
                    $result->bind_param("i", $clientid);
                    $result->execute();
                    $result->close();

                    $medischOverzichtId = $this->db->insert_id;
                } else {
                    $medischOverzicht = $this->getMedischOverzichtByClientId($clientid);
                    $medischOverzichtId = $medischOverzicht['id'];
                }

                $result = $this->db->prepare("INSERT INTO `clientverhaal`(`id`, `medischoverzichtid`, `foto`, `introductie`, `gezinfamilie`, `belangrijkeinfo`, `hobbies`) VALUES (NULL, ?, ?, ?, ?, ?, ?);");
                if (!$result) {
                    return false;
                }
                $result->bind_param("isssss", $medischOverzichtId, $foto, $introductie, $familie, $belangrijkeinfo, $hobbies);
                $success = $result->execute();
                $result->close();
                return (bool)$success;
            } else {
                $result = $this->db->prepare("UPDATE `clientverhaal` SET `foto`=?,`introductie`=?,`gezinfamilie`=?,`belangrijkeinfo`=?,`hobbies`=? WHERE medischoverzichtid = ?;");
                if (!$result) {
                    return false;
                }
                $result->bind_param("sssssi", $foto, $introductie, $familie, $belangrijkeinfo, $hobbies, $medischOverzicht['id']);
                $success = $result->execute();
                $result->close();
                return (bool)$success;
            }
        } else {
            return false;
        }
    }

    public function updateClient($naam, $geslacht, $adres, $postcode, $woonplaats, $telefoonnummer, $email, $reanimatiestatus, $nationaliteit, $afdeling, $burgelijkestaat, $foto): bool
    {
        $result = $this->db->prepare("UPDATE `client` SET `geslacht`=?,`adres`=?,`postcode`=?,`woonplaats`=?,`telefoonnummer`=?,`email`=?,`reanimatiestatus`=?,`nationaliteit`=?,`afdeling`=?,`burgelijkestaat`=?,`foto`=? WHERE `naam`=?;");
        if (!$result) {
            return false;
        }
        $result->bind_param("ssssssssssss", $geslacht, $adres, $postcode, $woonplaats, $telefoonnummer, $email, $reanimatiestatus, $nationaliteit, $afdeling, $burgelijkestaat, $foto, $naam);
        $result->execute();

        if ($result->affected_rows == 1) {
            $result->close();
            return true;
        }
        $result->close();

        // Als client nog niet bestond, controleer of de naam voorkomt
        $query = $this->db->prepare("SELECT id FROM `client` WHERE naam = ?");
        if (!$query) {
            return false;
        }
        $query->bind_param("s", $naam);
        $query->execute();
        $queryResult = $query->get_result()->fetch_all();
        $query->close();

        if (count($queryResult) == 0) {
            $insert = $this->db->prepare("INSERT INTO `client`(`naam`, `geslacht`, `adres`, `postcode`, `woonplaats`, `telefoonnummer`, `email`, `reanimatiestatus`, `nationaliteit`, `afdeling`, `burgelijkestaat`, `foto`) VALUES (?,?,?,?,?,?,?,?,?,?,?,?);");
            if (!$insert) {
                return false;
            }
            $insert->bind_param("ssssssssssss", $naam, $geslacht, $adres, $postcode, $woonplaats, $telefoonnummer, $email, $reanimatiestatus, $nationaliteit, $afdeling, $burgelijkestaat, $foto);
            $success = $insert->execute();
            $insert->close();
            return (bool)$success;
        }

        return false;
    }

    public function checkIfClientStoryExistsByClientId($id): bool
    {
        $result = $this->db->prepare("
        SELECT cv.id
        FROM client c
        JOIN medischoverzicht mo on mo.clientid = c.id 
        JOIN clientverhaal cv on cv.medischoverzichtid = mo.id
        WHERE c.id = ?
        ");
        if (!$result) {
            return false;
        }
        $result->bind_param("i", $id);
        $result->execute();
        $res = $result->get_result();
        $exists = ($res && $res->num_rows > 0);
        $result->close();

        return $exists;
    }

    public function checkIfMedischOverzichtExistsByClientId($clientid): bool
    {
        $result = $this->db->prepare("
        SELECT id
        FROM medischoverzicht
        WHERE clientid = ?
        ");
        if (!$result) {
            return false;
        }
        $result->bind_param("i", $clientid);
        $result->execute();
        $res = $result->get_result();
        $exists = ($res && $res->num_rows > 0);
        $result->close();

        return $exists;
    }

    public function checkIfCarePlanExistsByClientId($id): bool
    {
        $result = $this->db->prepare("
        SELECT cp.id
        FROM client c
        JOIN zorgplan cp on cp.clientid = c.id
        WHERE c.id = ?
        ");
        if (!$result) {
            return false;
        }
        $result->bind_param("i", $id);
        $result->execute();
        $res = $result->get_result();
        $exists = ($res && $res->num_rows > 0);
        $result->close();

        return $exists;
    }

    public function getClientStoryByClientId($id): array
    {
        $result = $this->db->prepare("
        SELECT cv.*
        FROM client c
        JOIN medischoverzicht mo on mo.clientid = c.id 
        join clientverhaal cv on cv.medischoverzichtid = mo.id
        where c.id = ?
        ");
        if (!$result) {
            return [];
        }

        $result->bind_param("i", $id);
        $result->execute();
        $row = $result->get_result()->fetch_assoc();
        $result->close();

        return $row ?: [];
    }

    public function getCarePlanByClientId($id): array
    {
        $result = $this->db->prepare("
        SELECT cp.*
        FROM client c
        JOIN zorgplan cp on cp.clientid = c.id
        WHERE c.id = ?
        ");
        if (!$result) {
            return [];
        }

        $result->bind_param("i", $id);
        $result->execute();
        $row = $result->get_result()->fetch_assoc();
        $result->close();

        return $row ?: [];
    }

    public function getVerzorgerregelByClientId($id): array
    {
        $result = $this->db->prepare("
        SELECT *
        FROM verzorgerregel
        WHERE clientid = ?
        ");
        if (!$result) {
            return [];
        }

        $result->bind_param("i", $id);
        $result->execute();
        $rows = $result->get_result()->fetch_all(MYSQLI_ASSOC);
        $result->close();

        return $rows ?: [];
    }

    public function getAdmissionDateByClientId($id): string
    {
        $result = $this->db->prepare("
        select opnamedatum
        from medischoverzicht
        where clientid = ?
        ");
        if (!$result) {
            return "Geen opnamedatum ingevuld";
        }
        $result->bind_param("i", $id);
        $result->execute();
        $res = $result->get_result();
        $opnamedatum = $res ? $res->fetch_assoc() : null;
        $result->close();

        if ($opnamedatum && !empty($opnamedatum['opnamedatum']) && $opnamedatum['opnamedatum'] !== '0000-00-00 00:00:00' && $opnamedatum['opnamedatum'] !== '0000-00-00') {
            return (string)$opnamedatum['opnamedatum'];
        } else {
            return "Geen opnamedatum ingevuld";
        }
    }

    public function getMedischOverzichtByClientId($id): array
    {
        $result = $this->db->prepare("
        SELECT mo.*
        FROM client c
        JOIN medischoverzicht mo on mo.clientid = c.id 
        where c.id = ?
        ");
        if (!$result) {
            return $this->getDefaultMedischOverzicht();
        }

        $result->bind_param("i", $id);
        $result->execute();

        $mo = $result->get_result()->fetch_assoc();
        $result->close();
        if (!empty($mo)) {
            return $mo;
        } else {
            return $this->getDefaultMedischOverzicht();
        }
    }

    private function getDefaultMedischOverzicht(): array
    {
        return [
            "medischevoorgeschiedenis" => "Geen medische voorgeschiedenis ingevuld",
            "medicatie" => "Geen medicatie ingevuld",
            "alergieen" => "Geen allergieën ingevuld",
            "opnamedatum" => "Geen opnamedatum ingevuld"
        ];
    }

    public function checkIfClientExistsById(int $id): bool
    {
        $result = $this->getClientById($id);

        return !empty($result);
    }

    public function checkIfClientExistsByName(string $name): bool
    {
        $result = $this->getClientByName($name);

        return !empty($result);
    }

    public function getById(int $id): ?array
    {
        $stmt = $this->db->prepare("
            SELECT c.*, a.naam AS afdeling
            FROM client c
                        LEFT JOIN afdelingen a ON a.id = c.afdeling_id
            WHERE c.id = ?
            LIMIT 1
        ");

        if (!$stmt) {
            return null;
        }

        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result();
        $client = $result ? $result->fetch_assoc() : null;
        $stmt->close();

        return $client ?: null;
    }

    public function getClientById($clientId): array
    {
        return $this->getById((int)$clientId) ?? [];
    }

    public function getClientByName($name): array
    {
        $result = $this->db->prepare("SELECT * FROM `client` WHERE naam = ?;");
        if (!$result) {
            return [];
        }
        $result->bind_param("s", $name);
        $result->execute();
        $row = $result->get_result()->fetch_assoc();
        $result->close();

        return $row ?: [];
    }

    public function getVerzorgersById($id): array
    {
        $result = $this->db->prepare("
        SELECT *
        FROM medewerker
        WHERE id = ?
        ");
        if (!$result) {
            return [];
        }

        $result->bind_param("i", $id);
        $result->execute();
        $row = $result->get_result()->fetch_assoc();
        $result->close();
        return $row ?: [];
    }

    public function getPatientGegevens($id, $type): array
    {
        $sql = match ($type) {
            'clientRelations' => "SELECT * FROM verzorgerregel WHERE clientid = ?",
            'contactPersonen' => "SELECT * FROM relatie WHERE clientid = ?",
            'medischOverzicht' => "SELECT * FROM medischoverzicht WHERE clientid = ?",
            'verzorgersArr' => "SELECT m.* FROM medewerker m JOIN verzorgerregel vr ON m.id = vr.medewerkerid WHERE vr.clientid = ?",
            default => null,
        };

        if ($sql === null) {
            return [];
        }

        $result = $this->db->prepare($sql);
        if (!$result) {
            return [];
        }

        $result->bind_param("i", $id);
        $result->execute();
        $rows = $result->get_result()->fetch_all(MYSQLI_ASSOC);
        $result->close();

        return $rows ?: [];
    }
}
