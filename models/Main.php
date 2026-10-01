<?php

class Main
{
    use Anamnese, Client, Convert, Formulier, Rapportage, Zorgplan, Afdeling;

    /**
     * @param int $id
     * @return array|null
     */
    public function findById(int $id): ?array
    {
        return MedewerkerModel::findById($id);
    }

    /**
     * @param string $email
     * @return array|null
     */
    public function findByEmail(string $email): ?array
    {
        return MedewerkerModel::findByEmail($email);
    }

    /**
     * @param string $email
     * @param string $wachtwoord
     * @return array|null
     */
    public function authenticate(string $email, string $wachtwoord): ?array
    {
        return MedewerkerModel::authenticate($email, $wachtwoord);
    }

    /**
     * @param int $id
     * @param string $name
     * @param string $class
     * @param string|null $photo
     * @param string $email
     * @param string $phoneNumber
     * @param string $password
     * @return bool
     */
    public function update(int $id, string $name, string $class, ?string $photo, string $email, string $phoneNumber, string $password): bool
    {
        return MedewerkerModel::update($id, $name, $class, $photo, $email, $phoneNumber, $password);
    }

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

    public function checkIfCareRelationExists(int $clientId, int $employeeId): bool
    {
        return $this->clientModel->checkIfCareRelationExists($clientId, $employeeId);
    }

    public function getMedicalOverviewByClientId(int $clientId): array
    {
        return $this->clientModel->getMedicalOverviewByClientId($clientId);
    }
    // ==========================================
    // PatroonModel Functions
    // ==========================================

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
     * @param int $patternId
     * @return array|null
     */
    public function getPatternType(int $patternId): ?array
    {
        return PatroonModel::getPatternType($patternId);
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
