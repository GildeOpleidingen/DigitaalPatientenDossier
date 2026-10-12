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
            $result = DatabaseConnection::getConn()->prepare("
                        SELECT id
                        FROM verzorgerregel
                        WHERE clientid = ?
                        AND medewerkerid = ?");
            $result->bind_param("ii", $clientId, $medewerkerId);
            $result->execute();

            if ($result->num_rows == 0) {
                $result->close();
                $result = DatabaseConnection::getConn()->prepare("INSERT INTO verzorgerregel (clientid, medewerkerid) VALUES (?, ?)");
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
            if (!$this->checkIfClientStoryExistsByClientId($clientid)) {
                if (!$this->checkIfMedischOverzichtExistsByClientId($clientid)) {
                    $result = DatabaseConnection::getConn()->prepare("INSERT INTO `medischoverzicht`(`clientid`) VALUES (?);");
                    $result->bind_param("i", $clientid);
                    $result->execute();

                    $medischOverzichtId = DatabaseConnection::getConn()->insert_id;
                } else {
                    $medischOverzicht = $this->getMedischOverzichtByClientId($clientid);
                    $medischOverzichtId = $medischOverzicht['id'];
                }

                $result = DatabaseConnection::getConn()->prepare("INSERT INTO `clientverhaal`(`id`, `medischoverzichtid`, `foto`, `introductie`, `gezinfamilie`, `belangrijkeinfo`, `hobbies`) VALUES (NULL, ?, ?, ?, ?, ?, ?);");
                $result->bind_param("isssss", $medischOverzichtId, $foto, $introductie, $familie, $belangrijkeinfo, $hobbies);
                if ($result->execute()) {
                    return true;
                } else {
                    return "Insert failed: " . $result->error;
                }
            } else {
                $result = DatabaseConnection::getConn()->prepare("UPDATE `clientverhaal` SET `foto`=?,`introductie`=?,`gezinfamilie`=?,`belangrijkeinfo`=?,`hobbies`=? WHERE medischoverzichtid = ?;");
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
        $result = DatabaseConnection::getConn()->prepare("UPDATE `client` SET `geslacht`=?,`adres`=?,`postcode`=?,`woonplaats`=?,`telefoonnummer`=?,`email`=?,`reanimatiestatus`=?,`nationaliteit`=?,`afdeling`=?,`burgelijkestaat`=?,`foto`=? WHERE `naam`=?;");
        $result->bind_param("ssssssssssss", $geslacht, $adres, $postcode, $woonplaats, $telefoonnummer, $email, $reanimatiestatus, $nationaliteit, $afdeling, $burgelijkestaat, $foto, $naam);
        $result->execute();

        if ($result->affected_rows == 1)
            return true;

        if ($result->affected_rows <= 0) {
            $query = DatabaseConnection::getConn()->prepare("SELECT * FROM `client` WHERE naam= ?");
            $query->execute();
            $query = $query->get_result()->fetch_all();
            $result->bind_param("s", $naam);
            if (sizeof($query) == 0) {
                //            DatabaseConnection::getConn()->prepare("INSERT INTO `client`(`naam`, `geslacht`, `adres`, `postcode`, `woonplaats`, `telefoonnummer`, `email`, `reanimatiestatus`, `nationaliteit`, `afdeling`, `burgelijkestaat`, `foto`) VALUES ('$naam','$geslacht','$adres','$postcode','$woonplaats','$telefoonnummer','$email','$reanimatiestatus','$nationaliteit','$afdeling','$burgelijkestaat','$foto');");
                $result = DatabaseConnection::getConn()->prepare("INSERT INTO `client`(`naam`, `geslacht`, `adres`, `postcode`, `woonplaats`, `telefoonnummer`, `email`, `reanimatiestatus`, `nationaliteit`, `afdeling`, `burgelijkestaat`, `foto`) VALUES (?,?,?,?,?,?,?,?,?,?,?,?);");
                $result->bind_param("sssssssssss", $naam, $geslacht, $adres, $postcode, $woonplaats, $telefoonnummer, $email, $reanimatiestatus, $nationaliteit, $afdeling, $burgelijkestaat, $foto);
                $result->execute();
                return true;
            }
        }

        return false;
    }

    /**
     * @param int $id
     * @return bool
     */
    public function checkIfClientStoryExistsByClientId($id): bool
    {
        $result = DatabaseConnection::getConn()->prepare("
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

    /**
     * @param int $clientid
     * @return bool
     */
    public function checkIfMedischOverzichtExistsByClientId($clientid): bool
    {
        $result = DatabaseConnection::getConn()->prepare("
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

    /**
     * @param int $id
     * @return bool
     */
    public function checkIfCarePlanExistsByClientId($id): bool
    {
        $result = DatabaseConnection::getConn()->prepare("
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

    /**
     * @param int $id
     * @return array
     */
    public function getClientStoryByClientId($id): array
    {
        $result = DatabaseConnection::getConn()->prepare("
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

    /**
     * @param int $id
     * @return array
     */
    public function getCarePlanByClientId($id): array
    {
        $result = DatabaseConnection::getConn()->prepare("
        SELECT cp.*
        FROM client c
        JOIN zorgplan cp on cp.clientid = c.id
        WHERE c.id = ?
        ");

        $result->bind_param("i", $id);
        $result->execute();
        return (array) $result->get_result()->fetch_array(MYSQLI_ASSOC);
    }

    /**
     * @param int $id
     * @return array
     */
    public function getVerzorgerregelByClientId($id): array
    {
        $result = DatabaseConnection::getConn()->prepare("
        SELECT *
        FROM verzorgerregel
        WHERE clientid = ?
        ");

        $result->bind_param("i", $id);
        $result->execute();
        return $result->get_result()->fetch_all(MYSQLI_ASSOC);
    }

    /**
     * @param int $id
     * @return string
     */
    public function getAdmissionDateByClientId($id): string
    {
        $result = DatabaseConnection::getConn()->prepare("
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

    /**
     * @param int $id
     * @return array
     */
    public function getMedischOverzichtByClientId($id): array
    {
        $result = DatabaseConnection::getConn()->prepare("
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
        $result = DatabaseConnection::getConn()->prepare("SELECT c.*, a.naam as afdeling FROM client c LEFT JOIN afdelingen a on a.id = c.afdeling_id WHERE c.id =?;");
        $result->bind_param("i", $ClientId);
        $result->execute();

        return (array) $result->get_result()->fetch_array(MYSQLI_ASSOC);
    }

    /**
     * @param string $name
     * @return array
     */
    public function getClientByName($name): array
    {
        $result = DatabaseConnection::getConn()->prepare("SELECT * FROM `client` WHERE naam = ?;");
        $result->bind_param("s", $name);
        $result->execute();

        return (array) $result->get_result()->fetch_array();
    }

    /**
     * @param int $id
     * @return array
     */
    public function getVerzorgersById($id): array
    {
        $result = DatabaseConnection::getConn()->prepare("
        SELECT *
        FROM medewerker
        WHERE id = ?
        ");

        $result->bind_param("i", $id);
        $result->execute();
        return (array) $result->get_result()->fetch_array(MYSQLI_ASSOC);
    }

    /**
     * @param int $id
     * @param 'clientRelations'|'contactPersonen'|'medischOverzicht'|'verzorgersArr'|string $type
     * @return array|false
     */
    public function getPatientGegevens($id, $type)
    {
        if ($type == 'clientRelations') {
            $result = DatabaseConnection::getConn()->prepare("
            SELECT * 
            FROM verzorgerregel 
            WHERE clientid = ?
            ");

            $result->bind_param("i", $id);
            $result->execute();
            return $result->get_result()->fetch_all(MYSQLI_ASSOC);
        } elseif ($type == 'contactPersonen') {
            $result = DatabaseConnection::getConn()->prepare("
            SELECT * 
            FROM relatie 
            WHERE clientid = ?
            ");

            $result->bind_param("i", $id);
            $result->execute();
            return $result->get_result()->fetch_all(MYSQLI_ASSOC);
        } elseif ($type == 'medischOverzicht') {
            $result = DatabaseConnection::getConn()->prepare("
            SELECT * 
            FROM medischoverzicht 
            WHERE clientid = ?
            ");

            $result->bind_param("i", $id);
            $result->execute();
            return $result->get_result()->fetch_all(MYSQLI_ASSOC);
        } elseif ($type == 'verzorgersArr') {
            $result = DatabaseConnection::getConn()->prepare("
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
