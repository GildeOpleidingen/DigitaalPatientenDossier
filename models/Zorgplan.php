<?php
trait Zorgplan
{
    public function insertCarePlan($clientId, $opsteldatumtijd, $patroontypeid, $P, $E, $S, $doelen, $interventies, $evaluatiedoelen): bool
    {
        if ($this->checkIfClientExistsById($clientId)) {
            if (!$this->checkIfCarePlanPatternTypeExists($clientId, $patroontypeid)) {
                $result = DatabaseConnection::getConn()->prepare("INSERT INTO `zorgplan`(`id`, `clientid`, `opsteldatumtijd`, `patroontypeid`, `P`, `E`, `S`, `doelen`, `interventies`, `evaluatiedoelen`) VALUES (NULL, ?, ?, ?, ?, ?, ?, ?, ?, ?);");
                $result->bind_param("issssssss", $clientId, $opsteldatumtijd, $patroontypeid, $P, $E, $S, $doelen, $interventies, $evaluatiedoelen);
                $result->execute();
                return true;
            } else {
                $result = DatabaseConnection::getConn()->prepare("UPDATE `zorgplan` SET `opsteldatumtijd`=?,`patroontypeid`=?,`P`=?,`E`=?,`S`=?,`doelen`=?,`interventies`=?,`evaluatiedoelen`=? WHERE clientid = ? AND patroontypeid = ?;");
                $result->bind_param("ssssssssii", $opsteldatumtijd, $patroontypeid, $P, $E, $S, $doelen, $interventies, $evaluatiedoelen, $clientId, $patroontypeid);
                $result->execute();
                return true;
            }
        } else {
            return false;
        }
    }

    public function checkIfCarePlanPatternTypeExists($id, $patternId): bool
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
