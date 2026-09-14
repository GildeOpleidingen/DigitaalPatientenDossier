<?php

class ClientModel
{
    private mysqli $db;

    public function __construct(?mysqli $db = null)
    {
        $this->db = $db ?? DatabaseConnection::getConn();
    }

    public function CheckIfVerzorgregelExists($clientId, $medewerkerId)
    {
        try {
            $result = $this->db->prepare("
                        SELECT id
                        FROM verzorgerregel
                        WHERE clientid = ?
                        AND medewerkerid = ?");
            $result->bind_param("ii", $clientId, $medewerkerId);
            $result->execute();

            if ($result->num_rows == 0) {
                $result->close();
                $result = $this->db->prepare("INSERT INTO verzorgerregel (clientid, medewerkerid) VALUES (?, ?)");
                $result->bind_param("ii", $clientId, $medewerkerId);
                $result->execute();
                return true;
            } else {
                return true;
            }
        } catch (Exception $e) {
            return false;
        }
    }

    public function insertClientStory($clientid, $foto, $introductie, $familie, $belangrijkeinfo, $hobbies): bool
    {
        $medischOverzicht = $this->getMedischOverzichtByClientId($clientid);
        if ($this->checkIfClientExistsById($clientid) && count($medischOverzicht) > 0) {
            if (!$this->checkIfClientStoryExistsByClientId($clientid)) {
                if (!$this->checkIfMedischOverzichtExistsByClientId($clientid)) {
                    $result = $this->db->prepare("INSERT INTO `medischoverzicht`(`clientid`) VALUES (?);");
                    $result->bind_param("i", $clientid);
                    $result->execute();

                    $medischOverzichtId = $this->db->insert_id;
                } else {
                    $medischOverzicht = $this->getMedischOverzichtByClientId($clientid);
                    $medischOverzichtId = $medischOverzicht['id'];
                }

                $result = $this->db->prepare("INSERT INTO `clientverhaal`(`id`, `medischoverzichtid`, `foto`, `introductie`, `gezinfamilie`, `belangrijkeinfo`, `hobbies`) VALUES (NULL, ?, ?, ?, ?, ?, ?);");
                $result->bind_param("isssss", $medischOverzichtId, $foto, $introductie, $familie, $belangrijkeinfo, $hobbies);
                if ($result->execute()) {
                    return true;
                } else {
                    return "Insert failed: " . $result->error;
                }
            } else {
                $result = $this->db->prepare("UPDATE `clientverhaal` SET `foto`=?,`introductie`=?,`gezinfamilie`=?,`belangrijkeinfo`=?,`hobbies`=? WHERE medischoverzichtid = ?;");
                $result->bind_param("sssssi", $foto, $introductie, $familie, $belangrijkeinfo, $hobbies, $medischOverzicht['id']);
                if ($result->execute()) {
                    return true;
                } else {
                    return "Update failed: " . $result->error;
                }
            }
        } else {
            return false;
        }
    }

    public function updateClient($naam, $geslacht, $adres, $postcode, $woonplaats, $telefoonnummer, $email, $reanimatiestatus, $nationaliteit, $afdeling, $burgelijkestaat, $foto): bool
    {
        $result = $this->db->prepare("UPDATE `client` SET `geslacht`=?,`adres`=?,`postcode`=?,`woonplaats`=?,`telefoonnummer`=?,`email`=?,`reanimatiestatus`=?,`nationaliteit`=?,`afdeling`=?,`burgelijkestaat`=?,`foto`=? WHERE `naam`=?;");
        $result->bind_param("ssssssssssss", $geslacht, $adres, $postcode, $woonplaats, $telefoonnummer, $email, $reanimatiestatus, $nationaliteit, $afdeling, $burgelijkestaat, $foto, $naam);
        $result->execute();

        if ($result->affected_rows == 1)
            return true;

        if ($result->affected_rows <= 0) {
            $query = $this->db->prepare("SELECT * FROM `client` WHERE naam= ?");
            $query->execute();
            $query = $query->get_result()->fetch_all();
            $result->bind_param("s", $naam);
            if (sizeof($query) == 0) {
                //            DatabaseConnection::getConn()->prepare("INSERT INTO `client`(`naam`, `geslacht`, `adres`, `postcode`, `woonplaats`, `telefoonnummer`, `email`, `reanimatiestatus`, `nationaliteit`, `afdeling`, `burgelijkestaat`, `foto`) VALUES ('$naam','$geslacht','$adres','$postcode','$woonplaats','$telefoonnummer','$email','$reanimatiestatus','$nationaliteit','$afdeling','$burgelijkestaat','$foto');");
                $result = $this->db->prepare("INSERT INTO `client`(`naam`, `geslacht`, `adres`, `postcode`, `woonplaats`, `telefoonnummer`, `email`, `reanimatiestatus`, `nationaliteit`, `afdeling`, `burgelijkestaat`, `foto`) VALUES (?,?,?,?,?,?,?,?,?,?,?,?);");
                $result->bind_param("sssssssssss", $naam, $geslacht, $adres, $postcode, $woonplaats, $telefoonnummer, $email, $reanimatiestatus, $nationaliteit, $afdeling, $burgelijkestaat, $foto);
                $result->execute();
                return true;
            }
        }

        return false;
    }

    public function checkIfClientStoryExistsByClientId($id): bool
    {
        $result = $this->db->prepare("
        SELECT cv.*
        FROM client c
        JOIN medischoverzicht mo on mo.clientid = c.id 
        JOIN clientverhaal cv on cv.medischoverzichtid = mo.id
        WHERE c.id = ?
        ");
        $result->bind_param("i", $id);
        $result->execute();

        if ($result->get_result()->num_rows > 0) {
            return true;
        } else {
            return false;
        }
    }

    public function checkIfMedischOverzichtExistsByClientId($clientid): bool
    {
        $result = $this->db->prepare("
        SELECT *
        FROM medischoverzicht
        WHERE clientid = ?
        ");
        $result->bind_param("i", $clientid);
        $result->execute();

        if ($result->get_result()->num_rows > 0) {
            return true;
        } else {
            return false;
        }
    }

    public function checkIfCarePlanExistsByClientId($id): bool
    {
        $result = $this->db->prepare("
        SELECT cp.*
        FROM client c
        JOIN zorgplan cp on cp.clientid = c.id
        WHERE c.id = ?
        ");
        $result->bind_param("i", $id);
        $result->execute();

        if ($result->get_result()->num_rows > 0) {
            return true;
        } else {
            return false;
        }
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

        $result->bind_param("i", $id);
        $result->execute();

        return (array) $result->get_result()->fetch_array();
    }

    public function getCarePlanByClientId($id): array
    {
        $result = $this->db->prepare("
        SELECT cp.*
        FROM client c
        JOIN zorgplan cp on cp.clientid = c.id
        WHERE c.id = ?
        ");

        $result->bind_param("i", $id);
        $result->execute();
        return (array) $result->get_result()->fetch_array(MYSQLI_ASSOC);
    }

    public function getVerzorgerregelByClientId($id): array
    {
        $result = $this->db->prepare("
        SELECT *
        FROM verzorgerregel
        WHERE clientid = ?
        ");

        $result->bind_param("i", $id);
        $result->execute();
        return $result->get_result()->fetch_all(MYSQLI_ASSOC);
    }

    public function getAdmissionDateByClientId($id): string
    {
        $result = $this->db->prepare("
        select opnamedatum
        from medischoverzicht
        where clientid = ?
        ");
        $result->bind_param("i", $id);
        $result->execute();
        $opnamedatum = $result->get_result()->fetch_assoc();

        if ($opnamedatum && isset($opnamedatum['opnamedatum'])) {
            if ($opnamedatum['opnamedatum'] !== '0000-00-00 00:00:00') {
                return $opnamedatum['opnamedatum'];
            } else {
                return "Geen opnamedatum ingevuld";
            }
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

    public function checkIfClientExistsById(int $id): bool
    {
        $result = $this->getClientById($id);

        return sizeof((array) $result) > 0;
    }

    public function checkIfClientExistsByName(string $name): bool
    {
        $result = $this->getClientByName($name);

        return sizeof((array) $result) > 0;
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
        $result->bind_param("s", $name);
        $result->execute();

        return (array) $result->get_result()->fetch_array();
    }

    public function getVerzorgersById($id): array
    {
        $result = $this->db->prepare("
        SELECT *
        FROM medewerker
        WHERE id = ?
        ");

        $result->bind_param("i", $id);
        $result->execute();
        return (array) $result->get_result()->fetch_array(MYSQLI_ASSOC);
    }

    public function getPatientGegevens($id, $type)
    {
        if ($type == 'clientRelations') {
            $result = $this->db->prepare("
            SELECT * 
            FROM verzorgerregel 
            WHERE clientid = ?
            ");

            $result->bind_param("i", $id);
            $result->execute();
            return $result->get_result()->fetch_all(MYSQLI_ASSOC);
        } elseif ($type == 'contactPersonen') {
            $result = $this->db->prepare("
            SELECT * 
            FROM relatie 
            WHERE clientid = ?
            ");

            $result->bind_param("i", $id);
            $result->execute();
            return $result->get_result()->fetch_all(MYSQLI_ASSOC);
        } elseif ($type == 'medischOverzicht') {
            $result = $this->db->prepare("
            SELECT * 
            FROM medischoverzicht 
            WHERE clientid = ?
            ");

            $result->bind_param("i", $id);
            $result->execute();
            return $result->get_result()->fetch_all(MYSQLI_ASSOC);
        } elseif ($type == 'verzorgersArr') {
            $result = $this->db->prepare("
            SELECT m.* 
            FROM medewerker m
            JOIN verzorgerregel vr ON m.id = vr.medewerkerid
            WHERE vr.clientid = ?
            ");

            $result->bind_param("i", $id);
            $result->execute();
            return $result->get_result()->fetch_all(MYSQLI_ASSOC);
        } else {
            return false;
        }
    }
}
