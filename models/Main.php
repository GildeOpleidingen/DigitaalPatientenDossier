<?php

class Main
{
    use Anamnese, Client, Convert, Formulier, Rapportage, Zorgplan, Afdeling;

    /**
     * @param int $id
     * @return array|null
     */
    public function getMedewerkerById(int $id): ?array
    {
        return MedewerkerModel::findById($id);
    }

    /**
     * @param int $id
     * @return bool
     */
    public function checkIfMedewerkerExistsById(int $id): bool
    {
        return MedewerkerModel::findById($id) !== null;
    }

    public ClientModel $clientModel;

    public function __construct()
    {
        $this->clientModel = new ClientModel();
    }

    /**
     * @param int|string $clientId
     * @return array|null
     */
    public function getById($clientId): ?array
    {
        return $this->clientModel->getById((int)$clientId);
    }

    /**
     * @param int $clientId
     * @param int $employeeId
     * @return bool
     */
    public function checkIfCareRelationExists(int $clientId, int $employeeId): bool
    {
        return $this->clientModel->checkIfCareRelationExists($clientId, $employeeId);
    }

    /**
     * @param int $clientId
     * @return array
     */
    public function getMedicalOverviewByClientId(int $clientId): array
    {
        return $this->clientModel->getMedicalOverviewByClientId($clientId);
    }

    /**
     * @param int $clientId
     * @param int $employeeId
     * @return int|null
     */
    public function getQuestionnaireId(int $clientId, int $employeeId): ?int
    {
        return PatroonModel::getQuestionnaireId($clientId, $employeeId);
    }

    /**
     * @return array|null
     */
    public function getPatternTypes(): ?array
    {
        return PatroonModel::getPatternTypes();
    }

    /**
     * @param int $param1
     * @param int|null $param2
     * @return array|null
     */
    public function getPatternType(int $param1, ?int $param2 = null): ?array
    {
        if ($param2 === null) {
            $clientId = (int)($_SESSION['clientId'] ?? 0);
            $patternId = $param1;
        } else {
            $clientId = $param1;
            $patternId = $param2;
        }
        return PatroonModel::getPatternType($clientId, $patternId);
    }

    /**
     * @param int $value
     * @param int $min
     * @param int $max
     * @return bool
     */
    public function checkValue(int $value, int $min, int $max): bool
    {
        return PatroonModel::checkValue($value, $min, $max);
    }

    /**
     * @param int $clientId
     * @param int $patternType
     * @return array
     */
    public function getAnswers(int $clientId, int $patternType): array
    {
        return PatroonModel::getAnswers($clientId, $patternType);
    }

    /**
     * @param int $clientId
     * @param int $employeeId
     * @param int $patternNum
     * @param array $data
     * @return bool
     */
    public function saveAnswers(int $clientId, int $employeeId, int $patternNum, array $data): bool
    {
        return PatroonModel::saveAnswers($clientId, $employeeId, $patternNum, $data);
    }
}
