<?php
trait Zorgplan
{
    /**
     * @param int $clientId
     * @param string $opsteldatumtijd
     * @param int $patroontypeid
     * @param string $P
     * @param string $E
     * @param string $S
     * @param string $doelen
     * @param string $interventies
     * @param string $evaluatiedoelen
     * @return bool
     */
    public function insertCarePlan($clientId, $opsteldatumtijd, $patroontypeid, $P, $E, $S, $doelen, $interventies, $evaluatiedoelen): bool
    {
        if ($this->checkIfClientExistsById($clientId)) {
            $pdo = DatabaseConnection::getPdo();
            if (!$this->checkIfCarePlanPatternTypeExists($clientId, $patroontypeid)) {
                $stmt = $pdo->prepare("
                    INSERT INTO zorgplan (
                        clientid, opsteldatumtijd, patroontypeid, P, E, S, doelen, interventies, evaluatiedoelen
                    ) VALUES (
                        :clientId, :opsteldatumtijd, :patroontypeid, :P, :E, :S, :doelen, :interventies, :evaluatiedoelen
                    )
                ");
                return $stmt->execute([
                    'clientId'         => $clientId,
                    'opsteldatumtijd'  => $opsteldatumtijd,
                    'patroontypeid'    => $patroontypeid,
                    'P'                => $P,
                    'E'                => $E,
                    'S'                => $S,
                    'doelen'           => $doelen,
                    'interventies'     => $interventies,
                    'evaluatiedoelen'  => $evaluatiedoelen
                ]);
            } else {
                $stmt = $pdo->prepare("
                    UPDATE zorgplan 
                    SET opsteldatumtijd = :opsteldatumtijd, 
                        patroontypeid   = :patroontypeid, 
                        P               = :P, 
                        E               = :E, 
                        S               = :S, 
                        doelen          = :doelen, 
                        interventies    = :interventies, 
                        evaluatiedoelen = :evaluatiedoelen 
                    WHERE clientid = :clientId AND patroontypeid = :targetPatroontypeid
                ");
                return $stmt->execute([
                    'opsteldatumtijd'     => $opsteldatumtijd,
                    'patroontypeid'       => $patroontypeid,
                    'P'                   => $P,
                    'E'                   => $E,
                    'S'                   => $S,
                    'doelen'              => $doelen,
                    'interventies'        => $interventies,
                    'evaluatiedoelen'     => $evaluatiedoelen,
                    'clientId'            => $clientId,
                    'targetPatroontypeid' => $patroontypeid
                ]);
            }
        } else {
            return false;
        }
    }

    /**
     * @param int $id
     * @param int $patternId
     * @return bool
     */
    public function checkIfCarePlanPatternTypeExists($id, $patternId): bool
    {
        $stmt = DatabaseConnection::getPdo()->prepare("
            SELECT zorgplan.id
            FROM client
            INNER JOIN zorgplan ON zorgplan.clientid = client.id
            WHERE client.id = :clientId AND zorgplan.patroontypeid = :patternId
            LIMIT 1
        ");
        $stmt->execute([
            'clientId'  => $id,
            'patternId' => $patternId
        ]);

        return (bool)$stmt->fetch();
    }
}
