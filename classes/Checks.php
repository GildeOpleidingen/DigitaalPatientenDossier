<?php
class Checks extends Get
{
    function checkIfClientExistsById(int $id): bool
    {
        $result = $this->getClientById($id);

        return sizeof((array) $result) > 0;
    }

    function checkIfClientExistsByName(string $name): bool
    {
        $result = $this->getClientByName($name);

        return sizeof((array) $result) > 0;
    }

    function checkIfClientStoryExistsByClientId($id): bool
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

    function checkIfMedischOverzichtExistsByClientId($clientid): bool
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

    function checkIfCarePlanExistsByClientId($id): bool
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

    function checkIfCarePlanPatternTypeExists($id, $patternId): bool
    {
        $result = DatabaseConnection::getConn()->prepare("
        SELECT cp.*
        FROM client c
        JOIN zorgplan cp on cp.clientid = c.id
        WHERE c.id = ? AND cp.patroontypeid = ?
        ");

        $result->bind_param("ii", $id, $patternId);
        $result->execute();
        $carePlan = $result->get_result()->fetch_array(MYSQLI_ASSOC);
        return sizeof((array) $carePlan) > 0;
    }
}
