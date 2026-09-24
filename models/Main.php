<?php

class Main
{
    use Anamnese, Client, Convert, Formulier, Patroon, Rapportage, Zorgplan, Afdeling;

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
     * Haal een cliënt op via het nieuwe ClientModel.
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
}
