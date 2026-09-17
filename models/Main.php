<?php

class Main
{
    use Anamnese, Client, Convert, Formulier, Rapportage, Zorgplan, Afdeling;

    public function findById(int $id): ?array
    {
        return MedewerkerModel::findById($id);
    }

    public function findByEmail(string $email): ?array
    {
        return MedewerkerModel::findByEmail($email);
    }

    public function authenticate(string $email, string $wachtwoord): ?array
    {
        return MedewerkerModel::authenticate($email, $wachtwoord);
    }

    public function update(int $id, string $name, string $class, ?string $photo, string $email, string $phoneNumber, string $password): bool
    {
        return MedewerkerModel::update($id, $name, $class, $photo, $email, $phoneNumber, $password);
    }

    public function getMedewerkerById(int $id): ?array
    {
        return MedewerkerModel::findById($id);
    }

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
     * Fetch a client via ClientModel.
     */
    public function getById($clientId): ?array
    {
        return $this->clientModel->getById((int)$clientId);
    }

    // ==========================================
    // PatroonModel Functions
    // ==========================================

    public function getQuestionnaireId(int $clientId, int $employeeId): ?int
    {
        return PatroonModel::getQuestionnaireId($clientId, $employeeId);
    }

    public function getPatternTypes(): ?array
    {
        return PatroonModel::getPatternTypes();
    }

    public function getPatternType(int $patternId): ?array
    {
        return PatroonModel::getPatternType($patternId);
    }

    public function checkValue(int $value, int $min, int $max): bool
    {
        return PatroonModel::checkValue($value, $min, $max);
    }

    public function getAnswers(int $clientId, int $patternType): array
    {
        return PatroonModel::getAnswers($clientId, $patternType);
    }

    public function saveAnswers(int $clientId, int $medewerkerId, int $patternNum, array $data): bool
    {
        return PatroonModel::saveAnswers($clientId, $medewerkerId, $patternNum, $data);
    }
}
